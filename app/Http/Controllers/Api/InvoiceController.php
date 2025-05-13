<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;  
use App\Models\Invoices;  
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    $sale = Sale::where('id', $request->sale)->first();
    


    // Создание новой записи инвойса
    $invoice = Invoices::create([
        'user_id' => $user->id,
        'invoice' => $request->invoice,
        'payment_link' => $request->payment_link,
        'product_id' => $sale->product_id,
        
    ]);

    
    if ($request->invoice == 'HEAD') {
        
        $invoice->is_paid = 1;
        $invoice->save();
    }

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
            $email = $request->email;
            $product_id = $request->product_id;

            // Поиск пользователя по email
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ], 404);
            }

            // Поиск инвойса по user_id и product_id
            $invoiceRecord = Invoices::where('user_id', $user->id)
                ->where('product_id', $product_id)
                ->first();

            if (!$invoiceRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invoice not found for this user',
                ], 404);
            }

            // Получение цены
            $sale = Sale::where('invoice_id', $invoiceRecord->id)->first();
            $price = $sale->price ?? 0;

            // Проверка статуса инвойса
            
                $apiKey = config('services.cryptocloud.api_key');
                $shop_id = config('services.cryptocloud.shop_id');
                $currency = 'USD';

                Log::info('http data', ['apiKey' => $apiKey,'shop_id'=> $shop_id,'currency'=> $currency]);

                $response = Http::withToken($apiKey)
                    ->post('https://api.cryptocloud.plus/v2/invoice/merchant/info', [
                        'uuids' => [$invoiceRecord->invoice],
                    ]);

                if (!$response->ok()) {
                    Log::error("Failed to verify invoice $invoiceRecord->invoice via CryptoCloud", [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return response()->json(['message' => 'Payment verification failed'], 500);
                }

                $data = $response->json();
                Log::info('CryptoCloud invoice status response', ['response_data' => $data]);

                if (
                    isset($data['result'][0]['status']) && $data['result'][0]['status'] === 'canceled'
                ) {
                    // Создаём новый инвойс


                      Log::info('REQUEST SENT:', [
                        'headers' => [
                            'Authorization' => 'Token ' . $apiKey,
                            'Content-Type' => 'application/json',
                        ],
                        'payload' => [
                            'amount' => $price,
                            'shop_id' => $shop_id,
                            'currency' => $currency,
                        ],
                        'response_status' => $response->status(),
                        'response_body' => $response->body(),
                    ]);

                    Log::info('Точка достигнута: создание инвойса начато.');

                    $newinvoiceresponse = Http::withHeaders([
                        'Authorization' => 'Token ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ])->post('https://api.cryptocloud.plus/v2/invoice/create', [
                        'amount' => $price,
                        'shop_id' => $shop_id,
                        'currency' => $currency,
                    ]);


                   


                    if (!$newinvoiceresponse->ok()) {
                        Log::error("Failed to create new invoice for user $user->id", [
                            'status' => $newinvoiceresponse->status(),
                            'body' => $newinvoiceresponse->body()
                        ]);
                        return response()->json(['message' => 'XFailed to create new invoice'], 500);
                    }

                    $newInvoiceData = $newinvoiceresponse->json();

                    $new_invoice_number = $newInvoiceData['result']['uuid'] ?? null;
                    $new_invoice_link = $newInvoiceData['result']['link'] ?? null;

                    if (!$new_invoice_number || !$new_invoice_link) {
                        return response()->json(['message' => 'Invalid response from CryptoCloud'], 500);
                    }

                    // Обновляем запись инвойса
                    $invoiceRecord->invoice = $new_invoice_number;
                    $invoiceRecord->payment_link = $new_invoice_link;
                    $invoiceRecord->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'New invoice created due to cancellation',
                        'data' => [
                            'invoice_number' => $new_invoice_number,
                            'payment_link' => $new_invoice_link,
                        ],
                    ], 200);
                }
                          

            // Возвращаем текущий инвойс
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice found successfully',
                'data' => [
                    'invoice_number' => $invoiceRecord->invoice,
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
        // Получаем email и product из запроса
        $email = $request->email;
        $product = $request->product;

        // Проверка на валидность входных данных
        if (empty($email) || empty($product)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email and product are required.',
            ], 400);
        }

        // Поиск пользователя по email
        $user = User::where('email', $email)->first();

        // Если пользователь не найден
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        // Поиск инвойса по user_id и product_id
        $invoiceRecord = Invoices::where('user_id', $user->id)
            ->where('product_id', $product)
            ->first();

        // Если инвойс не найден
        if (!$invoiceRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found for this user',
            ], 404);
        }

        // Успешный ответ с номером инвойса и ссылкой на оплату
        return response()->json([
            'status' => 'success',
            'message' => 'Invoice found successfully',
            'data' => [
                'invoice_number' => $invoiceRecord->invoice, // Убедитесь, что поле называется именно "invoice"
                'payment_link' => $invoiceRecord->payment_link,
            ],
        ], 200);
    }


}
