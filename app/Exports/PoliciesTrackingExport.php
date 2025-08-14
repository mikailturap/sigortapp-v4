<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class PoliciesTrackingExport implements FromArray, WithTitle
{
    /** @var array<int, array<int, string|null>> */
    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        return 'Poliçeler';
    }
}



