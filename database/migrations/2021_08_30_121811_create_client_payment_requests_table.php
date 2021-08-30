<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPaymentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->integer('sum');
            $table->string('comment');
            $table->string('card');
            $table->unsignedTinyInteger('paid')->default(0);
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
        Schema::dropIfExists('client_payment_requests');
    }
}
