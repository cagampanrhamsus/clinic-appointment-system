<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Find user
        $user = User::where('email', $request->email)->first();

        // 3. Check credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        // 4. OPTIONAL: revoke old tokens (prevents multiple tokens confusion)
        $user->tokens()->delete();

        // 5. Create new Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        // 6. Return safe response (hide password)
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
}