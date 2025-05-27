<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OctoSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OctoSettingController extends Controller
{
    public function getByEmail(Request $request)
{
    Log::info('метод вызван');
    Log::info('Получен запрос', ['input' => $request->all()]);
    $email = $request->input('email');
    Log::info('Получен запрос на получение настроек по email', ['email' => $email]);
    $user = User::where('email', $email)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $settings = OctoSetting::where('user_id', $user->id)->get();

    return response()->json($settings);
}



    public function updateGmt(Request $request)
{
    $request->validate([
        'gmt_id' => 'required|exists:gmt,id',
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->input('email'))->first();

    if (!$user) {
        return response()->json(['message' => 'Пользователь не найден.'], 404);
    }

    // Обновляем существующую запись
    $updated = OctoSetting::where('user_id', $user->id)->update([
        'gmt_id' => $request->input('gmt_id'),
    ]);

    if ($updated) {
        return response()->json([
            'message' => 'GMT успешно обновлён.',
        ]);
    } else {
        return response()->json([
            'message' => 'Настройки не найдены для пользователя, обновление не выполнено.',
        ], 404);
    }
}

public function updateTheme(Request $request)
{
    $request->validate([
        
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->input('email'))->first();

    if (!$user) {
        return response()->json(['message' => 'Пользователь не найден.'], 404);
    }

    // Обновляем существующую запись
    $updated = OctoSetting::where('user_id', $user->id)->update([
        'theme' => $request->input('theme'),
    ]);

    if ($updated) {
        return response()->json([
            'message' => 'Theme успешно обновлён.',
        ]);
    } else {
        return response()->json([
            'message' => 'Настройки не найдены для пользователя, обновление не выполнено.',
        ], 404);
    }
}


public function updateSignal(Request $request)
{
    $request->validate([
        
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->input('email'))->first();

    if (!$user) {
        return response()->json(['message' => 'Пользователь не найден.'], 404);
    }

    // Обновляем существующую запись
    $updated = OctoSetting::where('user_id', $user->id)->update([
        'signal' => $request->input('signal'),
    ]);

    if ($updated) {
        return response()->json([
            'message' => 'Signal успешно обновлён.',
        ]);
    } else {
        return response()->json([
            'message' => 'Настройки не найдены для пользователя, обновление не выполнено.',
        ], 404);
    }
}




    public function getGmtByEmail(Request $request)
        {
            Log::info('метод вызван');
            Log::info('Получен запрос', ['input' => $request->all()]);

            $email = $request->input('email');
            Log::info('Получен запрос на получение настроек по email', ['email' => $email]);

            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Подгружаем связанную модель gmt
            $settings = OctoSetting::with('gmt')->where('user_id', $user->id)->get();

            return response()->json($settings);
        }

}
