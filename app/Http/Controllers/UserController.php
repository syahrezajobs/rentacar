<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $users = User::where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->latest()
            ->paginate(10);
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  =>  'required',
            'email'  =>  'required|email:rfc,dns|unique:App\Models\User,email',
        ]);
        $data['password'] = Crypt::encrypt(Str::random(8));

        $user = User::create($data);

        $userDetailData = $request->validate([
            'address' => 'required',
            'phone' => 'required|string|max:20',
            'sim' => 'required|string|max:20',
        ]);

        // Create the UserDetail model for the user and associate it
        $userDetail = new UserDetail($userDetailData);
        $user->detail()->save($userDetail);

        return redirect()->route('user.index')->with('success', 'Data pelanggan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  =>  'required',
            'email'  =>  'required|email:rfc,dns',
        ]);

        $user->update($data);

        // Retrieve or create the associated UserDetail for the User
        $userDetail = $user->detail ?: new UserDetail();

        $userDetailData = $request->validate([
            'address' => 'required',
            'phone' => 'required|string|max:20',
            'sim' => 'required|string|max:20',
        ]);

        $userDetail->fill($userDetailData);
        $user->detail()->save($userDetail);

        return redirect()->route('user.index')->with('success', 'Data pelanggan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->image) {
            Storage::delete($user->image);
        }
        User::destroy($user->id);
        return redirect()->route('user.index')->with('success', 'Data pelanggan berhasil dihapus');
    }
}
