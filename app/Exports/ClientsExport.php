<?php

namespace App\Exports;

use App\Models\Tenant\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $clients = Client::all();

        foreach ($clients as $client) {
            if($client->dob != 'Invalid' && $client->dob != '' && $client->dob != null) {
                $client->dob = \Carbon\Carbon::parse($client->dob)->format('m/d/Y');
            } if ($client->spouse_dob != 'Invalid' && $client->spouse_dob != '' && $client->spouse_dob != null) {
                $client->spouse_dob = \Carbon\Carbon::parse($client->spouse_dob)->format('m/d/Y');
            }
        }

        return $clients;
    }

    public function headings(): array
    {
        return [
            'id',
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
