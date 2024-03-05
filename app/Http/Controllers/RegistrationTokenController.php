<?php

namespace App\Http\Controllers;

use App\Models\RegistrationToken;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationTokenController extends Controller
{
    public function index()
    {
        return view('token.index', ['tokens' => RegistrationToken::all()]);
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
           'proposed_username' => ['required', 'string', 'max:50'],
       ]);

       // Generate a token, and if a token with the same value already exists, generate a new one.
       do {
           $token = Str::random(10);
       } while (RegistrationToken::where('value', $token)->where('is_valid', 1)->exists());

       // The link should be updated with real URL after deployment
       $registrationLink = config('app.frontend_url') . '?token=' . $token;

       RegistrationToken::create(
           array_merge($validated, [
               'value' => $token,
               'expired_time' => (new DateTime())->modify('+30 days')->format('Y-m-d H:i:s')
           ])
       );

       return redirect('/tokens')
           ->with('link', $registrationLink)
           ->with('name', $validated['proposed_username']);
    }
}
