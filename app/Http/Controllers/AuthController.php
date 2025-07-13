<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function login(AuthRequest $request)
    {
        $request->validated();

        $credentials = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        if (!$credentials) {
            return redirect()->back()->with('error', 'Invalid credentials');
        }

        $user = Auth::user();
        $user = User::find($user->id);

        return redirect()->route('dashboard')->with('success', 'Login successful');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(UserRequest $request)
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // ← Criptografa corretamente
            ]);

            return redirect()->route('login')->with('success', 'Usuário cadastrado!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao cadastrar usuário.');
        }
    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Logout successful');
    }
}
