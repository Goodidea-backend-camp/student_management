<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'regex:/^09\d{8}$/', 'unique:'.User::class],
            'password' => ['required', 'confirmed',
                Password::min(8)
                    ->max(255)
                    ->mixedCase()
                    ->numbers()
            ],
        ]);

        $user = User::create($validated);

        return response($user, 201);
    }
}
