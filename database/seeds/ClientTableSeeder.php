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
        $client->first_name = 'James';
        $client->middle_initial = 'B';
        $client->last_name = 'Gordon';
        $client->occupation = 'Sales';
        $client->dob = '02/08/1965';
        $client->email = 'james@example.com';
        $client->cell_phone = '528-965-7456';
        $client->work_phone = '528-965-7456';
        $client->spouse_first_name = 'Sarah';
        $client->spouse_middle_initial = 'R';
        $client->spouse_last_name = 'Gordon';
        $client->spouse_occupation = 'Dentist';
        $client->spouse_dob = '02/08/1965';
        $client->spouse_email = 'sarah@example.com';
        $client->spouse_cell_phone = '528-965-7456';
        $client->spouse_work_phone = '528-965-7456';
        $client->street_address = 'Hwy 963';
        $client->city = 'City';
        $client->state = 'ST';
        $client->postal_code = '45698';
        $client->save();

        $client = new Client();
        $client->category = 'Client';
        $client->referral_type = 'Google';
        $client->first_name = 'Roger';
        $client->middle_initial = 'B';
        $client->last_name = 'Harris';
        $client->occupation = 'Teacher';
        $client->dob = '02/08/1965';
        $client->email = 'roger@example.com';
        $client->cell_phone = '528-965-7456';
        $client->work_phone = '528-965-7456';
        $client->spouse_first_name = 'Sally';
        $client->spouse_middle_initial = 'R';
        $client->spouse_last_name = 'Harris';
        $client->spouse_occupation = 'Homemaker';
        $client->spouse_dob = '02/08/1965';
        $client->spouse_email = 'sally@example.com';
        $client->spouse_cell_phone = '528-965-7456';
        $client->spouse_work_phone = '528-965-7456';
        $client->street_address = 'Hwy 963';
        $client->city = 'City';
        $client->state = 'ST';
        $client->postal_code = '45698';
        $client->save();
        
        $client = new Client();
        $client->category = 'Prospect';
        $client->referral_type = 'Google';
        $client->first_name = 'Sam';
        $client->middle_initial = 'B';
        $client->last_name = 'Smith';
        $client->occupation = 'Retired';
        $client->dob = '02/08/1965';
        $client->email = 'sam@example.com';
        $client->cell_phone = '528-965-7456';
        $client->work_phone = '528-965-7456';
        $client->spouse_first_name = 'Jane';
        $client->spouse_middle_initial = 'R';
        $client->spouse_last_name = 'Smith';
        $client->spouse_occupation = 'Retired';
        $client->spouse_dob = '02/08/1965';
        $client->spouse_email = 'jane@example.com';
        $client->spouse_cell_phone = '528-965-7456';
        $client->spouse_work_phone = '528-965-7456';
        $client->street_address = 'Hwy 963';
        $client->city = 'City';
        $client->state = 'ST';
        $client->postal_code = '45698';
        $client->save();

        $client = new Client();
        $client->category = 'Prospect';
        $client->referral_type = 'Word of mouth';
        $client->first_name = 'Bill';
        $client->middle_initial = 'B';
        $client->last_name = 'Thompson';
        $client->occupation = 'Sales';
        $client->dob = '02/08/1965';
        $client->email = 'billy@example.com';
        $client->cell_phone = '528-965-7456';
        $client->work_phone = '528-965-7456';
        $client->spouse_first_name = 'Rachel';
        $client->spouse_middle_initial = 'R';
        $client->spouse_last_name = 'Thompson';
        $client->spouse_occupation = 'Engineer';
        $client->spouse_dob = '02/08/1965';
        $client->spouse_email = 'rachel@example.com';
        $client->spouse_cell_phone = '528-965-7456';
        $client->spouse_work_phone = '528-965-7456';
        $client->street_address = 'Hwy 963';
        $client->city = 'City';
        $client->state = 'ST';
        $client->postal_code = '45698';
        $client->save();

        $client = new Client();
        $client->category = 'Client';
        $client->referral_type = 'Google';
        $client->first_name = 'James';
        $client->middle_initial = 'B';
        $client->last_name = 'Gordon';
        $client->occupation = 'Sales';
        $client->dob = '02/08/1965';
        $client->email = 'james@example.com';
        $client->cell_phone = '528-965-7456';
        $client->work_phone = '528-965-7456';
        $client->spouse_first_name = 'Sarah';
        $client->spouse_middle_initial = 'R';
        $client->spouse_last_name = 'Gordon';
        $client->spouse_occupation = 'Dentist';
        $client->spouse_dob = '02/08/1965';
        $client->spouse_email = 'james@example.com';
        $client->spouse_cell_phone = '528-965-7456';
        $client->spouse_work_phone = '528-965-7456';
        $client->street_address = 'Hwy 963';
        $client->city = 'City';
        $client->state = 'ST';
        $client->postal_code = '45698';
        $client->save();

    }
}
