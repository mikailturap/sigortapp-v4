<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TrackingSummaryExport implements WithMultipleSheets
{
    protected array $sheetsData; // [title => rows(array)]

    public function __construct(array $sheetsData)
    {
        $this->sheetsData = $sheetsData;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->sheetsData as $title => $rows) {
            $sheets[] = new class($rows, $title) extends PoliciesTrackingExport {
                protected string $tabTitle;
                public function __construct(array $rows, string $title)
                {
                    parent::__construct($rows);
                    $this->tabTitle = $title;
                }
                public function title(): string { return $this->tabTitle; }
            };
        }
        return $sheets;
    }
}


