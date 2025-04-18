<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Создание пользователя
        $user = User::create([
            'name' => $request['email'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
    
        // Создание дефолтных настроек для пользователя
        Setting::create([
            'user_id' => $user->id,
            'display_length' => 'M',
            'font_size' => 'L',
            'lv1_volume' => 500000,
            'lv2_volume' => 1000000,
            'lv3_volume' => 3000000,
            'scan_distance' => 2.00,
            'additional_spot' => 'BTC, ETH',
            'additional_futures' => 'BTC, ETH',
            'blacklisted_spot' => 'TST, MOVE',
            'blacklisted_futures' => 'TST, MOVE',
            'market' => '1',
        ]);
    
        // Создание токена
        $token = $user->createToken('auth_token')->plainTextToken;
    
        // Возвращение токена
        return response()->json(['token' => $token], 200);
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
