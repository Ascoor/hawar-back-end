<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MemberFeesImport implements ToCollection, WithStartRow
{
    private $rows;
    private $currentRow = 0;
    private $rowCount = 0;
    private $lastProcessedRow = 0;

    public function collection(Collection $rows)
    {
        $this->rows = $rows->toArray();
        $this->rowCount = count($this->rows);
    }

    public function startRow(): int
    {
        return $this->lastProcessedRow + 1;
    }

    public function nextRow(): void
    {
        $this->currentRow++;
    }

    public function getRowData()
    {
        if ($this->currentRow >= $this->rowCount) {
            return null;
        }

        return $this->rows[$this->currentRow];
    }

    public function getLastProcessedRow()
    {
        return $this->lastProcessedRow;
    }

    public function storeLastProcessedRow($rowNumber)
    {
        $this->lastProcessedRow = $rowNumber;
    }
}
