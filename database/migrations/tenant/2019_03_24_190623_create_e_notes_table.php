<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateENotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('engagement_id')->unsigned()->nullable();
            $table->foreign('engagement_id')->references('id')->on('engagements');
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
        Schema::dropIfExists('e_notes');
    }
}
