<?php

use Illuminate\Database\Seeder;
use App\Client;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
        $client->category = 'Client';
        $client->referral_type = 'Google';
        $client->first_name = 'John';
        $client->middle_initial = 'B';
        $client->last_name = 'Smith';
        $client->occupation = 'Sales';
        $client->dob = '02/08/1965';
        $client->email = 'john@example.com';
        $client->cell_phone = '528-965-7456';
        $client->work_phone = '528-965-7456';
        $client->spouse_first_name = 'Jane';
        $client->spouse_middle_initial = 'R';
        $client->spouse_last_name = 'Smith';
        $client->spouse_occupation = 'Dentist';
        $client->spouse_dob = '02/08/1965';
        $client->spouse_email = 'jane@example.com';
        $client->spouse_cell_phone = '528-965-7456';
        $client->spouse_work_phone = '528-965-7456';
        $client->street_address = 'Hwy 963';
        $client->city = 'City';
        $client->state = 'ST';
        $client->postal_code = '45698';
        $client->save();


    }
}
