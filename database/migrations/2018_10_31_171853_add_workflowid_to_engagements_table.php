<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWorkflowidToEngagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('engagements', function (Blueprint $table) {
        //     $table->dropColumn('workflow_id');
        // });

        Schema::table('engagements', function (Blueprint $table) {
            $table->integer('workflow_id')->unsigned()->nullable()->after('client_id');
            $table->foreign('workflow_id')->references('id')->on('workflows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engagements', function (Blueprint $table) {
            //
        });
    }
}
