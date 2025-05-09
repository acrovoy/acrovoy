<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Логируем всё, что пришло (для отладки)
        Log::info('Payment callback received', $request->all());

        // Пример проверки и обработки
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status');

        if ($transactionId && $status) {
            // Здесь обновите статус платежа в БД, отправьте уведомление и т.д.
            // Например:
            // $payment = Payment::where('transaction_id', $transactionId)->first();
            // if ($payment) {
            //     $payment->status = $status;
            //     $payment->save();
            // }

            return response()->json(['message' => 'Callback received'], 200);
        }

        
        return view('callback_debug', ['data' => $request->all()]);
        // return response()->json(['error' => 'Invalid data'], 400);
    }
}
