<?php

namespace App\Services;

use App\Models\User;
use DateTime;

class StudentService
{
    public function getStudents()
    {
        return User::where('role', 'student')->get();
    }

    // Create a student with validated data
    public function createStudent(array $validated)
    {
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->password = $validated['password'];
        $user->save();

        return $user;
    }

    public function updateStudent(User $user, array $validated)
    {
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
    }
}
