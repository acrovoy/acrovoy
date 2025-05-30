<?php

namespace App\Http\Controllers\Api;
use App\Models\OctoEvent;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\OctoSetting;
use Illuminate\Http\Request;
use App\Models\User;

class OctoEventController extends Controller
{
    public function getEvents(Request $request)
    {
    $email = $request->input('email');

    $user = User::where('email', $email)->first();

    if (!$user) {
        return response()->json(['message' => 'Пользователь не найден.'], 404);
    }

    $offset = OctoSetting::join('gmt', 'octo_settings.gmt_id', '=', 'gmt.id')
        ->where('octo_settings.user_id', $user->id)
        ->value('gmt.offset') ?? 0;

    $events = OctoEvent::where(function ($query) use ($user) {
    $query->where('user_id', $user->id)
                ->orWhere('user_id', 0);
        })
        ->orderBy('datetime')
        ->get();

    $formatted = $events->map(function ($event) use ($offset) {
        $userTime = Carbon::parse($event->datetime)->addHours($offset);
        return [
            'date' => $userTime->format('Y-m-d'),
            'time' => $userTime->format('H:i'),
            'title' => $event->title,
            'flag' => $event->flag,
        ];
    });

    return response()->json($formatted);
}


    public function storeUserEvent(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email|exists:users,email',
        'title' => 'required|string|max:200',
        'datetime' => 'required|date', // datetime передаётся в ЛОКАЛЬНОМ времени пользователя
    ]);

    $user = User::where('email', $request->input('email'))->first();

    if (!$user) {
        return response()->json(['message' => 'Пользователь не найден.'], 404);
    }

    // Получаем смещение часового пояса пользователя
    $offset = OctoSetting::join('gmt', 'octo_settings.gmt_id', '=', 'gmt.id')
        ->where('octo_settings.user_id', $user->id)
        ->value('gmt.offset') ?? 0;

    // Переводим локальное время в UTC
    $localDateTime = Carbon::parse($validated['datetime']);
    $utcDateTime = $localDateTime->subHours($offset);

    // Сохраняем в UTC
    $event = OctoEvent::create([
        'user_id' => $user->id,
        'title' => $validated['title'],
        'datetime' => $utcDateTime,
        'flag' => 3,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Event created successfully.',
        'event' => $event,
    ]);
}


}
