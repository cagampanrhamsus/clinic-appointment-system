<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function generateToken(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (!method_exists($user, 'createToken')) {
            return response()->json(['message' => 'Sanctum not enabled on User model'], 500);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    // READ ALL PATIENTS
    public function patients()
    {
        return response()->json(
            User::where('role', 'patient')->get()
        );
    }

    // READ ONE PATIENT
    public function showPatient($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        return response()->json($patient);
    }

    // CREATE PATIENT
    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $patient = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'patient',
        ]);

        return response()->json([
            'message' => 'Patient created successfully',
            'data' => $patient
        ], 201);
    }

    // UPDATE PATIENT (PATCH FIXED)
    public function updatePatient(Request $request, $id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $id,
        ]);

        if (empty($validated)) {
            return response()->json([
                'message' => 'No data provided to update'
            ], 422);
        }

        $patient->update($validated);

        return response()->json([
            'message' => 'Patient updated successfully',
            'data' => $patient->fresh()
        ]);
    }

    // DELETE PATIENT
    public function deletePatient($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        $patient->delete();

        return response()->json([
            'message' => 'Patient deleted successfully'
        ]);
    }
}