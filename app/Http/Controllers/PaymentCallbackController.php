<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoices;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Payment callback received', $request->all());

        $status = $request->input('status');
        $orderId = $request->input('order_id'); // это ваш invoice

        if ($status === 'success' && $orderId) {
            $affected = Invoices::where('invoice', $orderId)
                ->update(['is_paid' => 1, 'updated_at' => now()]);

            if ($affected) {
                Log::info("Invoice $orderId marked as paid.");
                return response()->json(['message' => 'Invoice updated'], 200);
            } else {
                Log::warning("Invoice $orderId not found or already updated.");
                return response()->json(['message' => 'Invoice not found'], 404);
            }
        }

        Log::warning('Invalid callback payload', $request->all());
        return response()->json(['message' => 'Invalid data'], 400);
    }
}
