<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;  // Импортируем модель User
use App\Models\Invoices;  // Импортируем модель Invoices

class InvoiceController extends Controller
{
    public function store(Request $request)
{
    // Поиск пользователя по email
    $user = User::where('email', $request->email)->first();

    // Проверка, есть ли инвойс для данного пользователя
    $existingInvoice = Invoices::where('user_id', $user->id)->first();

    if ($existingInvoice) {
        // Если инвойс существует, удаляем старый инвойс
        $existingInvoice->delete();
    }




    // Создание новой записи инвойса
    $invoice = Invoices::create([
        'user_id' => $user->id,
        'invoice' => $request->invoice,
        'payment_link' => $request->payment_link,
        
    ]);

    $sale = Sale::where('id', $request->sale)->first();
    if ($sale) {
        // Обновление поля invoice_id
        $sale->invoice_id = $invoice->id;
        $sale->save();
    }


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
            'status' => 'success',
            'message' => 'Invoice is not paid',
            'data' => false
        ], 200);

    }

     public function getInvoiceByEmail(Request $request)
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

        // Получаем user_id
        $userId = $user->id;

        // Поиск инвойса по user_id
        $invoiceRecord = Invoices::where('user_id', $userId)->first();

        // Если инвойс не найден
        if (!$invoiceRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found for this user',
            ], 404);
        }

        // Возвращаем номер инвойса
        return response()->json([
            'status' => 'success',
            'message' => 'Invoice found successfully',
            'data' => [
                'invoice_number' => $invoiceRecord->invoice, // или используйте другое поле, которое содержит номер инвойса
                'payment_link' => $invoiceRecord->payment_link, 
            ],
        ], 200);
    }

    public function markAsPaid(Request $request)
{
    // Поиск инвойса по ID
    $invoice = Invoices::where('invoice', $request->invoice)->first();

    // Проверка, существует ли инвойс
    if (!$invoice) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invoice not found',
        ], 404);
    }

    // Обновление поля is_paid на 1
    $invoice->is_paid = 1;
    $invoice->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Invoice marked as paid successfully',
        'data' => $invoice,
    ]);
}


public function checkPm(Request $request)
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
            {
                return response()->json([
                'status' => 'success',
                'message' => 'Invoice is not paid',
                'data' => false
            ], 200);}

    }



    public function getInvoiceByEmailAndProduct(Request $request)
    {
        // Проверка, был ли передан email
        $email = $request->email;
        $product = $request->product;

        // Поиск пользователя по email
        $user = User::where('email', $email)->first();

        // Если пользователь не найден
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        // Получаем user_id
        $userId = $user->id;

        // Поиск инвойса по user_id
        $invoiceRecord = Invoices::where('user_id', $userId)
            ->where('product_id', $product)
            ->first();

        // Если инвойс не найден
        if (!$invoiceRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found for this user',
            ], 404);
        }

        // Возвращаем номер инвойса
        return response()->json([
            'status' => 'success',
            'message' => 'Invoice found successfully',
            'data' => [
                'invoice_number' => $invoiceRecord->invoice, // или используйте другое поле, которое содержит номер инвойса
                'payment_link' => $invoiceRecord->payment_link, 
            ],
        ], 200);
    }


}
