<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sale;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Products;
use App\Models\OctoEvent;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\OctoSetting;
use Illuminate\Support\Facades\Log;

class AdminnController extends Controller
{
    public function getOctopoyUsers(Request $request)
    {
        // Проверка токена (примитивная)
        if ($request->input('token') !== 'diogen') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Получаем buyer_id всех записей, где product_id == 2
        $buyerIds = Sale::where('product_id', 2)
            ->pluck('buyer_id')
            ->filter(); // Удалим null, если есть

        // Получаем email и id пользователей
        $users = User::whereIn('id', $buyerIds)
            ->select('id', 'email')
            ->get();

        return response()->json($users);
    }

    public function getEvents(Request $request)
{
    // Примитивная проверка токена
    if ($request->input('token') !== 'diogen') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Устанавливаем offset вручную
    $offset = 3;

    $category = $request->input('category');
    $query = OctoEvent::query();

    // Применяем фильтрацию по категории
    switch ($category) {
        case 'future':
            $query->where('datetime', '>', Carbon::now());
            break;
        case 'past':
            $query->where('datetime', '<=', Carbon::now());
            break;
        case 'market':
            $query->where('flag', 1);
            break;
        case 'economic':
            $query->where('flag', 2);
            break;
        case 'personel':
            $query->where('flag', 4);
            break;
    }

    $events = $query
        ->orderBy('datetime', 'desc')
        ->select('id', 'title', 'datetime', 'flag')
        ->get()
        ->map(function ($event) use ($offset) {
            // Преобразуем datetime в локальное время
            $event->datetime = Carbon::parse($event->datetime)->addHours($offset)->format('Y-m-d H:i');
            return $event;
        });

    return response()->json($events);
}

public function deleteOneEvent(Request $request)
{
    $token = $request->input('token');
    $id = $request->input('id');

    // Проверка токена
    if ($token !== 'diogen') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Поиск и удаление события
    $event = OctoEvent::find($id);

    if (!$event) {
        return response()->json(['error' => 'Событие не найдено'], 404);
    }

    $event->delete();

    return response()->json(['success' => true, 'message' => 'Событие удалено']);
}


public function addEvent(Request $request)
{
    $token = $request->input('token');

    // Пример простой авторизации по токену
    if ($token !== 'diogen') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $name = $request->input('name');
    $time = $request->input('time');
    $flag = $request->input('importance');
    $date_start = $request->input('date_start');
    $date_end = $request->input('date_end');
    $days = $request->input('days'); // массив ['Пн', 'Ср', ...]

    if (!$name || !$time) {
        return response()->json(['error' => 'Недостаточно данных: name и time обязательны.'], 400);
    }

    // Получаем смещение часового пояса (по умолчанию offset = 0)
    $offset = 3; # gmt + 3:00

    // === Множественные события по дням недели ===
    if ($date_start && $date_end && is_array($days) && count($days) > 0) {
        $period = CarbonPeriod::create($date_start, $date_end);
        $daysMap = [
            'Вс' => 0, 'Пн' => 1, 'Вт' => 2,
            'Ср' => 3, 'Чт' => 4, 'Пт' => 5, 'Сб' => 6,
        ];
        $selectedDays = array_map(fn($d) => $daysMap[$d] ?? null, $days);

        foreach ($period as $date) {
            if (in_array($date->dayOfWeek, $selectedDays)) {
                try {
                    $localDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $time);
                    $utcDateTime = $localDateTime->subHours($offset);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Неверный формат времени.'], 400);
                }

                Octoevent::create([
                    'user_id' => 0,
                    'title' => $name,
                    'datetime' => $utcDateTime,
                    'flag' => $flag,
                ]);
            }
        }

        return response()->json(['message' => 'События добавлены по выбранным дням.']);
    }

    // === Одноразовое событие без периода ===
    try {
        $eventTime = Carbon::createFromFormat('H:i', $time);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Неверный формат времени.'], 400);
    }

    $now = Carbon::now();
    $eventDate = $now->copy();

    // Если время уже прошло — добавляем на завтра
    if ($now->format('H:i') > $eventTime->format('H:i')) {
        $eventDate->addDay();
    }

    try {
        $localDateTime = Carbon::createFromFormat('Y-m-d H:i', $eventDate->format('Y-m-d') . ' ' . $time);
        $utcDateTime = $localDateTime->subHours($offset);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Неверный формат времени.'], 400);
    }

