<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
