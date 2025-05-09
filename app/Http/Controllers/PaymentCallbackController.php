<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
{
    Log::info('Payment callback received', $request->all());

    $transactionId = $request->input('transaction_id');
    $status = $request->input('status');

    if ($transactionId && $status) {
        // Обновление данных в БД и др.
        return response()->json(['message' => 'Callback received'], 200);
    }

    return view('callback_debug', ['data' => $request->all()]);
}
}
