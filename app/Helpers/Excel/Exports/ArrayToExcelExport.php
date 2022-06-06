<?php

namespace App\Helpers\Excel\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ArrayToExcelExport implements FromCollection, WithStrictNullComparison
{
    protected $data;

    /**
     * @param Array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return new Collection([$this->data]);
    }
}
