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
            $table->string('referral_type');

            $table->string('first_name');
            $table->string('middle_initial');
            $table->string('last_name');
            $table->string('occupation');
            $table->string('dob');
            $table->string('email');
            $table->string('cell_phone');
            $table->string('work_phone');

            $table->string('spouse_first_name');
            $table->string('spouse_middle_initial');
            $table->string('spouse_last_name');
            $table->string('spouse_occupation');
            $table->string('spouse_dob');
            $table->string('spouse_email');
            $table->string('spouse_cell_phone');
            $table->string('spouse_work_phone');

            $table->string('street_address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->timestamps();
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
