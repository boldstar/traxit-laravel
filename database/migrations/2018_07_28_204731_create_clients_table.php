<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category');
            //the data for the tax payer
            $table->string('first_name');
            $table->string('middle_initial', 1);
            $table->string('last_name');
            $table->string('occupation');
            $table->string('dob');
            //the tax payer contact information
            $table->string('email');
            $table->string('cell_phone', 12);
            $table->string('work_phone', 12);
            //the data for the spouse
            $table->string('spouse_first_name');
            $table->string('spouse_middle_initial', 1);
            $table->string('spouse_last_name');
            $table->string('spouse_occupation');
            $table->string('spouse_dob');
            //the spouse contact information
            $table->string('spouse_email');
            $table->string('spouse_cell_phone', 12);
            $table->string('spouse_work_phone', 12);
            //the address information
            $table->string('street_address');
            $table->string('city');
            $table->string('state', 2);
            $table->string('postal_code');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
