<?php

namespace App\Services;

use App\Models\RegistrationToken;
use DateTime;
use Illuminate\Support\Str;

class RegistrationTokenService
{
    public function getTokens()
    {
        return RegistrationToken::all();
    }

    public function createToken(string $proposed_username)
    {
        // Generate a token, and if a token with the same value already exists, generate a new one.
        do {
            $token = Str::random(10);
        } while (RegistrationToken::where('value', $token)->where('is_valid', 1)->exists());

        $registrationToken = new RegistrationToken();
        $registrationToken->proposed_username = $proposed_username;
        $registrationToken->value = $token;
        $registrationToken->expired_time = (new DateTime())->modify('+30 days')->format('Y-m-d H:i:s');
        $registrationToken->save();

        return $registrationToken;
    }

    // Get the record if the provided token is valid and not expired
    public function getToken(string $token)
    {
        $tokenRecord = RegistrationToken::where('is_valid', 1)
            ->where('value', $token)
            ->where('expired_time', '>', (new DateTime())->format('Y-m-d H:i:s'))
            ->first();

        return $tokenRecord;
    }

    public function setTokenUsedBy(RegistrationToken $tokenRecord, string $user_id)
    {
        $tokenRecord->is_valid = 0;
        $tokenRecord->used_time = (new DateTime())->format('Y-m-d H:i:s');
        $tokenRecord->user_id = $user_id;
        $tokenRecord->save();
    }
}
