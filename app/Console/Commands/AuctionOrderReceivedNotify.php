<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AuctionOrderReceivedNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:received-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event-notify, that auction order was received';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = DB::connection('mysql2')->table('landing_orders')
            ->where('adv', '=', 335)
            ->where('datereceived_original', '!=', '0000-00-00 00:00:00')
            ->where('datereceived_original', '>=', Carbon::now()->subHours(24)->toDateTimeString())
            ->get(['id', 'phone']);
        //dd($orders, gettype($orders));

        $link = 'http://pdp-auction.brightmedia.ua/aukcion/send-notify';
        $result = [];

        if ($orders) {
            foreach ($orders as $order) {
                $data = [
                    'key' => '12345678',
                    'event' => 'review',
                    'userphone' => $order->phone,
                    'id' => $order->id,
                ];
                $result[] = $this->curlPost($link, $data);
                //$result[] = $this->curlGet($link .'/?'. http_build_query($data));
            }
        }

        //dd($result);
        //echo print_r($result,1);

        return 0;
    }

    private function curlPost($link, $data = [], $isJsonData = 0)
    {
        $curl = curl_init($link);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

        ($isJsonData == 0) ?
            curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query($data) ) :
            curl_setopt($curl, CURLOPT_POSTFIELDS,  $data);

        $response = curl_exec($curl); //json_decode(curl_exec($this->curl));
        $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return ['response' => $response, 'status' => $status ];
    }

    private function curlGet(string $link)
    {
        return file_get_contents($link);
    }

}
