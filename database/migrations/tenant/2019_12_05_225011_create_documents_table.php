<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->string('document_name');
            $table->string('tax_year')->nullable();
            $table->text('path');
            $table->text('message')->nullable();
            $table->string('account');
            $table->boolean('downloadable')->default(true);
            $table->boolean('payment_required')->default(false);
            $table->boolean('signature_required')->default(false);
            $table->boolean('paid')->default(false);
            $table->boolean('signed')->default(false);
            $table->string('uploaded_by');
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
        Schema::dropIfExists('documents');
    }
}