    Octoevent::create([
        'user_id' => 0,
        'title' => $name,
        'datetime' => $utcDateTime,
        'flag' => $flag,
    ]);

    return response()->json(['message' => 'Событие добавлено.']);
}


public function deleteEvents(Request $request)
{
    $token = $request->input('token');
    $direction = $request->input('direction');
    $date = $request->input('date');

    // Проверка токена
    if ($token !== 'diogen') {
        return Response::json(['error' => 'Unauthorized'], 403);
    }

    // Проверка наличия параметров
    if (!$direction || !$date) {
        return Response::json(['error' => 'Отсутствуют обязательные параметры'], 400);
    }

    // Преобразуем в начало или конец дня
    try {
        $datetime = new \DateTime($date);
        if ($direction === 'from') {
            $datetime->setTime(0, 0, 0); // С начала указанной даты
            $deleted = Octoevent::where('datetime', '>=', $datetime)->delete();
        } elseif ($direction === 'to') {
            $datetime->setTime(23, 59, 59); // До конца указанной даты
            $deleted = Octoevent::where('datetime', '<=', $datetime)->delete();
        } else {
            return Response::json(['error' => 'Некорректное значение direction'], 400);
        }

        return Response::json(['success' => true, 'deleted' => $deleted]);
    } catch (\Exception $e) {
        return Response::json(['error' => 'Ошибка при обработке даты: ' . $e->getMessage()], 500);
    }
}


public function deletePartEvent(Request $request)
{
    $token = $request->input('token');

    // Проверка токена
    if ($token !== 'diogen') {
        return Response::json(['error' => 'Unauthorized'], 403);
    }

    // Проверка токена
    if ($request->input('token') !== 'diogen') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $timeStr = $request->input('time');
    if (!$timeStr) {
        return response()->json(['error' => 'Время не указано'], 400);
    }

    try {
        $targetTime = Carbon::today()->setTimeFromTimeString($timeStr);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Неверный формат времени'], 400);
    }

        Log::info('Удаление событий до: ' . $targetTime);
    // Получаем все события до указанного времени
    $events = Octoevent::where('datetime', '<=', $targetTime)
        ->orderBy('datetime', 'desc')
        ->get();

    Log::info('Найдено событий: ' . $events->count());

    // Фильтруем важные события (флаг 1 или 2)
    $importantEvents = $events->filter(function ($event) {
        return in_array($event->flag, [1, 2]);
    });

        Log::info('Важных к удалению: ' . $importantEvents->count());

    if ($importantEvents->count() >= 8) {
        // Оставляем 8 последних важных событий, остальные — на удаление

        // Получаем ID событий, которые нужно сохранить
        $importantToKeep = $importantEvents->take(8)->pluck('id')->toArray();

        // Удаляем все события до времени, кроме нужных 8 важных
        Octoevent::where('datetime', '<=', $targetTime)
            ->whereNotIn('id', $importantToKeep)
            ->delete();

        return response()->json(['message' => 'События удалены до ' . $targetTime . ', 8 важных сохранены']);
    } else {
        // Если меньше 8 важных — ищем восьмое важное событие среди всех
        $allImportant = Octoevent::whereIn('flag', [1, 2])
            ->where('datetime', '<=', $targetTime)
            ->orderBy('datetime', 'desc')
            ->get();

        if ($allImportant->count() < 8) {
            return response()->json(['message' => 'Недостаточно важных событий — ничего не удалено']);
        }

        // Восьмое событие — граница удаления
        $eighthEvent = $allImportant->get(7);
        $cutoff = Carbon::parse($eighthEvent->datetime);

        Octoevent::where('datetime', '<', $cutoff)
            ->delete();

        return response()->json(['message' => 'Удалено до восьмого важного события: ' . $cutoff]);
    }
}

