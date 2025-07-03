<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate();

        return view('admin.users.index', compact('users'));
    }
    public function create()
    {
        return view('admin.users.create');
    }
    public function store()
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'is_admin' => true,
        ]);
        Alert::success('Success', 'User created successfully.');

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }
    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('admin.users.edit', compact('user'));
    }
    public function update($id)
    {
        $user = User::where('id', $id)->first();

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password') ? bcrypt(request('password')) : $user->password,
        ]);
        Alert::success('Success', 'User updated successfully.');
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        Alert::success('Success', 'User deleted successfully.');
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
