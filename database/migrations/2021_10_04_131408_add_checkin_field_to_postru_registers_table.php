<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckinFieldToPostruRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('postru_registers', function (Blueprint $table) {
            $table->unsignedTinyInteger('checkin')->default(0)->after('barcodes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('postru_registers', function (Blueprint $table) {
            $table->dropColumn('checkin');
        });
    }
}
