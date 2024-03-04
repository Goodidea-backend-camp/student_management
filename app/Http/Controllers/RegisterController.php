<?php

namespace App\Http\Controllers;

use App\Models\RegistrationToken;
use DateTime;
use Illuminate\Http\Request;
use App\Models\User;
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
            'token' => 'required'
        ]);

        // Get the record of the provided token
        $record = RegistrationToken::where('is_valid', 1)
            ->where('value', $request['token'])
            ->where('expired_time', '>', (new DateTime())->format('Y-m-d H:i:s'))
            ->first();

        if (empty($record)) {
           return response('Your registration token is incorrect', 422);
        }

        $user = User::create($validated);

        // Update registration_tokens
        $record->is_valid = 0;
        $record->used_time = (new DateTime())->format('Y-m-d H:i:s');
        $record->user_id = $user['id'];
        $record->save();

        return response($user, 201);
    }
}
