<?php

namespace App\Http\Controllers;

use App\Services\RegistrationTokenService;
use Illuminate\Http\Request;

class RegistrationTokenController extends Controller
{
    public function __construct(
        protected RegistrationTokenService $registrationTokenService
    ){}

    public function index()
    {
        $tokens = $this->registrationTokenService->getTokens();

        return view('token.index', ['tokens' => $tokens]);
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
           'proposed_username' => ['required', 'string', 'max:50'],
       ]);

       $createdToken = $this->registrationTokenService->createToken($validated['proposed_username']);

       $registrationLink = config('app.frontend_url') . '?token=' . $createdToken->value;

       return redirect('/tokens')
           ->with('link', $registrationLink)
           ->with('name', $createdToken->proposed_username);
    }
}
