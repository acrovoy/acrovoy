<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\User;

class NoticeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'notice' => 'required|string',
            'color' => 'nullable|string|max:50',
        ]);

        // Удаляем старое уведомление
        Notice::where('user_id', $request->user_id)->delete();

        // Создаём новое
        $notice = Notice::create([
            'user_id' => $request->user_id,
            'notice' => $request->notice,
            'color' => $request->color,
        ]);

        return response()->json(['message' => 'Уведомление добавлено', 'data' => $notice], 201);
    }

    // Получение уведомления по user_id
    public function show($email)
{
    // Ищем пользователя по email
    $user = User::where('email', $email)->first();

    if (!$user) {
        return response()->json(['error' => 'Пользователь не найден'], 404);
    }

    // Ищем уведомление по user_id
    $notice = Notice::where('user_id', $user->id)->first();

    if (!$notice) {
        return response()->json(['error' => 'Уведомление не найдено'], 404);
    }

    return response()->json($notice);
}
}
