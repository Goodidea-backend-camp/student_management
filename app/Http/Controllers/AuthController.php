<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'email|required|max:255',
            'password' => 'required',
        ]);

        if (! $token = Auth::attempt($validated)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return response()->json([
            'user' => Auth::user()->only('id', 'name', 'email'),
            'token' => $token
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->noContent();
    }
}
