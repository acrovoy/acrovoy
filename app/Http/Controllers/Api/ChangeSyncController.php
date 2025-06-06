<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Constant;

class ChangeSyncController extends Controller
{
    public function getChanges(Request $request)
    {

        if ($request->input('token') !== 'diogen') {
        return response()->json(['error' => 'Unauthorized'], 403);
         }

        $request->validate([
            'last_sync' => 'required|date',
        ]);

        $since = $request->input('last_sync');

        // Список таблиц для проверки (можно вынести в конфиг)
        $tables = [
            'users' => ['id', 'name', 'email', 'updated_at'],
            'sales' => ['id', 'product_id', 'buyer_id', 'manager_id', 'invoice_id', 'price', 'updated_at'],
            'octo_events' => ['id', 'user_id', 'title', 'datetime', 'flag', 'link', 'updated_at'],
            'keys' => ['id', 'name', 'updated_at'],
            'downloads' => ['id', 'product_id', 'ip_address', 'updated_at'],
            'cs_invoices' => ['id', 'user_id', 'product_id', 'invoice', 'payment_link', 'is_paid', 'updated_at'],
            'ads' => ['id', 'product_id', 'date_from', 'date_to', 'advertiser_name', 'advertiser_contact', 'updated_at'],
        ];

        $result = [];

        foreach ($tables as $table => $fields) {
            $result[$table] = DB::table($table)
                ->select($fields)
                ->where('updated_at', '>', $since)
                ->get();
        }

        return response()->json($result);
    }

    public function updateConstant(Request $request)
{
    $token = $request->input('token');
    $updates = $request->input('constants'); // ключ должен соответствовать тому, что вы отправляете с клиента

    if ($token !== 'diogen') {
        return response()->json(['error' => 'Неверный токен'], 403);
    }

    if (!is_array($updates)) {
        return response()->json(['error' => 'Неверный формат constants'], 400);
    }

    foreach ($updates as $key => $value) {
        Constant::updateOrInsert(
            ['key' => $key],
            ['value' => $value]
        );
    }

    return response()->json(['success' => true]);
}


    public function getConstant(Request $request)
    {

        $token = request()->query('token');

        if ($token !== 'diogen') {
            return response()->json(['error' => 'Неверный токен'], 403);
        }

        try {
            $constants = Constant::pluck('value', 'key');
            
            return response()->json([
                'success' => true,
                'data' => $constants
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при получении констант: ' . $e->getMessage()
            ], 500);
        }
    }

    
}
