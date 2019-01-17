<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngagementActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engagement_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('engagement_model');
            $table->integer('engagement_id')->unsigned();
            $table->foreign('engagement_id')->references('id')->on('engagements')->onDelete('cascade');
            $table->integer('workflow_id')->unsigned();
            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
            $table->string('action');
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->string('year')->nullable();
            $table->string('return_type')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('engagement_actions');
    }
}
