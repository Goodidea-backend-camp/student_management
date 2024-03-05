<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('progress', [
            'students' => User::where('role', 'student')->get()
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'start_date' => ['date'],
            'leave_date' => ['date'],
        ]);

        // start_date could be set only if it's null and request body contains start_date
        if (is_null($user->start_date) && isset($validated['start_date'])) {
            $user->start_date = $validated['start_date'];

            // proposed_leave_date is six months after start_date
            $user->proposed_leave_date = (new DateTime($validated['start_date']))->modify('+6 months');
        }

        // leave_date could be set only if it's null and request body contains leave_date
        if (is_null($user->leave_date) && isset($validated['leave_date'])) {
            $user->leave_date = $validated['leave_date'];
        }

        $user->save();

        return redirect('/students');
    }
}
