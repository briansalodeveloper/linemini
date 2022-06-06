<?php

namespace App\Helpers\Excel\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class ExcelToArrayImport implements OnEachRow
{
    use Importable;

    /**
     * @return Row $row
     */
    public function onRow(Row $row): array
    {
        return $row;
    }
}
