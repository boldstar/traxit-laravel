<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwoAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_auths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('token');
            $table->datetime('expires_on');
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
        Schema::table('two_auths', function (Blueprint $table) {
            //
        });
    }
}
