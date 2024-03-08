<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\RegistrationTokenService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    public function __construct(
       protected StudentService $studentService,
       protected RegistrationTokenService $registrationTokenService
    ) {}

    public function index()
    {
        $students = $this->studentService->getStudents();

        return view('progress', [
            'students' => $students
        ]);
    }

    public function create(Request $request)
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

        $tokenRecord = $this->registrationTokenService->getToken($validated['token']);

        if (is_null($tokenRecord)) {
            return response('Your registration token is incorrect', 422);
        }

        $createdStudent = $this->studentService->createStudent($validated);
        $this->registrationTokenService->setTokenUsedBy(tokenRecord: $tokenRecord, user_id: $createdStudent->id);

        return response($createdStudent, 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'start_date' => ['date'],
            'leave_date' => ['date'],
        ]);

        $this->studentService->updateStudent(user: $user, validated: $validated);

        return redirect('/students');
    }
}
