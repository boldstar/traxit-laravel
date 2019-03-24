<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDifficultyFeeArchiveToEngagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engagements', function (Blueprint $table) {
            $table->integer('difficulty')->nullable()->after('status');
            $table->string('fee')->nullable()->after('difficulty');
            $table->boolean('owed')->default(false)->after('fee');
            $table->string('balance')->nullable()->after('owed');
            $table->boolean('archive')->default(false)->after('balance');
            $table->timestamp('estimated_date')->nullable()->after('done');
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
