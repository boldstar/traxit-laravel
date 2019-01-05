<?php

namespace App\Exports;

use App\Models\Tenant\Client;
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
        // $clients = Client::select('dob')->where('id', 9)->first();

        // $formated = \Carbon\Carbon::parse($client->dob)->format('d/m/y');
        
        // foreach($clients as $client) {
        //     return \Carbon\Carbon::parse($client->dob)->format('d/m/y'); 
        // };

        // return $clients;

        $clients = Client::select(
            'active',
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
            'has_spouse',
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

        // foreach($clients as $client) {
        //     $client->dob = \Carbon\Carbon::parse($client->dob)->format('d/m/y'); 
        // };

        return $clients;
    }

    public function headings(): array
    {
        return [
            'active',
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
            'has_spouse',
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
