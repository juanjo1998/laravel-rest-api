<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        if ($students->isEmpty()) {
            $data = [
                "message" => "No students found.",
                "status" => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json([
            "students" => Student::all(),
            "status" => 200
        ], 200);
    }

    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                "message" => "Student not found.",
                "status" => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json([
            "student" => $student,
            "status" => 200
        ], 200);
    }

    public function store(Request $request)
    {
        $validations = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:students",
            "phone" => "required|string|max:15",
            "language" => "required|string|max:100",
        ]);

        $student = Student::create($validations);

        return response()->json([
            "student" => $student,
            "status" => 201,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(["message" => "Student not found."], 404);
        }

        $validations = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:students,email," . $id,
            "phone" => "required|string|max:15",
            "language" => "required|string|max:100",
        ]);

        $student->update($validations);

        return response()->json([
            "student" => $student,
            "status" => 200
        ], 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                "message" => "The student does not exist.",
                "status" => 404
            ];

            return response()->json($data, 404);
        }

        $student->delete();

        return response()->json($student, 200);
    }
}
