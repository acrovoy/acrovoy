<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'], 
            'password' => Hash::make($data['password']),
        ]);


          // Создание токена
    $token = $user->createToken('auth_token')->plainTextToken;
    // Возвращение токена
    return response()->json(['token' => $token], 201);


    }


    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMesages([
                'email' => ['The provider credentials are incorrect.'],
            ]);

        }


         // Удаляем все старые токены перед созданием нового
         $user->tokens->each(function ($token) {
            $token->delete();
        });

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }


    public function logout(Request $request) 
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out succesfully.']);
    }


}
