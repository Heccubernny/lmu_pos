<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoniepointTransaction;
use App\Models\ActivityLog;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MoniepointController extends Controller
{
    /**
     * Handle webhook from Moniepoint.
     * Bypasses CSRF and handles incoming transaction notifications.
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Moniepoint Webhook Received:', $request->all());

        // Support both direct payloads and standard event-wrapped payloads
        $event = $request->input('event', 'transaction.successful');
        $data = $request->input('data', $request->all());

        $reference = $data['transactionReference'] ?? $data['reference'] ?? null;
        if (!$reference) {
            return response()->json(['status' => 'error', 'message' => 'Missing transaction reference'], 400);
        }

        $amount = $data['amount'] ?? 0;
        $paymentMethod = $data['channel'] ?? $data['payment_method'] ?? 'Card'; // CARD, TRANSFER, etc.
        $terminalId = $data['terminalId'] ?? $data['terminal_id'] ?? null;
        $status = strtolower($data['status'] ?? 'successful'); // SUCCESS, APPROVED, etc.
        if (in_array($status, ['success', 'approved', 'successful'])) {
            $status = 'successful';
        } else {
            $status = 'failed';
        }

        $customerName = $data['customerName'] ?? $data['customer_name'] ?? 'Walk-in Customer';
        $bankName = $data['bankName'] ?? $data['bank_name'] ?? null;
        $accountNumber = $data['accountNumber'] ?? $data['account_number'] ?? null;
        $cardBrand = $data['cardBrand'] ?? $data['card_brand'] ?? null;
        $cardLast4 = $data['cardLast4'] ?? $data['card_last_4'] ?? null;
        $storeId = $data['storeId'] ?? $data['store_id'] ?? null;
        $cashierId = $data['cashierId'] ?? $data['cashier_id'] ?? null;

        // Find or create the transaction log
        $transaction = MoniepointTransaction::updateOrCreate(
            ['reference' => $reference],
            [
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'terminal_id' => $terminalId,
                'status' => $status,
                'customer_name' => $customerName,
                'bank_name' => $bankName,
                'account_number' => $accountNumber,
                'card_brand' => $cardBrand,
                'card_last_4' => $cardLast4,
                'store_id' => $storeId,
                'cashier_id' => $cashierId,
                'payload' => $request->all(),
            ]
        );

        // Inform Admin & Log Activity
        if ($status === 'successful') {
            $storeName = 'Unknown Store';
            if ($storeId) {
                $store = Store::find($storeId);
                $storeName = $store ? $store->name : 'Store #' . $storeId;
            }

            $methodDesc = $paymentMethod === 'Transfer' ? "Transfer to Account ({$bankName} - {$accountNumber})" : "Card Swipe ({$cardBrand} *{$cardLast4})";

            ActivityLog::log(
                'moniepoint_payment',
                "Moniepoint Payment Received: ₦" . number_format($amount, 2) . " at {$storeName} via {$methodDesc}. Ref: {$reference}",
                $cashierId
            );
        }

        return response()->json(['status' => 'success', 'transaction_id' => $transaction->id]);
    }

    /**
     * Poll transaction status by reference.
     */
    public function checkTransaction($reference)
    {
        $transaction = MoniepointTransaction::where('reference', $reference)->first();

        if (!$transaction) {
            return response()->json([
                'found' => false,
                'status' => 'not_found'
            ]);
        }

        return response()->json([
            'found' => true,
            'status' => $transaction->status,
            'amount' => $transaction->amount,
            'payment_method' => $transaction->payment_method,
            'customer_name' => $transaction->customer_name,
            'reference' => $transaction->reference
        ]);
    }

    /**
     * Poll recent successful unmatched transactions for a store and amount.
     * Helpful for real-time notification of incoming bank transfers or card swiping.
     */
    public function pollActivePayment(Request $request)
    {
        $storeId = $request->input('store_id');
        $amount = $request->input('amount');

        $query = MoniepointTransaction::where('status', 'successful')
            ->whereNull('sale_id')
            ->where('created_at', '>=', Carbon::now()->subMinutes(5));

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        if ($amount) {
            // Find payments with close amount (within ₦1.00 tolerance)
            $query->whereBetween('amount', [$amount - 1, $amount + 1]);
        }

        $payments = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'payments' => $payments
        ]);
    }

    /**
     * Display Moniepoint transactions in the admin dashboard for logging and auditing.
     */
    public function adminIndex(Request $request)
    {
        $query = MoniepointTransaction::with(['store', 'cashier']);

        // Filter by store
        if ($storeId = $request->input('store_id')) {
            $query->where('store_id', $storeId);
        }

        // Filter by method
        if ($method = $request->input('payment_method')) {
            $query->where('payment_method', $method);
        }

        // Filter by search (reference, terminal, customer)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('terminal_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(15);
        $stores = Store::all();

        return view('admin.moniepoint.index', compact('transactions', 'stores'));
    }
}
