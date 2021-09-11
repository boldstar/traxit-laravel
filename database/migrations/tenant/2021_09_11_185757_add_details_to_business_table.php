<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailsToBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->string('ein')->nullable();
            $table->string('tax_return_type')->nullable();
            $table->string('state_tax_id')->nullable();
            $table->string('sos_file_number')->nullable();
            $table->string('xt_number')->nullable();
            $table->string('rt_number')->nullable();
            $table->string('formation_date')->nullable();
            $table->string('twc_account')->nullable();
            $table->string('qb_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business', function (Blueprint $table) {
            //
        });
    }
}
