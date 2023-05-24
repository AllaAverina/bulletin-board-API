<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * Handle a register attempt.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255',],
            'nickname' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users',],
            'email' => ['required', 'string', 'email:filter', 'max:255', 'unique:users',],
            'password' => ['required', 'string', 'min:6', 'confirmed',],
        ]);

        $user = User::create([
            'name' => $request->name,
            'nickname' => Str::slug($request->nickname),
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response(['access_token' => $token, 'token_type' => 'Bearer',], Response::HTTP_CREATED);
    }

    /**
     * Handle a login attempt.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email:filter', 'max:255',],
            'password' => ['required', 'string', 'min:6',],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response(['error' => 'The provided credentials are incorrect.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response(['access_token' => $token, 'token_type' => 'Bearer',],);
    }

    /**
     * Handle a logout.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->noContent();
    }
}
