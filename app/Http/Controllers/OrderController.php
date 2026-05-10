<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show($order_id)
    {
        // Temukan pesanan berdasarkan order_id string
        $order = Order::query()->where('order_id', '=', $order_id, 'and')->firstOrFail();
        return view('orders.show', compact('order'));
    }


    public function pay($order_id)
    {
        $order = Order::query()->where('order_id', '=', $order_id, 'and')->firstOrFail();
        
        // Pastikan hanya bisa bayar jika status pending
        if (strtolower($order->status) !== 'pending') {
            return redirect()->route('orders.show', $order->order_id)
                ->with('error', 'Pesanan ini sudah dibayar atau tidak bisa dibayar lagi.');
        }

        $snapToken = $order->snap_token;
        return view('checkout.pay', compact('snapToken', 'order'));
    }

    public function cancel($order_id)
    {
        $order = Order::query()->where('order_id', '=', $order_id, 'and')
            ->where('buyer', '=', Auth::id(), 'and')
            ->firstOrFail();

        if (strtolower($order->status) !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini tidak bisa dibatalkan.');
        }

        $order->status = 'dibatalkan';
        $order->save();

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function confirmReceived($order_id)
    {
        $order = Order::query()->where('order_id', '=', $order_id, 'and')
            ->where('buyer', '=', Auth::id(), 'and')
            ->firstOrFail();

        if (strtolower($order->status) !== 'dikirim') {
            return redirect()->back()->with('error', 'Pesanan tidak dalam status dikirim.');
        }

        $order->status = 'selesai';
        $order->save();

        // Notifikasi ke penjual (existing logic in Order model notified buyer, user wants to notify seller too)
        $seller = \App\Models\User::where('role', '=', 'penjual', 'and')->first();
        if ($seller) {
            try {
                $seller->notify(new \App\Notifications\OrderStatusUpdated($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to notify seller: ' . $e->getMessage());
            }
        }

        return redirect()->route('orders.show', $order->order_id)->with('success', 'Pesanan telah selesai. Terima kasih!');
    }
}

