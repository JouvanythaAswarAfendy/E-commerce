<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesReportExport implements WithMultipleSheets
{
    protected $dateFrom;
    protected $dateTo;

    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function sheets(): array
    {
        return [
            new OrdersExport($this->dateFrom, $this->dateTo),
            new OfflineTransactionsExport($this->dateFrom, $this->dateTo),
        ];
    }
}
