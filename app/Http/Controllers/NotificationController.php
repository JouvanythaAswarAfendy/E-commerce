<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
        }
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    public function markAsRead($id)
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->markAsRead();

            // Redirect based on the data in notification
            if (isset($notification->data['order_id'])) {
                return redirect()->route('orders.show', ['order_id' => $notification->data['order_id']]);
            }
        }

        return back();
    }
}
