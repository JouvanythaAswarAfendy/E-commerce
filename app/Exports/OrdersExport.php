<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Carbon;

class OrdersExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithMapping, ShouldAutoSize, WithEvents
{
    protected $dateFrom;
    protected $dateTo;
    protected $rowCount = 0;

    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function title(): string
    {
        return 'Pesanan Online';
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Nama Pelanggan',
            'Produk',
            'Jumlah',
            'Status',
            'Total (Rp)',
            'Tanggal',
        ];
    }

    public function map($order): array
    {
        $this->rowCount++;
        
        $statusLabel = match($order->status) {
            'pending' => 'Belum Bayar',
            'diproses' => 'Diproses',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($order->status),
        };

        $isRevenue = in_array($order->status, ['diproses', 'dikirim', 'selesai']);

        // Gabungkan nama produk tanpa x1, x2
        $productNames = $order->items->map(function($item) {
            return ($item->product->name ?? 'Produk');
        })->implode(', ');

        return [
            $order->order_id,
            $order->user?->name ?? '-',
            $productNames,
            $order->items->sum('quantity'),
            $statusLabel,
            $isRevenue ? $order->total_price : '—',
            $order->created_at->format('d-m-Y H:i'),
        ];
    }

    public function collection()
    {
        $query = Order::query()->with(['user', 'items.product']);

        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->dateFrom)->startOfDay(),
                Carbon::parse($this->dateTo)->endOfDay()
            ], 'and', false);
        }

        return $query->get();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1A7A4A']
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $this->rowCount + 1;
                $summaryRow = $lastRow + 2;
                
                $data = $this->collection();
                foreach ($data as $index => $order) {
                    $currentRow = $index + 2;
                    if (!in_array($order->status, ['diproses', 'dikirim', 'selesai'])) {
                        // Kolom F adalah Total (Rp)
                        $sheet->getStyle("F{$currentRow}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FCEBEB');
                    }
                }

                $totalRevenue = $data->filter(fn($o) => in_array($o->status, ['diproses', 'dikirim', 'selesai']))->sum('total_price');

                // Total Pendapatan di kolom E-F
                $sheet->setCellValue("E{$summaryRow}", 'Total Pendapatan');
                $sheet->setCellValue("F{$summaryRow}", $totalRevenue);
                
                $sheet->getStyle("E{$summaryRow}:F{$summaryRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EAF3DE']
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
                    ]
                ]);

                // Format ribuan untuk kolom F
                $sheet->getStyle("F2:F{$summaryRow}")->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
