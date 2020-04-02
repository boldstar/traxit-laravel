<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookkeepingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookkeepings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->string('belongs_to');
            $table->string('business_name')->nullable();
            $table->string('account_name');
            $table->string('account_type');
            $table->string('year');
            $table->boolean('jan')->default(false);
            $table->boolean('feb')->default(false);
            $table->boolean('mar')->default(false);
            $table->boolean('apr')->default(false);
            $table->boolean('may')->default(false);
            $table->boolean('jun')->default(false);
            $table->boolean('jul')->default(false);
            $table->boolean('aug')->default(false);
            $table->boolean('sep')->default(false);
            $table->boolean('oct')->default(false);
            $table->boolean('nov')->default(false);
            $table->boolean('dec')->default(false);
            $table->datetime('account_start_date')->nullable();
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
        Schema::dropIfExists('bookkeepings');
    }
}
