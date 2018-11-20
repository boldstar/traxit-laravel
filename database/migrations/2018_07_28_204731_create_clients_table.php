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
            $table->string('middle_initial')->nullable($value = 'undefined');
            $table->string('last_name');
            $table->string('occupation')->nullable($value = 'N/A');
            $table->string('dob')->nullable($value = 'N/A');
            $table->string('email')->nullable($value = 'N/A');
            $table->string('cell_phone')->nullable($value = 'N/A');
            $table->string('work_phone')->nullable($value = 'N/A');

            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_middle_initial')->nullable();
            $table->string('spouse_last_name')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_dob')->nullable();
            $table->string('spouse_email')->nullable();
            $table->string('spouse_cell_phone')->nullable();
            $table->string('spouse_work_phone')->nullable();

            $table->string('street_address')->nullable($value = 'N/A');
            $table->string('city')->nullable($value = 'N/A');
            $table->string('state')->nullable($value = 'N/A');
            $table->string('postal_code')->nullable($value = 'N/A');
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
