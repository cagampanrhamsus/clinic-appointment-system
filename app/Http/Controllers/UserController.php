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
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        if (!method_exists($user, 'createToken')) {
            return response()->json([
                'message' => 'Sanctum not enabled on User model'
            ], 500);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
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
        $patient = User::where('role', 'patient')
            ->findOrFail($id);

        return response()->json($patient);
    }

    // CREATE PATIENT
    public function storePatient(Request $request)
    {
        $patient = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'patient',
        ]);

        return response()->json($patient, 201);
    }

    // UPDATE PATIENT
    public function updatePatient(Request $request, $id)
    {
        $patient = User::findOrFail($id);

        $patient->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json($patient);
    }

    // DELETE PATIENT
    public function deletePatient($id)
    {
        $patient = User::findOrFail($id);

        $patient->delete();

        return response()->json([
            'message' => 'Patient deleted successfully'
        ]);
    }
}