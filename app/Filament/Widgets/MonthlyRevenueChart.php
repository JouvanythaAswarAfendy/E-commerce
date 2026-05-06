<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use App\Models\OfflineTransaction;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class MonthlyRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan Bulanan';
    protected static ?int $sort = 10; // Letakkan paling bawah

    public $dateFrom = null;
    public $dateTo = null;

    public function mount(): void
    {
        // Default null
    }

    #[On('filterUpdated')]
    public function updateFilter($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        // Memaksa update chart pada sisi Livewire/JS
        $this->updateChartData();
    }

    protected function getData(): array
    {
        if ($this->dateFrom && $this->dateTo) {
            $start = Carbon::parse($this->dateFrom)->startOfMonth();
            $end = Carbon::parse($this->dateTo)->endOfMonth();
        } else {
            // Jika tidak ada filter, tampilkan 6 bulan terakhir
            $start = Carbon::now()->subMonths(5)->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }

        $data = [];
        $labels = [];

        $current = clone $start;
        while ($current <= $end) {
            $month = $current->month;
            $year = $current->year;
            $labels[] = $current->format('M Y');

            // Hitung status yang sudah dibayar
            $onlineRevenue = Order::query()
                ->whereMonth('created_at', '=', $month, 'and')
                ->whereYear('created_at', '=', $year, 'and')
                ->whereIn('status', ['diproses', 'dikirim', 'selesai'], 'and', false)
                ->sum('total_price');

            $offlineRevenue = OfflineTransaction::query()
                ->whereMonth('created_at', '=', $month, 'and')
                ->whereYear('created_at', '=', $year, 'and')
                ->where('status', '=', 'selesai', 'and')
                ->sum('total_price');

            $data[] = $onlineRevenue + $offlineRevenue;

            $current->addMonth();
            
            if ($current->year > Carbon::now()->year + 10) break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pendapatan (Rp)',
                    'data' => $data,
                    'backgroundColor' => '#622A2A',
                    'borderColor' => '#622A2A',
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
