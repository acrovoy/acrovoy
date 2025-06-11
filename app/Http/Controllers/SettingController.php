<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use App\Models\User;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getSettingsByEmail($email)
    {
        // Находим пользователя по email
        $user = User::where('email', $email)->first();

        // Если пользователь не найден, возвращаем ошибку
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Получаем настройки пользователя по user_id
        $settings = Setting::where('user_id', $user->id)->first();

        // Если настройки не найдены, возвращаем ошибку
        if (!$settings) {
            return response()->json(['error' => 'Settings not found for this user'], 404);
        }

        // Возвращаем настройки в формате JSON
        return response()->json($settings);
    }


    public function updateSettingsByEmail(Request $request)
    {
      
        // Правила валидации
        $rules = [
            'email' => 'required|email', // Убедитесь, что email присутствует и валиден
            'display_length' => 'required|in:S,L,M', // Разрешены только S, L, M
            'font_size' => 'required|in:L,M', // Разрешены только L, M
            'lv1_volume' => 'required|numeric|min:0|max:50000000',
            'lv2_volume' => 'required|numeric|min:0|max:50000000',
            'lv3_volume' => 'required|numeric|min:0|max:50000000',
            'scan_distance' => 'required|numeric|min:0|max:20',
            'additional_spot' => 'nullable|string|max:200',
            'additional_futures' => 'nullable|string|max:200',
            'blacklisted_spot' => 'nullable|string|max:200',
            'blacklisted_futures' => 'nullable|string|max:200',
        ];

        // Сообщения об ошибках валидации
        $messages = [
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'display_length.required' => 'Display length is required',
            'display_length.in' => 'Display length must be one of: S, L, M',
            'font_size.required' => 'Font size is required',
            'font_size.in' => 'Font size must be one of: L, M',
            'lv1_volume.required' => 'Level 1 volume is required',
            'lv1_volume.numeric' => 'Level 1 volume must be a number',
            'lv1_volume.min' => 'Level 1 volume must be at least 0',
            'lv2_volume.required' => 'Level 2 volume is required',
            'lv2_volume.numeric' => 'Level 2 volume must be a number',
            'lv2_volume.min' => 'Level 2 volume  must be at least 0',
            'lv3_volume.required' => 'Level 3 volume is required',
            'lv3_volume.numeric' => 'Level 3 volume must be a number',
            'lv3_volume.min' => 'Level 3 volume must be at least 0',
            'lv1_volume.max' => 'Level 1 volume  must be greater than 100 least 0',
            // Добавьте другие сообщения по аналогии
        ];

        // Валидируем данные
        $validator = Validator::make($request->all(), $rules, $messages);

        // Если валидация не прошла
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422); // Статус 422 для ошибки валидации
        }

        // Извлекаем данные из запроса
        $settings_data = $request->all();

        // Получаем email из данных
        $email = $settings_data['email'];

        // Находим пользователя по email
        $user = User::where('email', $email)->first();

        // Если пользователь не найден, возвращаем ошибку
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Находим настройки пользователя
        $settings = Setting::where('user_id', $user->id)->first();

        // Если настройки не найдены, возвращаем ошибку
        if (!$settings) {
            return response()->json(['error' => 'Settings not found for this user'], 404);
        }


        

        // Обновляем настройки пользователя
        $settings->update([
            'display_length' => $settings_data['display_length'],
            'font_size' => $settings_data['font_size'],
            'lv1_volume' => $settings_data['lv1_volume'],
            'lv2_volume' => $settings_data['lv2_volume'],
            'lv3_volume' => $settings_data['lv3_volume'],
            'scan_distance' => $settings_data['scan_distance'],
            'additional_spot' => $settings_data['additional_spot'],
            'additional_futures' => $settings_data['additional_futures'],
            'blacklisted_spot' => $settings_data['blacklisted_spot'],
            'blacklisted_futures' => $settings_data['blacklisted_futures'],
            'market' => $settings['market'],
            'exchange' => $settings_data['exchange'],
            'proxy' => $settings_data['proxy'] ?? null,
        ]);

        // Возвращаем успешный ответ
        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => $settings
        ], 200);
    }

    


    public function updateMarketByEmail(Request $request)
    {
        
        // Извлекаем данные из запроса
        $market_data = $request->all();

        // Получаем email из данных
        $email = $market_data['email'];

        // Находим пользователя по email
        $user = User::where('email', $email)->first();

        // Если пользователь не найден, возвращаем ошибку
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Находим настройки пользователя
        $settings = Setting::where('user_id', $user->id)->first();

        // Если настройки не найдены, возвращаем ошибку
        if (!$settings) {
            return response()->json(['error' => 'Settings not found for this user'], 404);
        }

        // Обновляем настройки пользователя
        $settings->update([
            
            'market' => $market_data['market'],
        ]);

        // Возвращаем успешный ответ
        return response()->json([
            'message' => 'Market updated successfully',
            'settings' => $settings
        ], 200);
    }


}
