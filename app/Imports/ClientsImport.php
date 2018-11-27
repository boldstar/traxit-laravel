<?php

namespace App\Imports;

use App\Client;
use Maatwebsite\Excel\Concerns\ToModel;

class ClientsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Client([
            'category' => $row[0],
            'referral_type' => $row[1],
            'first_name' => $row[2],
            'middle_initial' => $row[3],
            'last_name' => $row[4],
            'occupation' => $row[5], 
            'dob' => $row[6],
            'email' => $row[7], 
            'cell_phone' => $row[8],
            'work_phone' => $row[9],
            'has_spouse' => $row[10],
            'spouse_first_name' => $row[11], 
            'spouse_middle_initial' => $row[12], 
            'spouse_last_name' => $row[13], 
            'spouse_occupation' => $row[14], 
            'spouse_dob' => $row[15], 
            'spouse_email' => $row[16], 
            'spouse_cell_phone' => $row[17], 
            'spouse_work_phone' => $row[18], 
            'street_address' => $row[19],
            'city' => $row[20],
            'state' => $row[21],
            'postal_code' => $row[22], 
        ]);
    }
}
