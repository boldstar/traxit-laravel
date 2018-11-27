<?php

namespace App\Exports;

use App\Engagement;
use App\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EngagementsExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Query
    */
    public function query()
    {
        return Engagement::select('id', 'return_type', 'year', 'assigned_to', 'status', 'created_at');

    }

    public function headings(): array
    {
        return [
            'Id',
            'Return Type',
            'Year',
            'Assigned To',
            'Status',
            'Created Date',
        ];
    }
}
