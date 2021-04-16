<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuctionFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('auction_price')->default(0)->after('updated_by_id');
            $table->integer('auction_price_min')->default(0)->after('auction_price');
            $table->unsignedTinyInteger('auction_show')->default(0)->after('auction_price_min');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['auction_price', 'auction_price_min', 'auction_show']);
        });
    }
}
