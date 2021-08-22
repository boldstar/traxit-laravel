<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('engagement_id')->unsigned();
            $table->foreign('engagement_id')->references('id')->on('engagements')->onDelete('cascade');
            $table->string('engagement_name');
            $table->string('user_name');
            $table->string('current_status');
            $table->text('comments')->nullable();
            $table->integer('total_calls');
            $table->timestamp('last_called_date');
            $table->timestamp('first_called_date');
            $table->boolean('archive')->default(false);
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
        Schema::dropIfExists('call_lists');
    }
}
