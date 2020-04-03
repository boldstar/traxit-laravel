<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountCloseDateToBookkeepingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookkeepings', function (Blueprint $table) {
            $table->datetime('account_close_date')->nullable()->after('account_start_date');
            $table->boolean('account_closed')->default(false)->after('account_close_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookkeepings', function (Blueprint $table) {
            //
        });
    }
}
