<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('business_id')->unsigned()->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('business_notes');
    }
}
