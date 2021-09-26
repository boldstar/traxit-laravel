<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('business_id')->unsigned()->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->boolean('payroll')->default(false);
            $table->boolean('sales_tax')->default(false);
            $table->boolean('tax_return')->default(false);
            $table->boolean('bookkeeping')->default(false);
            $table->boolean('tax_planning')->default(false);
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
        Schema::dropIfExists('business_services');
    }
}
