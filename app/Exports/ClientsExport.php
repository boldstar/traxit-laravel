<?php

namespace App\Exports;

use App\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Query
    */
    public function query()
    {
        return Client::select(
            'category',
            'referral_type', 
            'first_name', 
            'middle_initial', 
            'last_name', 
            'occupation', 
            'dob', 
            'email', 
            'cell_phone', 
            'work_phone',
            'spouse_first_name', 
            'spouse_middle_initial', 
            'spouse_last_name', 
            'spouse_occupation', 
            'spouse_dob', 
            'spouse_email', 
            'spouse_cell_phone', 
            'spouse_work_phone', 
            'street_address',
            'city',
            'state',
            'postal_code',
            'created_at',
            'updated_at'

        );
    }

    public function headings(): array
    {
        return [
            'category',
            'referral_type', 
            'first_name', 
            'middle_initial', 
            'last_name', 
            'occupation', 
            'dob', 
            'email', 
            'cell_phone', 
            'work_phone',
            'spouse_first_name', 
            'spouse_middle_initial', 
            'spouse_last_name', 
            'spouse_occupation', 
            'spouse_dob', 
            'spouse_email', 
            'spouse_cell_phone', 
            'spouse_work_phone', 
            'street_address',
            'city',
            'state',
            'postal_code',
            'created_at',
            'updated_at'
        ];
    }
}
