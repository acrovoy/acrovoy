<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoices;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Payment callback received', $request->all());

        $status = $request->input('status');
        $invoiceId = $request->input('invoice_id');
        $orderId = $request->input('order_id');
        $invoiceInfo = $request->input('invoice_info');

        $invoiceStatus = $invoiceInfo['invoice_status'] ?? null;
        $amountPaid = $invoiceInfo['amount_paid'] ?? 0;

        if (
            $status === 'success' &&
            $invoiceStatus === 'success' &&
            $amountPaid > 0 &&
            $invoiceId
        ) {
            $affected = Invoices::where('invoice', $invoiceId)
                ->update(['is_paid' => 1, 'updated_at' => now()]);

            if ($affected) {
                Log::info("Invoice $invoiceId marked as paid (amount: $amountPaid).");
                return response()->json(['message' => 'Invoice updated'], 200);
            } else {
                Log::warning("Invoice $invoiceId not found or already updated.");
                return response()->json(['message' => 'Invoice not found'], 404);
            }
        }

        Log::warning('Callback rejected: invalid or incomplete payment data.', [
            'status' => $status,
            'invoice_status' => $invoiceStatus,
            'amount_paid' => $amountPaid,
            'invoice_id' => $invoiceId,
            'order_id' => $orderId,
        ]);

        Log::channel('single')->info('Full payment callback data', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'raw' => $request->getContent(),
        ]);

        return response()->json(['message' => 'Invalid payment data'], 400);
    }
}
