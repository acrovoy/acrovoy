<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;  // Импортируем модель User
use App\Models\Invoices;  // Импортируем модель Invoices

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        
        // Поиск пользователя по email
        $user = User::where('email', $request->email)->first();

        // Создание новой записи инвойса
        $invoice = Invoices::create([
            'user_id' => $user->id,
            'invoice' => $request->invoice,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice created successfully',
            'data' => $invoice,
        ]);
    }

    public function checkInvoiceStatus(Request $request)
    {
        // Проверка, был ли передан email
        $email = $request->email;

        // Поиск пользователя по email
        $user = User::where('email', $email)->first();

        // Если пользователь не найден
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        // Поиск инвойса пользователя
        $invoiceRecord = Invoices::where('user_id', $user->id)->first();

        // Если инвойс не найден
        if (!$invoiceRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found for this user',
            ], 404);
        }

        // Проверяем, оплачен ли счет
        $isPaid = $invoiceRecord->is_paid;

        if ($isPaid == 1) {
        // Возвращаем статус
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice is paid',
                'data' => true // Возвращаем true, если инвойс оплачен
            ], 200);
        }
        else
            return response()->json([
                'status' => 'failed',
                'message' => 'Invoice is not paid',
                'data' => False // Возвращаем false, если инвойс НЕ оплачен
            ], 200);

    }
}
