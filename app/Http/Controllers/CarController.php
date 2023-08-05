<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $availability = $request->availability; // Check if the checkbox is checked
        $cars = Car::select('cars.*', 'brands.name as brand_name', 'types.name as type_name')
            ->join('brands', 'cars.brand_id', '=', 'brands.id')
            ->join('types', 'cars.type_id', '=', 'types.id')
            ->where(function ($query) use ($search) {
                $query->where('cars.name', 'like', '%' . $search . '%')
                    ->orWhere('plat', 'like', '%' . $search . '%')
                    ->orWhere('price', 'like', '%' . $search . '%')
                    ->orWhere('brands.name', 'like', '%' . $search . '%')
                    ->orWhere('types.name', 'like', '%' . $search . '%');
            })
            ->when($availability, function ($query) {
                // If 'availability' checkbox is checked (value = 1), filter only available cars
                return $query->where('status', '=', 1);
            })
            ->latest()
            ->paginate(10);
        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $types = Type::all();
        return view('cars.create', compact('brands', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  =>  'required',
            'plat'  =>  'required',
            'brand_id'  =>  'required',
            'type_id'  =>  'required',
            'description'  =>  'required',
            'price'  =>  'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'  =>  'required',
        ]);

        if ($request->file('image')) {
            $data['image'] = $request->file('image')->store('cars');
        }

        Car::create($data);

        return redirect()->route('car.index')->with('success', 'Data mobil berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        $brands = Brand::all();
        $types = Type::all();
        return view('cars.edit', ['car' => $car], compact('brands', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $data = $request->validate([
            'name'  =>  'required',
            'plat'  =>  'required',
            'brand_id'  =>  'required',
            'type_id'  =>  'required',
            'description'  =>  'required',
            'price'  =>  'required|integer',
            'status'  =>  'required',
        ]);
        if ($request->file('image')) {
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $data['image'] = $request->file('image')->store('cars');
        }
        $car->update($data);
        return redirect()->route('car.index')->with('success', 'Data mobil berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        if ($car->image) {
            Storage::delete($car->image);
        }
        Car::destroy($car->id);
        return redirect()->route('car.index')->with('success', 'Data mobil berhasil dihapus');
    }
}
