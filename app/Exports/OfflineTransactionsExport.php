<?php

namespace App\Exports;

use App\Models\OfflineTransaction;
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

class OfflineTransactionsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithMapping, ShouldAutoSize, WithEvents
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
        return 'Transaksi Offline';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Transaksi',
            'Nama Penjual',
            'Produk',
            'Jumlah',
            'Status',
            'Total (Rp)',
            'Tanggal',
        ];
    }

    public function map($trx): array
    {
        $this->rowCount++;
        $isCancelled = $trx->status === 'dibatalkan';

        // Gabungkan nama produk tanpa x1, x2
        $productNames = $trx->items->map(function($item) {
            return ($item->product_name ?? ($item->product->name ?? 'Produk'));
        })->implode(', ');

        return [
            $trx->id,
            $trx->transaction_code,
            $trx->seller?->name ?? '-',
            $productNames,
            $trx->items->sum('qty'),
            ucfirst($trx->status),
            $isCancelled ? '—' : $trx->total_price,
            $trx->created_at->format('d-m-Y H:i'),
        ];
    }

    public function collection()
    {
        $query = OfflineTransaction::query()->with(['seller', 'items.product']);

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
                foreach ($data as $index => $trx) {
                    if ($trx->status === 'dibatalkan') {
                        $currentRow = $index + 2;
                        // Kolom G adalah Total (Rp)
                        $sheet->getStyle("G{$currentRow}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FCEBEB');
                    }
                }

                $totalRevenue = $data->filter(fn($t) => $t->status !== 'dibatalkan')->sum('total_price');

                // Total Pendapatan di kolom F-G
                $sheet->setCellValue("F{$summaryRow}", 'Total Pendapatan');
                $sheet->setCellValue("G{$summaryRow}", $totalRevenue);
                
                $sheet->getStyle("F{$summaryRow}:G{$summaryRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EAF3DE']
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
                    ]
                ]);

                $sheet->getStyle("G2:G{$summaryRow}")->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
