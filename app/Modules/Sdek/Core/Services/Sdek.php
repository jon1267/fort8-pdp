<?php

namespace App\Modules\Sdek\Core\Services;

class Sdek
{
    const NEW_TOKEN_LINK = 'https://api.cdek.ru/v2/oauth/token?';
    const ORDER_REGISTER_LINK  = 'https://api.cdek.ru/v2/orders?';
    const ORDER_INFO_LINK  = 'https://api.cdek.ru/v2/orders/';
    const BARCODE_REQUEST_LINK = 'https://api.cdek.ru/v2/print/barcodes';
    const SDEK_DELIVERY_POINTS = 'http://api.cdek.ru/v2/deliverypoints?';
    const SDEK_REGION_CODES = 'https://api.cdek.ru/v2/location/regions?';
    const SDEK_CITY_CODES = 'https://api.cdek.ru/v2/location/cities/?';
    const RECEIPT_REQUEST_LINK = 'https://api.cdek.ru/v2/print/orders';

    const CLIENT_ID = 'xwR6nbL7hIn0IcyFGtwpvVgtdRirRjh0';
    const CLIENT_SECRET = 'n4zos9FLqglrhkDTCNKRsIHXw8Ha5VLw';

    private $token;

    // $data[] example for order type=1 "internet shop" sklad - dver
    public function orderData137()
    {
        return [
            //'number' => 'ddOererre7450813980068',// надо какой-то номер но токо не именно этот...
            'comment' => 'Заказ 1 инет-магазин, tarif = 137, склад - дверь до 30 кг',
            'delivery_recipient_cost' => ['value' => 50],
            'delivery_recipient_cost_adv' => ['sum' => 3000, 'threshold' => 200],
            'from_location' => [
                'code' => 44,
                //'fias_guid' => "",
                //'postal_code' => "",
                //'longitude' => "",
                //'latitude' => "",
                //'country_code' => "",
                //'region' => "",
                //'sub_region' => "",
                'city' => "Москва",
                //'kladr_code' => "",
                'address' => "пр. Ленинградский, д.4",
             ],
            'to_location' => [
                'code' => 270,
                //'fias_guid' => "",
                //'postal_code' => "",
                //'longitude' => "",
                //'latitude' => "",
                //'country_code' => "",
                //'region' => "",
                //'sub_region' => "",
                'city' => "Новосибирск",
                //'kladr_code' => "",
                'address' => "ул. Блюхера, 32"
            ],
            'packages' => [
                [
                    'number' => "bar-001",
                    'comment' => "Упаковка",
                    'height' => 10,
                    'items' => [
                        [
                            'ware_key' => "00055",
                            'payment' => ['value' => 3000],
                            'name' => "Товар",
                            'cost' => 300,
                            'amount' => 2,
                            'weight' => 700,
                            'url' => "www.item.ru"
                        ]
                    ],
                    'length' => 10,
                    'weight' => 4000,
                    'width' => 10
                ]
            ],
            'recipient' => [
                'name' => "Иванов Иван",
                'phones' => [ ['number' => "+79134637228"] ]
            ],
            'sender' => [ 'name' => "Петров Петр"],
            'services' =>  [ [ 'code' => "DELIV_WEEKEND" ] ],
            'tariff_code' => 137,
            'print' => 'barcode', //если это указать то сразу формируется pdf с ШК места
        ];
    }

    // $data[] example for order type=1 "internet shop" sklad - sklad
    public function orderData136()
    {
        return [
            //'number' => 'ddOererre7450813980068',// надо какой-то номер но токо не именно этот...
            'tariff_code' => 136,
            'comment' => 'Заказ 2 инет-магазин, tarif = 136, склад - склад до 30 кг',
            'delivery_point'=> 'MSK203',// без этого сздавался с ошибками
            'delivery_recipient_cost' => ['value' => 50],
            'delivery_recipient_cost_adv' => ['sum' => 3000, 'threshold' => 200],
            'from_location' => [
                'code' => 44,
                'city' => "Москва",
                'address' => "пр. Ленинградский, д.4",
            ],
            /*'to_location' => [
                'code' => 270,
                'city' => "Новосибирск",
                'address' => "ул. Блюхера, 32"
            ],*/
            'packages' => [
                [
                    'number' => "bar-001",
                    'comment' => "Упаковка",
                    'height' => 10,
                    'items' => [
                        [
                            'ware_key' => "00055",
                            'payment' => ['value' => 3000],
                            'name' => "Товар",
                            'cost' => 300,
                            'amount' => 2,
                            'weight' => 700,
                            'url' => "www.item.ru"
                        ]
                    ],
                    'length' => 10,
                    'weight' => 4000,
                    'width' => 10
                ]
            ],
            'recipient' => [
                'name' => "Иванов Иван",
                'phones' => [ ['number' => "+79134637228"] ]
            ],
            'sender' => [ 'name' => "Петров Петр"],
            'services' =>  [ [ 'code' => "DELIV_WEEKEND" ] ],
            'print' => 'barcode',
        ];

    }

