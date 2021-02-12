<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aroma_id')->nullable();
            $table->unsignedBigInteger('brand_id');
            $table->string('vendor');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('description_ua')->nullable();
            $table->string('img');
            $table->string('img2')->nullable();
            $table->string('img3')->nullable();
            $table->unsignedTinyInteger('hide')->default(0);
            $table->bigInteger('sort')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
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
        Schema::dropIfExists('products');
    }
}
