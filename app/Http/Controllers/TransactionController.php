<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::latest()->get();
            return DataTables::of($data)
                ->editColumn('user_id', function (Transaction $transaction) {
                    $transaction = Transaction::with('user')->find($transaction->id);
                    return $transaction->user->name;
                })
                ->editColumn('car_id', function (Transaction $transaction) {
                    $transaction = Transaction::with('car')->find($transaction->id);
                    return $transaction->car->name;
                })
                ->editColumn('date_start', function (Transaction $transaction) {
                    return Carbon::parse($transaction->date_start)->format('d F Y');
                })
                ->editColumn('date_end', function (Transaction $transaction) {
                    return Carbon::parse($transaction->date_end)->format('d F Y');
                })
                ->editColumn('total', function (Transaction $transaction) {
                    // $transaction = Transaction::with('car')->find($transaction->id);
                    return number_format($transaction->total, 2);
                })
                ->addColumn('action', function (Transaction $transaction) {
                    $btn = '<a href=' . route("transaction.edit", $transaction->id) . ' class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                    $btn = $btn . '<form class="d-inline" action=' . route("transaction.destroy", $transaction->id) . ' method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value=' . csrf_token() . '>
                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                    </form>';
                    $btn = $btn . '<a href=' . route("transaction.show", $transaction->id) . ' class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';
                    $btn .= '<form class="d-inline" action="' . route('transaction.status', ['transaction' => $transaction->id, 'car' => $transaction->car_id]) . '" method="POST">';
                    $btn .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $btn .= '<input type="hidden" name="status" value="SELESAI">';
                    $btn .= '<button class="btn btn-outline-success btn-sm" type="submit"><i class="far fa-check-circle"></i></button>';
                    $btn .= '</form>';

                    $btn .= '<form class="d-inline" action="' . route('transaction.status', ['transaction' => $transaction->id, 'car' => $transaction->car_id]) . '" method="POST">';
                    $btn .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $btn .= '<input type="hidden" name="status" value="DIPINJAM">';
                    $btn .= '<button class="btn btn-outline-danger btn-sm" type="submit"><i class="far fa-times-circle"></i></button>';
                    $btn .= '</form>';


                    return $btn;
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'modal'])
                ->make(true);
        }
        return view('transaction.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cars = Car::all();
        $users = User::whereHas('detail', function ($query) {
            $query->whereNotNull('sim');
        })->get();
        return view('transaction.create', compact('cars', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'    =>  'required',
            'car_id'    =>  'required',
            'date_start'    =>  'required',
            'date_end'    =>  'required',
            'note'    =>  'required',
        ]);

        $data['date_due'] = $request->date_end;
        $data['status'] = 'PENDING';
        $price = Car::find($request->car_id);
        $date_start = new DateTime($request->date_start);
        $date_end = new DateTime($request->date_end);
        $duration = $date_start->diff($date_end);
        $data['total'] = $price->price * $duration->days;

        Transaction::create($data);

        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction = Transaction::with('user', 'car')->find($transaction->id);
        return view('transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $users = User::all();
        $cars = Car::all();
        return view('transaction.edit', compact('users', 'cars', 'transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'user_id'    =>  'required',
            'car_id'    =>  'required',
            'date_start'    =>  'required',
            'date_end'    =>  'required',
            'note'    =>  'required',
        ]);
        $price = Car::find($request->car_id);
        $date_start = new DateTime($request->date_start);
        $date_end = new DateTime($request->date_end);
        $duration = $date_start->diff($date_end);
        $data['total'] = $price->price * $duration->days;
        $transaction->update($data);
        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        Transaction::destroy($transaction->id);
        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil dihapus');
    }

    public function status(Request $request, Transaction $transaction, Car $car)
    {
        DB::beginTransaction();

        try {
            // Update the transaction status
            $transaction->update([
                'status' => $request->status
            ]);

            // Now update the car status based on the provided car_id
            $car->update([
                'status' => $request->status === 'SELESAI' ? true : false
            ]);

            DB::commit();
            return redirect()->route('transaction.index')->with('success', 'Transaction and Car status updated successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            DB::rollback();
            Log::error('Error updating transaction and/or car status: ' . $e->getMessage());
            return redirect()->route('transaction.index')->with('error', 'An error occurred while updating transaction and/or car status.');
        }
    }
}
