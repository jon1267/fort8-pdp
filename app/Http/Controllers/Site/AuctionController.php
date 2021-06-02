<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Aroma;
use App\Models\Brand;
use App\Models\Note;
use App\Models\Product;
use App\Models\Category;
use App\Models\Client;
use App\Http\Requests\AuctionClientRegisterRequest;
use App\Http\Requests\AuctionClientLoginRequest;
use App\Http\Requests\AuctionSendCartRequest;
use App\Http\Requests\AuctionSetDiscountRequest;
use App\Services\Sms\Sms;
use App\Services\Import\Csv;
use Illuminate\Support\Facades\Http;

class AuctionController extends Controller
{
    const API_KEY = '12345678';
    private $sms;

    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    public function getManufacturer(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        // меняем подход: считаем у нас 1 vendor='PdParis'. отдаем только c id=1, и без id одна запись (тоже с id=1 :)
        if ($id) {
            $dat = ($id == 1) ? Product::where('id', $id)->get(['id', 'vendor'])->toArray() : [];
        } else {
            $dat = Product::all(['id', 'vendor'])->take(1)->toArray();
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['vendor']],
                    'ua' => ['name' => $item['vendor']],
                ]
            ];
        }
        return response()->json($data);
    }

    public function getAroma(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        if ($id) {
            $dat = Category::where('id', $id)->get(['id', 'name', 'name_ua'])->toArray();
        } else {
            $dat = Category::all(['id', 'name', 'name_ua'])->toArray();
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['name']],
                    'ua' => ['name' => $item['name_ua']],
                ]
            ];
        }

        return response()->json($data);
    }

    public function getBrand(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        if ($id) {
            $dat = Brand::where('id', $id)->get(['id', 'name'])->toArray();
        } else {
            $dat = Brand::all(['id', 'name'])->toArray();
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['name']],
                    'ua' => ['name' => $item['name']],
                ]
            ];
        }

        return response()->json($data);
    }

    public function getFamily(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        if ($id) {
            $dat = Aroma::where('id', $id)->get(['id', 'name', 'name_ua'])->toArray();
        } else {
            $dat = Aroma::all(['id', 'name', 'name_ua'])->toArray();
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['name']],
                    'ua' => ['name' => $item['name_ua']],
                ]
            ];
        }

        return response()->json($data);
    }

    public function getNotes(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        if ($id) {
            $dat = Note::where('id', $id)->get(['id', 'name_ru', 'name_ua'])->toArray();
        } else {
            $dat = Note::all(['id', 'name_ru', 'name_ua'])->toArray();
        }

        $data = [];
        foreach ($dat as $item) {
            $data[$item['id']] = [
                [
                    'ru' => ['name' => $item['name_ru']],
                    'ua' => ['name' => $item['name_ua']],
                ]
            ];
        }

        return response()->json($data);
    }

    public function getProduct(Request $request)
    {
        if ($request->get('key') !== self::API_KEY) abort(404);

        $id = $request->get('id');

        //это при дальнейшем усложнении переделать как в AggregatorController или сервис, по данным вариантов товара ?
        if ($id) {
            $dat = Product::with(['categories', 'notes', 'notes2', 'notes3', 'productVariants'])
                ->whereHas('productVariants', function ($query) {
                    $query->where([['active_ua', '=' , 1 ], ['volume', '=', 100.00] ]);
                })
                ->whereHas('categories', function ($query) {
                    $query->whereIn('categories.id', [1,2]);
                })
                ->where('id', $id)
                ->get([
                    'id', 'vendor', 'name', 'description', 'description_ua', 'img', 'img2', 'img3', 'aroma_id',
                    'brand_id', 'auction_price', 'auction_price_min', 'auction_show'
                ])->toArray();
        } else {
            $dat = Product::with(['categories', 'notes', 'notes2', 'notes3', 'productVariants'])
                ->whereHas('productVariants', function ($query) {
                    $query->where([['active_ua', '=' , 1 ], ['volume', '=', 100.00] ]);
                })
                ->whereHas('categories', function ($query) {
                    $query->whereIn('categories.id', [1,2]);
                })
                ->get([
                    'id', 'vendor', 'name', 'description', 'description_ua', 'img', 'img2', 'img3', 'aroma_id',
                    'brand_id', 'auction_price', 'auction_price_min', 'auction_show'
                ])->toArray();
        }
        //dd($dat);

        $data = [];
        foreach ($dat as $item) {
            if (is_array($item) && count($item) && $item['auction_show']) {

                /*$price = array_map( function ($priceUa) {
                    return ($priceUa['active_ua'] == 1 && $priceUa['volume'] == 100) ? $priceUa['price_ua'] : 0;},
                    $item['product_variants']
                );*/

                $data[$item['id']] = [
                    [
                        'ru' => [
                            'name' => $item['name'],
                            'descr' => $item['description'],
                        ],

                        'ua' => [
                            'name' => $item['name'],
                            'descr' => $item['description_ua'],
                        ],
                        'images' => [
                            0 => $item['img'] ? url('/') . $item['img'] : null,
                            //1 => $item['img2'] ? url('/') . $item['img2'] : null,
                            //2 => $item['img3'] ? url('/') . $item['img3'] : null,
                        ],
                        'p_price'  => $item['auction_price'],//max($price),
                        'p_priceD' => $item['auction_price_min'], //0,
                        'count' => 100,
                        'volume' => 100,
                        'art' => $item['product_variants'][2]['art'] ?? '',
                        'manuf_id' => 1, //$item['vendor'],
                        'aroma_id' => $item['aroma_id'],
                        'brand_id' => $item['brand_id'],
                        'note1' => array_map(function ($note) {return $note['id'];}, $item['notes']),
                        'note2' => array_map(function ($note2) {return $note2['id'];}, $item['notes2']),
                        'note3' => array_map(function ($note3) {return $note3['id'];}, $item['notes3']),
                        'categories' => array_map(function ($category) {return $category['id'];}, $item['categories']),
                        'family' => [],
                        //'notes' =>  implode(', ', array_map( function ($note) {return $note['name_ru'];}, $item['notes'])),
                        //'categories' =>  implode(', ', array_map( function ($category) {return $category['name'];}, $item['categories'])),
                    ]
                ];
            }
        }
        return response()->json($data);
    }

    //  /auction/register
    public function register(AuctionClientRegisterRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $username = $request->username;
        $userphone = $request->userphone;

        $userphone = phone_format($userphone);
        if ($userphone === false) {
            return response()->json(['success'=>false, 'reason'=>'notransport']); //may be 'reason'=>'bad phone' ?
        }

        $client = null;
        if ($username && $userphone) {
            $client = Client::where([
                ['first_name', '=', $username],
                ['phone', '=', $userphone],
            ])->first();
        }

        // клиент существует, и active = 1
        if ($client && $client->active) {
            return response()->json(['success'=>false, 'reason'=>'exist']);
        }

        // клиент существует, но active = 0
        if ($client &&  (!$client->active)) {
            $code = mt_rand(11111, 99999);
            $text = 'Ваш пароль для входа в аукцион: ' . $code;
            $isSmsSend = $this->sms->sendSms($userphone, $text);
            if (!$isSmsSend) {
                return response()->json(['success'=>false, 'reason'=>'notransport']);
            }
            return response()->json(['success'=>true, 'password'=>$code]);
        }

        $code = mt_rand(11111, 99999);
        $text = 'Ваш пароль для входа в аукцион: ' . $code;
        $isSmsSend = $this->sms->sendSms($userphone, $text);
        if (!$isSmsSend) {
            return response()->json(['success'=>false, 'reason'=>'notransport']);
        }

        // создаем запись в табл clients (active = 0 by default)
        Client::create([
            'first_name' => $username,
            'phone' => $userphone,
        ]);

        return response()->json(['success'=>true, 'password'=>$code]);
    }

    // route: /auction/registerConfirm (post request with key & phone)
    // AuctionClientLoginRequest ошибки нет, тк это просто FormRequest с userphone и key
    public function registerConfirm(AuctionClientLoginRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);
        $userphone = phone_format($request->userphone);

        if ($userphone === false) {
            return response()->json(['success'=>false, 'reason'=>'notransport']); //may be 'reason'=>'bad phone' ?
        }

        $client = Client::where('phone', $userphone)->first();

        if ($client) {
            $client->update(['active' => 1]);
            return response()->json(['success'=>true, 'reason'=>'successfully activated']);
        }
        return response()->json(['success'=>false, 'reason'=>'client not exist']);
    }

    // /auction/login
    public function login(AuctionClientLoginRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $userphone = phone_format($request->userphone);

        $client = Client::where('phone', $userphone)->first();

        if ($client && $client->active) return response()->json(['status' => 10]);

        return  response()->json(['status' => 1]);
    }

    // route: /auction/changePass (post request with key & phone)
    // тут с AuctionClientLoginRequest ошибки нет, тк в нем есть userphone и key
    public function changePass(AuctionClientLoginRequest $request)
    {
        // если key не тот - 404
        if ($request->key !== self::API_KEY) abort(404);

        // прогоняем телефон через фильтр. если false - невалидный телефон
        $userphone = phone_format($request->userphone);
        if ($userphone === false) {
            return response()->json(['success'=>false, 'reason'=>'bad phone']); //old 'reason'=>'notransport' ?
        }

        $client = null;
        if ($userphone) {
            $client = Client::where('phone', '=', $userphone)->first();
        }

        //нет клиента или он неактивен
        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']);//old 'reason'=>'notransport'
        }

        $code = mt_rand(11111, 99999);
        $text = 'Ваш код подтверждения на смену пароля: ' . $code;
        $isSmsSend = $this->sms->sendSms($userphone, $text);

        if (!$isSmsSend) {
            return response()->json(['success'=>false, 'reason'=>'notransport']);
        }

        return response()->json(['success'=>true, 'password'=>$code]);
    }

    // route: /auction/changePhone (post request with key & phone)
    // с AuctionClientLoginRequest ошибки нет, там есть и userphone и key
    public function changePhone(AuctionClientLoginRequest $request)
    {
        // если key не тот - 404
        if ($request->key !== self::API_KEY) abort(404);

        // прогоняем телефон через фильтр. если false - невалидный телефон
        $userphone = phone_format($request->userphone);
        if ($userphone === false) {
            return response()->json(['success'=>false, 'reason'=>'bad phone']); //old 'reason'=>'notransport'
        }

        $client = null;
        if ($userphone) {
            $client = Client::where('phone', '=', $userphone)->first();
        }

        //нет клиента или он неактивен
        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']); //old 'reason'=>'notransport'
        }

        $code = mt_rand(11111, 99999);
        $text = 'Ваш код подтверждения на смену телефона: ' . $code;
        $isSmsSend = $this->sms->sendSms($userphone, $text);

        if (!$isSmsSend) {
            return response()->json(['success'=>false, 'reason'=>'notransport']);
        }

        return response()->json(['success'=>true, 'password'=>$code]);
    }

    // route /auction/sendCart
    public function sendCart(AuctionSendCartRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $url = 'http://kleopatra0707.com/getorderlanding';
        $data = [];

        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();
        if ($client) {
            $data['client_id'] = $client->id;
        }
        $data['phone'] = $userphone;
        $data['idorder'] = $request->orderid;
        $data['name']  = $request->name.' '.$request->lastname;
        $data['city']  = $request->city;
        $data['email']  = $request->email;
        $data['sum'] = $request->partnersum;
        $data['mess']  = $request->paymethod.'-'.$request->discount;
        $data['adres'] = $request->postoffice;
        $data['adv']  = 335;
        $data['auction'] = 1;
        //return response()->json($data);

        $response = $this->request($url, $data);

        if (ctype_digit($response)) {
            $out = ['success' => 1, 'orderId' => $response];
        } else {
            $out = ['success' => 0, 'reason' => $response];
        }

        return response()->json($out);
    }

    // route /auction/setDiscount
    public function setDiscount(AuctionSetDiscountRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $url =  'http://kleopatra0707.com/api/promocode';
        $data = [
            //'promocode' => $this->request->getPost('promocode'),
            //'site'      => $this->request->getPost('site'),
            //какие поля ??? у нас: строка промокода и сайт. тут userphone & code ?
            'userphone' => $request->userphone,
            'code' => $request->code,
        ];
        return response()->json($data);

        $response = $this->request($url, $data);

        return response()->json($response);
    }
    // route /auction/checkSum
    public function checkSum(AuctionClientLoginRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $url = '';//имхо на http://kleopatra0707.com/api/...~checksum  надо это создать
        //$data = $request->all();

        //$response = $this->request($url, $request->all());
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($url, $request->all());

        dd($response, $response->body());
        // обработать $response, вернуть что надо

    }
    // route /auction/getOrders
    public function getOrders(Request $request)
    {
        if ($request->key !== self::API_KEY) abort(404);
    }

    // post curl
    private function request(string $url, array $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
