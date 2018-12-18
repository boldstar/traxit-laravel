<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('engagement_id')->unsigned();
            $table->text('question');
            $table->boolean('answered')->default(false);
            $table->timestamps();
        });

        Schema::table('questions', function(Blueprint $table)
        {
            $table->foreign('engagement_id')->references('id')->on('engagements')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
