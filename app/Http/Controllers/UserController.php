<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('id')->paginate(1);
        return view("users.index", ['users' => $users]);
    }
    public function create()
    {
        return view("users.create");
    }

    public function store(UserRequest $request)
    {

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return redirect()->route('users.create')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create user.');
        }
    }

    public function show(User $user)
    {
        return view("users.show", ['user' => $user]);
    }

    public function edit(User $user)
    {
        return view("users.edit", ['user' => $user]);
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return redirect()->route('users.edit', ['user' => $user->id])->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return back()->withinput()->with('error', 'Failed to update user.');
        }
    }

    public function profile()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Carrega os itens da watchlist (filmes e séries)
        $watchlistMovies = $user->watchlistMovies()->latest('watchlist.created_at')->take(5)->get();
        $watchlistSeries = $user->watchlistSeries()->latest('watchlist.created_at')->take(5)->get();

        return view('public.profile.index', compact('user', 'watchlistMovies', 'watchlistSeries'));
    }


    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user.');
        }
    }
}
