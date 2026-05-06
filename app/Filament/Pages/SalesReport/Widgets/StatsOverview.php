<?php

namespace App\Filament\Pages\SalesReport\Widgets;

use App\Models\Order;
use App\Models\User;
use App\Models\OfflineTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public $dateFrom = null;
    public $dateTo = null;

    public function mount(): void
    {
        // Default null agar menampilkan semua data
    }

    #[On('filterUpdated')]
    public function updateFilter($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    protected function getStats(): array
    {
        // Hanya hitung status yang sudah dibayar (diproses, dikirim, selesai)
        $validStatuses = ['diproses', 'dikirim', 'selesai'];
        
        $onlineQuery = Order::query()->whereIn('status', $validStatuses, 'and', false);
        $offlineQuery = OfflineTransaction::query()->where('status', '=', 'selesai', 'and');
        
        $onlineOrderCountQuery = Order::query();
        $offlineOrderCountQuery = OfflineTransaction::query();

        if ($this->dateFrom && $this->dateTo) {
            $start = Carbon::parse($this->dateFrom)->startOfDay();
            $end = Carbon::parse($this->dateTo)->endOfDay();
            
            $onlineQuery->whereBetween('created_at', [$start, $end], 'and', false);
            $offlineQuery->whereBetween('created_at', [$start, $end], 'and', false);
            $onlineOrderCountQuery->whereBetween('created_at', [$start, $end], 'and', false);
            $offlineOrderCountQuery->whereBetween('created_at', [$start, $end], 'and', false);
        }

        $totalOnlineRevenue = $onlineQuery->sum('total_price');
        $totalOfflineRevenue = $offlineQuery->sum('total_price');
        $totalRevenue = $totalOnlineRevenue + $totalOfflineRevenue;

        $totalOnlineOrders = $onlineOrderCountQuery->count('*');
        $totalOfflineOrders = $offlineOrderCountQuery->count('*');

        return [
            Stat::make('Total Pengguna', User::query()->count('*'))
                ->description('Seluruh Pengguna Terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Pesanan Online', $totalOnlineOrders)
                ->description('Jumlah Transaksi Online')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),
            Stat::make('Pesanan Offline', $totalOfflineOrders)
                ->description('Jumlah Transaksi Offline')
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('warning'),
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Seluruh Transaksi Berhasil')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
