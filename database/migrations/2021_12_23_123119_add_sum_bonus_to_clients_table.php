<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Client;

class AddSumBonusToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->integer('sum_bonus')->default(0)->after('sum');
        });

        $clients = Client::get(['id', 'referral_code']);
        foreach ($clients as $client) {
            if ($client->referral_code === 0) {
                $client->update(['referral_code' => mt_rand(111111, 999999)]);
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('sum_bonus');
        });
    }
}