    // data needed for get token
    public function tokenData()
    {
        return [
            'grant_type' =>'client_credentials',
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET
        ];
    }

    // request on get bearer token (need success result for all other requests!)
    public function tokenRequest()
    {
        $curl = CurlSender::init(self::NEW_TOKEN_LINK);//curl init
        $res  = $curl->post($this->tokenData(),0);//curl request
        $status = $curl->getStatus();//get last curl status
        $error = $curl->getError();//get last curl error
        $curl->close();
        $result = json_decode($res);



        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    // register new order Sdek, with any type={1,2} or 'internet shop' or 'delivery'
    // depend on $orderData (or data for 'internet shop' or data for 'delivery')
    public function orderRequest($orderData)
    {
        $curl = CurlSender::init(self::ORDER_REGISTER_LINK);
        $data = json_encode($orderData);
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken()
        ]);
        $res = $curl->post($data,1);
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();
        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    public function orderInfoTrack($sdek_number)
    {
        $curl = CurlSender::init(self::ORDER_INFO_LINK.'?'.'cdek_number='.$sdek_number);
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken()
        ]);
        $res = $curl->get();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        return $result;
    }

    public function orderTrack($sdek_number)
    {
        $curl = CurlSender::init(self::ORDER_INFO_LINK.'?'.'cdek_number='.$sdek_number);
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken()
        ]);
        $res = $curl->get();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        //return $result->entity->statuses;//last point where data from Sdek
        $lastStatus = array_pop($result->entity->statuses);
        $code6 = ['ACCEPTED', 'CREATED'];
        $code7 = [
            'ACCEPTED_AT_RECIPIENT_CITY_WAREHOUSE', 'ACCEPTED_AT_PICK_UP_POINT'
        ];
        $code9 = ['DELIVERED'];
        $code102 = ['NOT_DELIVERED', 'INVALID'];

        $code = 6;

        // прибыл на отделение, хранение
        if (in_array($lastStatus->code, $code7))
        {
            $code = 7;
        }

        // получение (успешно доставлено, вручено адресату.конечный статус)
        if (in_array($lastStatus->code, $code9))
        {
            $code = 9;
        }

        // возврат (отказ от покупки, возврат в ИМ. конечный статус)
        if (in_array($lastStatus->code, $code102))
        {
            $code = 102;
        }

        return ['code' => $code ?: 0, 'status' => $lastStatus->name ?: ''];
    }

    // get fool info about created order. Need order uuid.
    public function orderInfo($orderUuid)
    {
        $curl = CurlSender::init(self::ORDER_INFO_LINK . $orderUuid);
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken()
        ]);
        $res = $curl->get();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    // get (one) order last status (if success, return stdClass Object)
    public function orderLastStatus(string $orderUuid)
    {
        $statusInfo = $this->orderInfo($orderUuid);
        return array_pop($statusInfo->entity->statuses);
    }

    // get all order statuses (if success, return array of stdClass Objects)
    public function orderAllStatuses(string $orderUuid)
    {
        $statusInfo = $this->orderInfo($orderUuid);
        return $statusInfo->entity->statuses ?: null;
    }

    // delete order
    public function orderDelete($orderUuid)
    {
        $curl = CurlSender::init(self::ORDER_INFO_LINK . $orderUuid);
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken()
        ]);
        $res = $curl->delete();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    // get list of delivery points
    public function deliveryPoints($data)
    {
        $curl = CurlSender::init(self::SDEK_DELIVERY_POINTS . http_build_query($data));
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken(),
        ]);
        $res = $curl->get();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();



        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    // возвращает массив (сокращенный до ['code','name','address']) офисов СДЕК по почтовому индексу Города(!)
    // тут даются индексы типа '308000'-Белгород, '350000'-Краснодар, '190000' Санкт-Петербург итд. Если дать
    // обычный почтовый индекс типа 121609 - все равно вернется список офисов SDEK для ВСЕГО этого города.
    public function getPostOffices($index='')
    {
        if ($index == '') return [];

        $offices = [];
        $request =  $this->deliveryPoints(['postal_code' => $index]);



        if (count($request)) {

            foreach ($request as $key => $post) {

                if (isset($post->code)) {
                    $offices[$key]['code'] = $post->code;
                    $offices[$key]['name'] = $post->name;
                    $offices[$key]['address_comment'] = isset($post->address_comment) ? $post->address_comment : null;
                    $offices[$key]['city_code'] = $post->location->city_code;
                    $offices[$key]['address_full'] = $post->location->address_full;
                    $offices[$key]['postal_code'] = $post->location->postal_code;
                }


            }
        }

        return $offices;
    }

    //get list of region cods (RU)
    public function getSdekRegionCodes()
    {
        $data = ['country_codes'=>'RU']; //if $data=[]; all regions SDEK in the world (us,ru,turkey, & so on)
        $curl = CurlSender::init(self::SDEK_REGION_CODES . http_build_query($data));
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken(),
        ]);
        $res = $curl->get();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    // возвращает для полностью и правильно указанного города все коды SDEK для этого города:
    // код города, код региона, широту, долготу и массив почтовых индексов города (все по sdek)
    public function getSdekCityCodes( $city = 'Белгород') {
        $data=['city'=> $city];
        $curl = CurlSender::init(self::SDEK_CITY_CODES . http_build_query($data));
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken(),
        ]);
        $res = $curl->get();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    // список городов SDEK по 2 значному коду страны, можно с кодом региона SDEK (можно без)
    //$data = ['country_codes' => 'RU', 'region_code' => 16, 'size' => 1000 ,'page' => 1];
    //$data = ['country_codes' => 'RU', 'size' => 1000 ,'page' => 0];
    public function getSdekAllCities($data)
    {
        $curl = CurlSender::init(self::SDEK_CITY_CODES . http_build_query($data));
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken(),
        ]);
        $res = $curl->get();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        $cities = [];
        if (count($result)) {
            foreach ($result as $key => $value) {
                $cities[$key]['code'] = $value->code;
                $cities[$key]['city'] = $value->city;
                $cities[$key]['region'] = $value->region;
                $cities[$key]['region_code'] = $value->region_code;
                $cities[$key]['sub_region'] = $value->sub_region;
            }
        }

        return $cities;
    }

    // заказ штрих кода места
    public function barcodeRequest($orderUuid)
    {
        $curl = CurlSender::init(self::BARCODE_REQUEST_LINK);
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken()
        ]);
        $data = json_encode([
            'orders' => ['cdek_number' => $orderUuid ],// orders can be many
            'copy_count' => 1,
            'format' => 'A5'
        ]);
        $res = $curl->post($data, 1);
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    // получение ШК места {$barcodeUuid == $barcode->entity->uuid - успешный результат $this->barcodeRequest(...)}
    public function barcodeReceive(string $barcodeUuid)
    {
        $curl = CurlSender::init(self::BARCODE_REQUEST_LINK .'/'. $barcodeUuid);
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken(),
        ]);
        $res = $curl->get();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    //public function printBarcode(string $pdfLink)
    public function printPdf(string $pdfLink)
    {
        $curl = CurlSender::init($pdfLink);
        $curl->setHeaders([
            'Content-Type: application/pdf',
            'Authorization: Bearer '. $this->getToken()
        ]);

        header("Content-type: application/pdf");

        print $curl->get();
        $curl->close();

        exit;
    }

    //public function printBarcode(string $pdfLink)
    public function pdf($pdfLink)
    {
        $curl = CurlSender::init($pdfLink);
        $curl->setHeaders([
            'Content-Type: application/pdf',
            'Authorization: Bearer '. $this->getToken()
        ]);

        $result = $curl->get();
        $curl->close();

        return $result;
    }

    /**
     * Формирование квитанции (pdf) к заказу. Параметр: или массив в к-ром 1 или больше
     * (но не более 100 - документация sdek api) orders Uuid, или строка с одним order_uuid.
     * Массив заказов $orders предполагается в виде:
     *   $orders = [
     *       ['order_uuid' => '72753031-7987-4d53-a358-8e52771bb55b'],
     *       ['order_uuid' => '72753031-31c8-4141-8fbc-c282e5e71781'],
     *       ['order_uuid' => '72753031-84ba-4796-b20d-7d0d478eb035'],
     *   ];
     * Если заказ один, то можно параметром указать строку с order_uuid
     *
     * @param $orders
     * @return mixed
     **/
    public function receiptRequest($orders)
    {
        $curl = CurlSender::init(self::RECEIPT_REQUEST_LINK);
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken()
        ]);

        // 2-а if, чтоб параметр мог быть $orders[] и (string) $orderUuid
        $data = '';
        if (is_string($orders)) {
            $data = json_encode(['orders' => ['order_uuid' => $orders,], 'copy_count' => 2,]);
        }

        if (is_array($orders)) {
            $data = json_encode(['orders' => $orders, 'copy_count' => 2,]);
        }

        $res = $curl->post($data,1);
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    // Получение квитанции к заказу. Если success и pdf сформировался (время...), то в ответе вернется
    // объект $entity в котором будет $entity->url - ссылка на pdf файл. тут только ссылка. печать отдельно.
    // параметр - строка uuid ($entity->uuid а не $request->...) вернувшаяся из receiptRequest([])
    public function receiptReceive(string $receiptUuid)
    {
        $curl = CurlSender::init(self::RECEIPT_REQUEST_LINK .'/'. $receiptUuid);
        $curl->setHeaders([
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->getToken(),
        ]);
        $res = $curl->get();
        $status = $curl->getStatus();
        $result = json_decode($res);
        $error = $curl->getError();
        $curl->close();

        if (!in_array($status, [200,201, 202, 204, 205])) {
            return ['result' => $result, 'status' => $status, 'error' => $error];
        }

        return $result;
    }

    // this return bearer token, or null
    private function getToken()
    {
        $token = $this->tokenRequest();

        //var_dump($token);

        if ($token AND isset($token->access_token)) {
        	return $this->token = $token->access_token ?: null;
        }

        return null;
    }
}
