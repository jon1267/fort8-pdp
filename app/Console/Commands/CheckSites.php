<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CheckSites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:sites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check sites access, its responses & ability create order';

    private $sites = [
        'https://pdparis.net/', 'https://parfumdeparis.biz/','https://pdparis.ru/','https://pdparis.org/',
        'https://pd-paris.ru/','http://pdp-only.ru/','https://pdp-paris.ru/', 'https://pdparis.com/',
    ];

    private $postmaster = 'korobka.dima@gmail.com';

    private $testOrder = [
        'name' => 'тестовый заказ',
        'email' => 'test@test.com',
        'tel' => '+38 (000) 000-00-00',
        'adv' => 218,
        'basket' => [
            0 => [
                'art' => 'W065-30',
                'qty' => 1,
                'price' => 590,
                'sale' => 590,
                'vol' => 30,
                'name' => 'No 5 ',
                'bname' => 'Chanel',
                'total' => 590,
            ],
        ]
    ];

    private $oldOrder = [
        'tel' => '+38 (000) 000-00-00',
        'comment'=> 'test order, old sites',
        'adv' => 218,
    ];

    private $oldBasket = ['id' => 6, 'art' => 'W027', 'price' => 390, 'volume' => 100];

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
        foreach ($this->sites as $site)
        {
            $result = $this->curlGet($site);

            if ($this->badSite($result)) {
                ////// ---  Mail::to($this->postmaster)->send();
                Mail::raw($site .' - не работает, пожалуйста проверьте вручную.' , function ($mess)  {
                    $mess->to($this->postmaster);
                    $mess->subject('Проверка сайтов');
                });
                //echo $site . ' - not work'.'<br>';
                continue;
            }


            if (strpos( $result['response'],'<div class="vue">') && $this->badApiNew($site)) {
                // новый сайт
                Mail::raw($site .' - не работает api, пожалуйста проверьте вручную.' , function ($mess)  {
                    $mess->to($this->postmaster);
                    $mess->subject('Проверка сайтов');
                });
                //echo $site . ' - api not work (new site)'.'<br>';
                continue;
            }

            if (strpos( $result['response'],'made in France')) {
                $oldOrder = $this->curlPostSession($site.'cart/add_volume', $this->oldBasket);
                //dd($oldOrder);
                if ($oldOrder['status'] === 200 && isset($oldOrder['cookies']['PHPSESSID']) && strlen($oldOrder['cookies']['PHPSESSID'])) {

                    if ($this->badApiOld($site, $oldOrder['cookies']['PHPSESSID'])) {
                        Mail::raw($site .' - не работает api, пожалуйста проверьте вручную.' , function ($mess)  {
                            $mess->to($this->postmaster);
                            $mess->subject('Проверка сайтов');
                        });
                        //echo $site . ' - api not work (old site)'.'<br>';
                        continue;
                    }


                }

            }

        }
        //echo 'all pass at: '. date('Y-m-d H:i:s');
        return 0;
    }

    public function badApiNew($site)
    {
        $apiTest = $this->curlPost($site.'api/store', $this->testOrder); //dd($apiTest, $site.'api/store');
        return  !($apiTest['status'] === 200) && is_numeric($apiTest['response']);
    }

    public function badApiOld($site, $phpSessionId)
    {
        $apiTest = $this->curlPostSession($site.'cart/store', $this->oldOrder,0, $phpSessionId);
        return  !($apiTest['status'] === 200) && strpos($apiTest['response'], 'ok');
    }

    private function badSite(array $result)
    {
        $okStatus = in_array($result['status'], [200, 302], true );
        $okLength =  (is_string($result['response']) && strlen($result['response']) > 100);

        return !($okStatus && $okLength);
    }

    private function curlGet($link)
    {

        $curl = curl_init($link);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

        $response = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return ['response' => $response, 'status' => $status];
    }

    public function curlPost($link, $data = [], $isJsonData = 0) {

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

    public function curlPostSession($link, $data = [], $isJsonData = 0, $phpSessionId=null) {

        $curl = curl_init($link);

        curl_setopt($curl, CURLOPT_COOKIESESSION,  true);
        curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $phpSessionId);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HEADER, true);

        ($isJsonData == 0) ?
            curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query($data) ) :
            curl_setopt($curl, CURLOPT_POSTFIELDS,  $data);


        $response = curl_exec($curl); //json_decode(curl_exec($this->curl));
        $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // multi-cookie variant contributed by @Combuster in comments
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
        $cookies = [];
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }

        curl_close($curl);

        return ['response' => $response, 'status' => $status, 'cookies' => $cookies];
    }
}