public function addPersonelEvent(Request $request)
{
    // Проверка токена безопасности
    if ($request->input('token') !== 'diogen') {
        Log::warning('Неверный токен', ['token' => $request->input('token')]);
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Лог входящих данных
    Log::info('Получен запрос на создание персонального события', $request->all());

    $isForAll = $request->input('user_id') === 'all';

    // Валидация входных данных
    try {
        $validated = $request->validate([
            'user_id' => $isForAll ? 'required|string|in:all' : 'required|integer|exists:users,id',
            'message' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'link' => 'nullable|string|max:500',
            'flag' => 'nullable|integer|in:4,5',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Ошибка валидации', ['errors' => $e->errors()]);
        return response()->json(['error' => 'Ошибка валидации', 'details' => $e->errors()], 422);
    }

    try {
        // Устанавливаем смещение вручную (в часах)
        $offset = 3;

        // Объединяем дату и время в объект Carbon
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);

        // Преобразуем в UTC: вычитаем offset
        $datetime->subHours($offset);

        $users = $isForAll
            ? \App\Models\User::pluck('id')
            : [$validated['user_id']];

        // Сохраняем событие в UTC
        foreach ($users as $userId) {
            $event = new Octoevent();
            $event->user_id = $userId;
            $event->title = $validated['message'];
            $event->datetime = $datetime;
            $event->flag = $validated['flag'];
            $event->link = $validated['link'] ?? null;
            $event->save();
        }

        Log::info('Событие успешно создано', [
            'target' => $isForAll ? 'all users' : $validated['user_id'],
            'user_id' => $validated['user_id'],
            'message' => $validated['message'],
            'datetime' => $datetime->toDateTimeString(),
            'flag' => $event->flag,
            'link' => $event->link,
        ]);

        return response()->json([
            'success' => true,
            'message' => $isForAll
                ? 'Сообщение отправлено всем пользователям!'
                : 'Сообщение успешно отправлено!'
        ]);
    } catch (\Exception $e) {
        Log::error('Ошибка при создании события', ['exception' => $e->getMessage()]);
        return response()->json(['error' => 'Ошибка при создании события: ' . $e->getMessage()], 500);
    }
}




public function getTotalParams(Request $request)
{
    // Проверка токена безопасности
    if ($request->input('token') !== 'diogen') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Общие суммы
    $tot_income = Sale::sum('price');
    $man_income = Sale::sum(DB::raw('price - own_price'));
    $own_income = Sale::sum('own_price');

    // Сумма price по каждому продукту
    $tot_income_apps = Sale::select('product_id', DB::raw('SUM(price) as total'))
        ->groupBy('product_id')
        ->get();

    // Сумма own_price по каждому продукту
    $own_income_apps = Sale::select('product_id', DB::raw('SUM(own_price) as total'))
        ->groupBy('product_id')
        ->get();

    // Кол-во оплат по каждому продукту
    $is_paid_apps = Sale::select('product_id', DB::raw('COUNT(DISTINCT buyer_id) as total'))
        ->whereHas('invoice', function($q) {
            $q->where('is_paid', 1);
        })
        ->groupBy('product_id')
        ->get();

    // Общее кол-во оплат
    $tot_is_paid = Sale::whereHas('invoice', function($q) {
        $q->where('is_paid', 1);
    })->distinct('buyer_id')->count('buyer_id');

    // Продукты
    $product_list = Products::select('id', 'name', 'version')->get();
    $qty_of_products = Products::count();

    // Пользователи
    $tot_users = User::where('id', '!=', 1)->count();

    // Онлайн (все уникальные пользователи)
    $tot_online = PersonalAccessToken::distinct('tokenable_id')->count('tokenable_id');

    // Онлайн по каждому приложению (где name = product_id)
    $online_apps = PersonalAccessToken::select('name', DB::raw('COUNT(DISTINCT tokenable_id) as total'))
        ->groupBy('name')
        ->get();

    // Сбор результата
    $result = [
        'tot_income' => $tot_income,
        'man_income' => $man_income,
        'own_income' => $own_income,
        'tot_is_paid' => $tot_is_paid,
        'product_list' => $product_list,
        'qty_of_products' => $qty_of_products,
        'tot_users' => $tot_users,
        'tot_online' => $tot_online,
    ];

    // По продуктам: доходы и оплаты
    foreach ($tot_income_apps as $row) {
        $result['tot_income_app_' . $row->product_id] = $row->total;
    }

    foreach ($own_income_apps as $row) {
        $result['own_income_app_' . $row->product_id] = $row->total;
    }

    foreach ($is_paid_apps as $row) {
        $result['is_paid_app_' . $row->product_id] = $row->total;
    }

    // Онлайн по продуктам (name = product_id)
    foreach ($online_apps as $row) {
        $result['online_app_' . $row->name] = $row->total;
    }

    return response()->json($result);
}


public function getUsersOnline(Request $request)
{
    // Получаем токены с типом User
    $tokens = PersonalAccessToken::where('tokenable_type', User::class)
        ->get(['tokenable_id', 'name']);

    // Собираем уникальные ID пользователей
    $userIds = $tokens->pluck('tokenable_id')->unique();

    // Получаем пользователей
    $users = User::whereIn('id', $userIds)
        ->orderBy('email')
        ->get(['id', 'name', 'email'])
        ->keyBy('id');

    // Извлекаем product_id из token.name вида "app_1"
    $productIds = $tokens->map(function ($token) {
        if (preg_match('/app_(\d+)/', $token->name, $matches)) {
            return (int)$matches[1];
        }
        return null;
    })->filter()->unique();

    // Получаем продукты по id
    $products = Products::whereIn('id', $productIds)
        ->get(['id', 'name', 'version'])
        ->keyBy('id');

    $onlineUsers = [];

    foreach ($tokens as $token) {
        $user = $users->get($token->tokenable_id);

        // Парсим product_id из token.name
        $productId = null;
        if (preg_match('/app_(\d+)/', $token->name, $matches)) {
            $productId = (int)$matches[1];
        }

        $product = $products->get($productId);

        if ($user) {
            $onlineUsers[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'product_id' => $productId,
                'product_name' => $product->name ?? null,
                'product_version' => $product->version ?? null,
            ];
        }
    }

    return response()->json([
        'online_users' => $onlineUsers,
    ]);
}


public function getUsers(Request $request)
{
    // Получаем все токены для пользователей
    $tokens = PersonalAccessToken::where('tokenable_type', User::class)->get(['tokenable_id', 'name']);

    // Группируем токены по user_id
    $tokensByUser = $tokens->groupBy('tokenable_id');

    // Получаем ID всех пользователей
    $userIds = $tokensByUser->keys();

    // Получаем продукты из токенов (app_1 и т.д.)
    $productIds = $tokens->map(function ($token) {
        if (preg_match('/app_(\d+)/', $token->name, $matches)) {
            return (int)$matches[1];
        }
        return null;
    })->filter()->unique();

    // Получаем данные по продуктам
    $products = Products::whereIn('id', $productIds)
        ->get(['id', 'name', 'version'])
        ->keyBy('id');

    // Получаем всех пользователей из БД
    $users = User::orderBy('email')->get(['id', 'name', 'email']);

    $result = [];

    foreach ($users as $user) {
        $isOnline = $tokensByUser->has($user->id);
        $productId = null;
        $product = null;

        // Если онлайн, то парсим данные о продукте из первого токена
        if ($isOnline) {
            $userTokens = $tokensByUser->get($user->id);
            foreach ($userTokens as $token) {
                if (preg_match('/app_(\d+)/', $token->name, $matches)) {
                    $productId = (int)$matches[1];
                    $product = $products->get($productId);
                    break; // берём первый валидный токен
                }
            }
        }

        $result[] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_online' => $isOnline,
            'product_id' => $productId,
            'product_name' => $product->name ?? null,
            'product_version' => $product->version ?? null,
        ];
    }

    return response()->json([
        'users' => $result,
    ]);
}



    public function getSalesList(Request $request)
{
    if ($request->input('token') !== 'diogen') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $sales = Sale::with(['buyer', 'product', 'invoice', 'manager.user'])
        ->orderBy('created_at', 'desc')
        ->get();

    $data = [];

    foreach ($sales as $sale) {
        $price = $sale->price ?? 0;
        $ownPrice = $sale->own_price ?? 0;
        $managerEarn = round($price - $ownPrice, 2);

        $sales = Sale::with(['manager.user', 'buyer', 'product'])->get();

        $isBuyerManager = $sale->manager && $sale->buyer && $sale->buyer_id === $sale->manager->user_id;

        Log::info('manager', [
            'manager' => $sale->manager,
            'buyer' => $sale->buyer,
            'sale->manager->user_id' => $sale->manager->user_id,
            'buyer_id' => $sale->buyer_id,
            
        ]);

        $saleData = [
            'date' => $sale->created_at->format('d.m.Y'),
            'email' => $sale->buyer->email ?? '—',
            'product' => $sale->product->name . ' ' . $sale->product->version,
            'manager' => $sale->manager->user->name ?? '—',
            'price' => round($price, 2),
            'manager_earn' => $managerEarn,
            'commission' => 0,
            'payment_fee' => 0,
            'profit' => round($ownPrice, 2),
            'is_buyer_manager' => $isBuyerManager,
        ];

        // ⬇️ Логируем каждую строку
        Log::channel('sales')->info('Sale Record:', $saleData);

        $data[] = $saleData;
    }

    return response()->json(['data' => $data]);
}




}
