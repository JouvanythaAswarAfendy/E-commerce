<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Admin (penjual) diarahkan ke Filament admin panel
        if ($user->role === 'penjual') {
            return redirect('/admin');
        }

        // Ambil semua pesanan milik user, terbaru dulu, dengan pagination (kecuali yang dibatalkan)
        $orders = $user->orders()
            ->where('status', '!=', 'dibatalkan')
            ->latest()
            ->paginate(10);

        // Hitung stats (hanya yang tidak dibatalkan)
        $totalOrders     = $user->orders()->where('status', '!=', 'dibatalkan')->count();
        $processingOrders = $user->orders()
            ->whereIn('status', ['pending', 'diproses', 'dikirim'])
            ->count();
        $totalSpent      = $user->orders()
            ->whereIn('status', ['diproses', 'dikirim', 'selesai'])
            ->sum('total_price');

        return view('dashboard', compact(
            'user',
            'orders',
            'totalOrders',
            'processingOrders',
            'totalSpent'
        ));
    }
}