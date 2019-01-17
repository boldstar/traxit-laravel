<?php

namespace App\Exports;

use App\Models\Tenant\Engagement;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EngagementsReportExport implements FromCollection

{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Engagement::all();
    }

    // public function headings(): array
    // {
    //     return [
    //         'Id',
    //     ];
    // }
}
