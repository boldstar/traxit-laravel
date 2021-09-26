<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('expires')->nullable();
            $table->string('issued')->nullable();
            $table->string('access_token')->nullable();
            $table->integer('expires_in')->nullable();
            $table->string('mfa_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('token_type')->nullable();
            $table->string('user_id')->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('integrations');
    }
}
