<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Order;
use App\Models\OfflineTransaction;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class SalesReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $title = 'Laporan Penjualan';

    protected static string $view = 'filament.pages.sales-report';

    public $dateFrom = null;
    public $dateTo = null;

    public function mount()
    {
        // Tetapkan null agar default menampilkan semua data
        $this->dateFrom = null;
        $this->dateTo = null;
    }

    public function applyFilter()
    {
        $this->dispatch('filterUpdated', dateFrom: $this->dateFrom, dateTo: $this->dateTo);
    }

    protected function getViewData(): array
    {
        $onlineQuery = Order::query()->with('items.product');
        $offlineQuery = OfflineTransaction::query()->with('items.product');

        if ($this->dateFrom && $this->dateTo) {
            $start = Carbon::parse($this->dateFrom)->startOfDay();
            $end = Carbon::parse($this->dateTo)->endOfDay();
            
            $onlineQuery->whereBetween('created_at', [$start, $end], 'and', false);
            $offlineQuery->whereBetween('created_at', [$start, $end], 'and', false);
        }

        return [
            'onlineOrders' => $onlineQuery->latest()->get(),
            'offlineTransactions' => $offlineQuery->latest()->get(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Ekspor ke Excel (.xlsx)')
                ->color('success')
                ->icon('heroicon-m-arrow-down-tray')
                ->action(function () {
                    $dateRangeStr = ($this->dateFrom && $this->dateTo) 
                        ? $this->dateFrom . '_to_' . $this->dateTo 
                        : 'Semua_Waktu';
                    $filename = 'Sales_Report_' . $dateRangeStr . '.xlsx';
                    
                    return Excel::download(new SalesReportExport($this->dateFrom, $this->dateTo), $filename);
                }),
        ];
    }
}
