<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\OrderStatusUpdated;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        if (!$notification) {
            Log::error('Midtrans Notification: Empty payload received.');
            return response(['message' => 'Empty payload'], 400);
        }

        Log::info('Midtrans Notification received:', ['payload' => (array) $notification]);

        // Midtrans signature gross_amount must be precisely formatted with 2 decimal places
        $grossAmount = number_format($notification->gross_amount, 2, '.', '');
        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $grossAmount . config('services.midtrans.server_key'));

        if ($notification->signature_key !== $validSignatureKey) {
            Log::error('Midtrans Signature mismatch:', [
                'order_id' => $notification->order_id,
                'received' => $notification->signature_key,
                'calculated' => $validSignatureKey,
                'raw_gross' => $notification->gross_amount,
                'formatted_gross' => $grossAmount
            ]);
            return response(['message' => 'Invalid signature'], 403);
        }

        $order = Order::query()->where('order_id', '=', $notification->order_id, 'and')->first();

        if (!$order) {
            return response(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;

        if ($transactionStatus == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $order->status = 'pending';
                } else {
                    $order->status = 'diproses';
                    $this->reduceStock($order);
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            $order->status = 'diproses';
            $this->reduceStock($order);
        } elseif ($transactionStatus == 'pending') {
            $order->status = 'pending';
        } elseif ($transactionStatus == 'deny') {
            $order->status = 'dibatalkan';
        } elseif ($transactionStatus == 'expire') {
            $order->status = 'dibatalkan';
        } elseif ($transactionStatus == 'cancel') {
            $order->status = 'dibatalkan';
        }

        // Save order status
        $order->save();

        return response(['message' => 'Notification processed']);
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::query()->where('order_id', '=', $orderId, 'and')->first();

        if ($order) {
            // Jika status masih pending, ubah ke diproses (karena ini callback sukses)
            if ($order->status === 'pending') {
                $order->status = 'diproses';
                $order->save();
                
                // Kurangi stok jika belum dikurangi
                $this->reduceStock($order);
            }
            
            return redirect()->route('dashboard', ['status' => 'success']);
        }

        return redirect()->route('dashboard');
    }

    private function reduceStock($order)
    {
        // Avoid double stock reduction if settlement/capture happens multiple times
        if ($order->stock_reduced) return; 

        foreach ($order->items as $item) {
            if ($item->size) {
                // Reduce stock from ProductSize table
                $productSize = \App\Models\ProductSize::query()
                    ->where('product_id', '=', $item->product_id, 'and')
                    ->where('size', '=', $item->size, 'and')
                    ->first();

                if ($productSize) {
                    $productSize->stock = max(0, $productSize->stock - $item->quantity);
                    $productSize->save();
                }
            } else {
                // Reduce stock from Products table
                $product = \App\Models\Product::query()->find($item->product_id, ['*']);
                if ($product) {
                    $product->stock = max(0, $product->stock - $item->quantity);
                    $product->save();
                }
            }
        }
        
        // Mark order as stock reduced
        $order->stock_reduced = true;
        $order->save();
    }
}
