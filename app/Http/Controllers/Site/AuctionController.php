<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ClientProduct;
use App\Models\ClientPaymentRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Aroma;
use App\Models\Brand;
use App\Models\Note;
use App\Models\Product;
use App\Models\Category;
use App\Models\Client;
use App\Models\ClientSumHistory;
use App\Http\Requests\AuctionClientRegisterRequest;
use App\Http\Requests\AuctionClientLoginRequest;
use App\Http\Requests\AuctionSendCartRequest;
use App\Http\Requests\AuctionSetDiscountRequest;
use App\Http\Requests\AuctionAddCommentRequest;
use App\Http\Requests\AuctionClientBalanceRequest;
use App\Http\Requests\AuctionAddClientPaymentRequest;
use App\Http\Requests\AuctionSendNotificationRequest;
use App\Modules\Clients\Core\Jobs\SaveClientSumHistory;
use App\Services\Sms\Sms;

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

        $addText = Setting::all(['auction_product_text_ru', 'auction_product_text_ua']);

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
                    'brand_id', 'auction_price', 'auction_price_min', 'auction_show', 'auction_new', 'auction_rating'
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
                    'brand_id', 'auction_price', 'auction_price_min', 'auction_show', 'auction_new', 'auction_rating'
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
                            'descr_addon' => $addText[0]['auction_product_text_ru'],
                        ],

                        'ua' => [
                            'name' => $item['name'],
                            'descr' => $item['description_ua'],
                            'descr_addon' => $addText[0]['auction_product_text_ua'],
                        ],
                        'images' => [
                            0 => $item['img'] ? url('/') . $item['img'] : null,
                            1 => $item['img2'] ? url('/') . $item['img2'] : null,
                            //2 => $item['img3'] ? url('/') . $item['img3'] : null,
                        ],
                        'p_price'  => $item['auction_price'],//max($price),
                        'p_priceD' => $item['auction_price_min'], //0,
                        'count' => 100,
                        'volume' => 100,
                        'art' => $item['product_variants'][2]['art'] ?? '',
                        'new' => $item['auction_new'],
                        'rating' => $item['auction_rating'],
                        'active' => $item['auction_show'],
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
            'referral_code' => mt_rand(111111, 999999),
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

        $url = 'http://kleopatra0707.com/getorderauction';
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
        $data['mess']  = $request->paymethod.'-'.$request->discount;
        $data['paymethod'] = $request->paymethod;
        $data['adres'] = $request->postoffice;
        $data['adv']  = 335;
        $data['auction'] = 1;
        $data['sum'] = 0;
        $data['pay_online'] = $request->pay_online;

        $auctionProducts=[];
        if (is_array($request->products)) {
            foreach ($request->products as $product) {
                $auctionProducts[] = [
                    'art' => $product['product_id'],
                    'qty' => $product['count'],
                    'price' => $product['price'],
                    'volume' => 100,
                ];

                $data['sum'] += (int)$product['count'] * (int)$product['price'];
            }
        }

        if (isset($request->discount) && ($request->discount) != 0) {
            $data['sum'] = $data['sum'] - $request->discount;
            $data['mess'] = 'Скидка на заказ '. $request->discount. ' грн.';
        }

        switch ($request->paymethod) {
            case 1:
                $data['mess'] .= '  Он-лайн оплата (Visa, Mastercard, Apple Pay, Google Pay) - доставка бесплатно';
                break;
            case 2:
                $data['mess'] .= ' Оплата наличными в терминале iBOX - доставка бесплатно';
                break;
            case 3:
                $data['mess'] .= ' Оплата на Новой Почте при получении заказа';
                break;
            case 4:
            default:
                $data['mess'] .= ' Другой способ оплаты, пусть менеджер подскажет';
        }

        $data['product'] = json_encode($auctionProducts);

        $response = json_decode($this->request($url, $data), true);

        $out = ['success' => 1, 'orderId' => $response['order_id']];

        if (isset($response['payment_link'])) {
            $out['paymentLink'] = $response['payment_link'];
        }

        return response()->json($out);
    }

    // route /auction/setDiscount
    public function setDiscount(AuctionSetDiscountRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $url =  'http://kleopatra0707.com/api/promocode';

        $data = [
            'promocode' => $request->promocode,
            'site' =>  'auction', //$request->site,
        ];

        $response = json_decode($this->request($url, $data), true);

        if (is_array($response) && count($response) && isset($response['procent'])) {
            return response()->json(['sum'=> $response['procent']]);
        }

        return response()->json(['success' => 0, 'reason' => 'Discount not found']);
    }

    // route /auction/checkSum
    public function checkSum(AuctionClientLoginRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();

        // клиента нет, или он неактивен false ... иначе возвращаем sum
        if (!$client || (!$client->active)) {
            $out = ['success'=>false, 'reason'=>'client not exist or not active'];
        } else {
            $out = ['sum' => $client->sum];
        }

        return response()->json($out);
    }

    // route /auction/getOrders
    public function getOrders(Request $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $url = 'http://kleopatra0707.com/api/auction/orders?';
        $data = [];
        $data['key'] = $request->key;

        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();
        if ($client) {
            $data['client_id'] = $client->id;
        }

        //нет клиента или он неактивен
        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']);
        }

        if (isset($request->order_id)) {
            $data['order_id'] = $request->order_id;
        }

        $out = json_decode(file_get_contents($url . http_build_query($data)));

        return response()->json($out); //dd($out);
    }

    // route /auction/getOrderedProducts
    public function getOrderedProducts(Request $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();

        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']);
        }

        //$url = 'http://kleopatra0707.com/api/auction/orders?';// ???
        $url = 'http://crm.kleopatra0707.com/auction/getOrders?';
        $data = [];
        $data['key'] = $request->key;
        $data['userphone'] = $userphone;

        $out = json_decode(file_get_contents($url . http_build_query($data)));
        //dd($out, gettype($out));

        $result = [];
        if (is_array($out) && count($out)) {
            foreach ($out as $items) {
                foreach ($items as $key => $value) {
                    if ($key === 'datereceived' && $value ==='0000-00-00 00:00:00') {
                        break;
                    }
                    if ($key === 'datereceived') {
                        $datereceived = $value;
                    }
                    if ($key === 'products') {
                        foreach ($value as $products) {
                            foreach ($products as $k => $v) {
                                if ($k === 'prodid') {
                                    $result[] = ['art' => $v, 'received' => $datereceived ];
                                }
                            }
                        }
                    }
                }
            }
        }

        //dd($result);//полная отдача, с дублями
        //dd(array_values(array_unique($result, SORT_REGULAR)));
        return response()->json(array_values(array_unique($result, SORT_REGULAR)));
    }

    // route /auction/getPayStatusList
    public function getPayStatusList(Request $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $out = [
            ['id' => 0, 'name'=> 'Оплата наложенным платежом'],
            ['id' => 1, 'name'=> 'Оплата онлайн'],
        ];

        return response()->json($out);
    }

    // route /auction/getOrderStatusList
    public function getOrderStatusList(Request $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $url = 'http://kleopatra0707.com/api/auction/statuses?key='.self::API_KEY;

        $out = json_decode(file_get_contents($url));

        return response()->json($out);
    }

    // route /auction/addComment
    public function addComment(AuctionAddCommentRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $data = [];
        $data['product_id'] = $request->product_id;
        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();
        if ($client) {
            $data['client_id'] = $client->id;
        }

        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']);
        }

        $auctionCommentPrice = Setting::find(1)->auction_comment_price;

        $clientProduct = ClientProduct::where('client_id', $data['client_id'])
            ->where('product_id', $data['product_id'])->first();

        // в табл ClientProduct добавить [client_id, product_id] если таких нет, и не добавлять если есть...
        if (!$clientProduct) {
            ClientProduct::create($data);
            $client->update(['sum'=> ($client->sum + $auctionCommentPrice) ]);
            SaveClientSumHistory::dispatch([
                'client_id' => $data['client_id'],
                'note' => 'Зачисление за добавление комментария на товар ID ' . $data['product_id'],
                'amount' => $auctionCommentPrice,
            ]);

            return response()->json(['success'=>true, 'reason'=>'Data was added successfully']);
        }

        return response()->json(['success'=>false, 'reason'=>'This data already exist']);
    }

    // route /auction/getClientBalance
    public function getClientBalance(AuctionClientBalanceRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();

        //нет клиента или он неактивен
        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']);
        }

        return response()->json(['success' => true, 'balance' => $client->sum ]);
    }

    // route /auction/addClientPaymentRequest
    public function addClientPaymentRequest(AuctionAddClientPaymentRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();

        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']);
        }

        $data = $request->only('sum', 'card', 'comment');
        $data['client_id'] = $client->id;
        ClientPaymentRequest::create($data);

        return response()->json(['success'=>true, 'reason'=>'Data was added in table' ]);
    }

    // route /auction/getSettings
    public function getSettings(Request $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $auctionCommentPrice = Setting::all(['auction_comment_price', 'auction_register_price', 'auction_partner_price']); //in settings only 1 row
        //dd($auctionCommentPrice[0]['auction_comment_price']);

        return response()->json([
            'comment_price'  => $auctionCommentPrice[0]['auction_comment_price'],
            'register_price' => $auctionCommentPrice[0]['auction_register_price'],
            'partner_price'  => $auctionCommentPrice[0]['auction_partner_price'],
        ]);
    }

    // route /auction/getClientDetail (get request with key & userphone)
    // с AuctionClientLoginRequest ошибки нет; тут нужен FormRequest с userphone и key
    public function getClientDetail(AuctionClientLoginRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();

        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']);
        }

        $clientTransactions = ClientSumHistory::where('client_id', $client->id)->get(['note', 'amount', 'created_at']);

        $out = [
            'client_detail' => [
                'first_name' => $client->first_name,
                'last_name' => $client->last_name,
                'phone' => $client->phone,
                'sum' => $client->sum,
                'sum_bonus' => $client->sum_bonus,
                'referral_code' => $client->referral_code,
                'active' => $client->active,
                'transactions' => $clientTransactions,
            ],

        ];

        return response()->json($out);
    }
    // route /auction/getClientPaymentRequestList (get request with key & userphone)
    public function getClientPaymentRequestList(AuctionClientLoginRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();

        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']);
        }

        $clientPaymentRequests = ClientPaymentRequest::where('client_id', $client->id)
            ->get(['sum', 'comment', 'card', 'paid', 'created_at'])
            ->toArray(); // dd($clientPaymentRequests);

        return response()->json($clientPaymentRequests);
    }

    // route /auction/sendNotification (post request with key, userphone & text)
    public function sendNotification(AuctionSendNotificationRequest $request)
    {
        if ($request->key !== self::API_KEY) abort(404);

        $userphone = phone_format($request->userphone);
        $client = Client::where('phone', $userphone)->first();

        if (!$client || (!$client->active)) {
            return response()->json(['success'=>false, 'reason'=>'client not exist or not active']);
        }

        $smsSend = $this->sms->sendSms($userphone, trim($request->text));

        if (!$smsSend) {
            return response()->json(['success'=>false, 'reason'=>'no transport']);
        }

        return response()->json(['success'=>true, 'message'=>'sms ok']);
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
