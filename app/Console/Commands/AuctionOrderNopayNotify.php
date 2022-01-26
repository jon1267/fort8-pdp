<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class AuctionOrderNopayNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:not-pay-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event-notify, that auction order was not payed at 48 hours';

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
        // datebuy позже чем (сейчас минус 3 часа)
        $notPayed3 =  DB::connection('mysql2')->table('landing_orders')
            ->where('adv', '=', 335)
            ->where('status_pay', '=', 1)
            ->where('paid', '=', 0)
            ->where('datebuy', '<=', Carbon::now()->subHours(3)->toDateTimeString())
            ->where('datebuy', '>=', Carbon::now()->subHours(4)->toDateTimeString())
            ->get(['id', 'phone']);

        // datebuy позже чем ->(сейчас минус 48 часов)
        $notPayed48 =  DB::connection('mysql2')->table('landing_orders')
            ->where('adv', '=', 335)
            ->where('status_pay', '=', 1)
            ->where('paid', '=', 0)
            ->where('datebuy', '<=', Carbon::now()->subHours(48)->toDateTimeString())
            ->where('datebuy', '>=', Carbon::now()->subHours(49)->toDateTimeString())
            ->get(['id', 'phone']);

        //dd($notPayed3, $notPayed48);

        $result3 = $this->makeIt($notPayed3);
        $result48 = $this->makeIt($notPayed48);
        //dd($result3, $result48);

        return 0;
    }

    private function makeIt($orders)
    {
        if (empty($orders)) return 0;

        $link = 'http://pdp-auction.brightmedia.ua/aukcion/send-notify';
        $result = [];

        foreach ($orders as $order) {
            $data = [
                'key' => '12345678',
                'event' => 'nopay',
                'userphone' => $order->phone,
                'id' => $order->id,
            ];

            $result[] = $this->curlPost($link, $data);
        }

        return $result;
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
}
