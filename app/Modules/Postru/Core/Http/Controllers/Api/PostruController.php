<?php

namespace App\Modules\Postru\Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Http\Request;
use App\Modules\Postru\Core\Services\PostRu;
use LapayGroup\RussianPost\Enum\PostType;
use LapayGroup\RussianPost\Providers\OtpravkaApi;
use LapayGroup\RussianPost\AddressList;
use LapayGroup\RussianPost\Entity\Order;
use App\Modules\Postru\Core\Http\Requests\CreateOrderRequest;
use App\Models\PostruRegisters;
use LapayGroup\RussianPost\ParcelInfo;

class PostruController extends Controller
{

    /**
     * возвращается одно(!) ОПС (отделение почт. связи) по правильному почтовому индексу (типа 399058)
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function office(Request $request)
    {
        $zip = trim($request->zip);

        if (!isset($zip) || is_null($zip) || $zip==='') {
            return [];
        }

        $office = $this->getOffice($zip);//dd($office, gettype($office));
        $rows = [];
        $row['region'] = $row['district'] = $row['settlement'] = $row['address-source'] = $row['postal-code'] = $row['desc'] ='';

        foreach ($office as $key => $value) {
            if ($key === 'region')  $row['region'] = $value;
            if ($key === 'district')  $row['district'] = $value;
            if ($key === 'settlement')  $row['settlement']= $value;
            if ($key === 'address-source')  $row['address-source'] = $value;
            if ($key === 'postal-code')  $row['postal-code'] = $value;
            if ($key === 'desc')  $row['desc'] = $value;
            // "type-code": "ГОПС", "СОПС", "Почтомат" ?
        }

        if ($row['region'] !=='' && $row['settlement'] !== '' &&  $row['address-source'] !=='') {
            $rows[] = $row['region'].', '.$row['district'].', '.$row['settlement'].', '.$row['address-source'].', ('.$row['postal-code'].')';
        } elseif ($row['desc'] !== '') {
            $rows[] = $row['desc'];
        }

        return response()->json($rows); //print json_encode($rows);
    }

    public function offices(Request $request)
    {
        $city = trim($request->city);
        $keyword = trim($request->keyword);

        if (!isset($city) || is_null($city) || $city==='') {
            return [];
        }

        $indexes = (new PostRu())->getPostIndexes(['settlement'=>$city]);//dd($indexes);

        $rows = [];
        $row['region'] = $row['district'] = $row['settlement'] = $row['address-source'] = $row['postal-code'] = '';
        if (count($indexes)) {
            foreach ($indexes as $index) {
                $office = $this->getOffice($index); //dd($office, gettype($office));

                foreach ($office as $key => $value) {

                    if ($key === 'region')  $row['region'] = $value;
                    if ($key === 'district')  $row['district'] = $value;
                    if ($key === 'settlement')  $row['settlement']= $value;
                    if ($key === 'address-source')  $row['address-source'] = $value;
                    if ($key === 'postal-code')  $row['postal-code']= $value;
                }

                $emptyString = ($row['region'] ==='' && $row['settlement'] === '' &&  $row['address-source'] ==='');
                if (!$emptyString && ($keyword && strpos(mb_strtolower($row['address-source'], 'UTF-8'), mb_strtolower($keyword, 'UTF-8')) !== false) || (!$keyword && !$emptyString)) {
                    $rows[] = $row['region'].', '.$row['district'].', '.$row['settlement'].', '.$row['address-source'].', ('.$row['postal-code'].')';
                }
                $row['region'] = $row['district'] = $row['settlement'] = $row['address-source'] = $row['postal-code'] = '';
            }
        }

        //dd($rows);
        return response()->json($rows);
    }

    private function getOffice($zip)
    {
        $zip = trim($zip);
        return (new PostRu())->getOfficeByIndex($zip); //dd($office, gettype($office));
    }

    /**
     * this method use https://github.com/lapaygroup/RussianPost
     * Нормализация адреса. Если успех, вернет массив нормал. адреса,
     * с ключами, к-рые дает сама почта россии.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function address(Request $request)
    {
        $address = trim($request->q);
        $result = [];

        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');
            $addressList = new AddressList();
            $addressList->add($address);

            $result = $otpravkaApi->clearAddress($addressList);
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        // if address not validated, return empty array
        if(is_array($result[0]) && isset($result[0]['validation-code']) && $result[0]['validation-code']!=='VALIDATED') {
            //return response()->json([]);
            return response()->json(['error' => true, 'message' => 'address is not normalised by PostRu']);
        }

        return response()->json($result); //echo '--- нормализация аддреса ---<br>'.'<pre>'.print_r($result,1).'</pre>';
    }

    // точная копия address, но не для постмена, а для внутр. исполз.
    public function addressN(string $address)
    {
        $result = [];
        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');
            $addressList = new AddressList();
            $addressList->add($address);

            $result = $otpravkaApi->clearAddress($addressList);
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        // if address not validated, return empty array
        if(is_array($result[0]) && isset($result[0]['validation-code']) && $result[0]['validation-code']!=='VALIDATED') {
            //return response()->json([]);
            return ['error' => true, 'message' => 'адрес не нормализован почтой Росии'];
        }

        return $result; //echo '--- нормализация аддреса ---<br>'.'<pre>'.print_r($result,1).'</pre>';

    }

    /**
     * https://github.com/lapaygroup/RussianPost#create_orders_v2
     * (просто создание заказа v2, без помещения в партию...)
     * @param CreateOrderRequest $request
     */
    public function createOrder(CreateOrderRequest $request)
    {
        $config = include 'lapaygroup_config.php';
        $result = [];

        try {
            $otpravkaApi = new OtpravkaApi($config);

            $orders = [];
            $order = new Order();
            $order->setIndexTo(trim($request->index_to));// 115551
            $order->setPostOfficeCode(trim($request->postoffice_code));//109012
            $order->setGivenName(trim($request->given_name));//'Иван'// имя получателя
            $order->setHouseTo(trim($request->house_to));//'92'
            $order->setCorpusTo(trim($request->corpus_to));//'3'
            $order->setMass(trim($request->mass));// 1000
            $order->setOrderNum(trim($request->order_num)); //'2'
            $order->setPlaceTo(trim($request->place_to));//'Москва'
            $order->setRecipientName(trim($request->recipient_name));//'Иванов Иван'
            $order->setRegionTo(trim($request->region_to));//'Москва'
            $order->setStreetTo(trim($request->street_to));//'Каширское шоссе'
            $order->setRoomTo(trim($request->room_to));//'1'
            $order->setSurname(trim($request->surname));//'Иванов'
            $orders[] = $order->asArr();

            $result = $otpravkaApi->createOrdersV2($orders);

        }

        catch (\InvalidArgumentException $e) {
            dd('Invalid Argument Exception: '. $e->getMessage());// Обработка ошибки заполнения параметров
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        return response()->json($result); //echo '--- создание заказа v2 ---<br>'.'<pre>'.print_r($result,1).'</pre>';
    }

    public function createOrderN(array $orderData)
    {
        $result = [];
        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');

            $orders = [];
            $order = new Order();
            $order->setIndexTo(trim($orderData['index_to']));// 115551
            $order->setPostOfficeCode(trim($orderData['postoffice_code']));//109012
            $order->setGivenName(trim($orderData['given_name']));//'Иван'// имя получателя
            $order->setHouseTo(trim($orderData['house_to']));//'92'
            $order->setCorpusTo(trim($orderData['corpus_to']));//'3'
            $order->setMass(trim($orderData['mass']));// 1000
            $order->setOrderNum(trim($orderData['order_num'])); //'2'
            $order->setComment(trim($orderData['comment']));
            $order->setTelAddress(trim($orderData['phone']));
            $order->setPlaceTo(trim($orderData['place_to']));//'Москва'
            $order->setRecipientName(trim($orderData['recipient_name']));//'Иванов Иван'
            $order->setRegionTo(trim($orderData['region_to']));//'Москва'
            $order->setStreetTo(trim($orderData['street_to']));//'Каширское шоссе'
            $order->setRoomTo(trim($orderData['room_to']));//'1'
            $order->setSurname(trim($orderData['surname']));//'Иванов'
            //$order->setFragile($orderData['fragile']); //признак хрупкое (в пдфке добавляет 4 красные рюмики)
            //////$order->setItems($orderData['goods']);
            $order->setInsrValue($orderData['insr-value']); // объявленная стоимость коп.
            $order->setPayment($orderData['payment']); // наложенным платежем
            //$order->setMailCategory('WITH_DECLARED_VALUE'); //для объявлен. стоим. без Налож Плат.
            //$order->setMailCategory('WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY'); //для объявлен. стоим. и Налож Плат.
            $order->setMailCategory($orderData['mail-category']);
            $order->setMailType($orderData['mail-type']);
            $orders[] = $order->asArr(); //dd($orders);

            $result = $otpravkaApi->createOrdersV2($orders);

        }

        catch (\InvalidArgumentException $e) {
            dd('Invalid Argument Exception: '. $e->getMessage());// Обработка ошибки заполнения параметров
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        return $result;
    }

    /**
     * Удаление просто созданного, но не помещенного в партию заказа.
     * Если помещен, то он уже невидим, и удалить просто так нельзя. Удалять из партии.
     * @param $orderIds
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOrders($orderIds)
    {
        $config = include 'lapaygroup_config.php';
        $data = is_array($orderIds) ? $orderIds : [$orderIds];
        $result = [];

        try {
            $otpravkaApi = new OtpravkaApi($config);
            $result = $otpravkaApi->deleteOrders($data);
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        //echo '--- создание заказа v2 ---<br>'.'<pre>'.print_r($result,1).'</pre>';
        return response()->json($result);
    }

    /**
     * Создание партии из N заказов с использ. библиотеки LapayGroup
     * (заказ уже создан, есть его id, из этого id создается партия)
     * (https://github.com/lapaygroup/RussianPost)
     * (createBatch и createBatchN очень похожи ... хочется отрефакторить в один код)
     * @param mixed $request
     * @param bool $postman
     * @param mixed $orderIds
     * @return mixed
     */
    public function createBatch(?Request $request, $postman = true, $orderIds = null)
    {
        if ($postman) {
            $data = [$request->order_id];
        } else {
            $data = is_array($orderIds) ? $orderIds : [$orderIds];
        }

        $result = [];

        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');
            $result = $otpravkaApi->createBatch($data);
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        //echo '--- создание партии ---<br>'.'<pre>'.print_r($result,1).'</pre>';
        return $postman ?  response()->json($result) : $result;
    }

    // $orderIds или массив id заказов, типа ['310115153', '310115157', '115322331']
    // или один id заказа (строка)
    public function createBatchN($orderIds)
    {
        $config = include 'lapaygroup_config.php';
        $data = is_array($orderIds) ? $orderIds : [$orderIds];
        $result = [];

        try {
            $otpravkaApi = new OtpravkaApi($config);
            $result = $otpravkaApi->createBatch($data);
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        //echo '--- создание партии ---<br>'.'<pre>'.print_r($result,1).'</pre>';
        return $result;
    }

    // в уже созд. партию помещает уже созд. заказ, по его id (почты россии)
    public function moveOrdersToBatch(string $batch, $orderIds)
    {
        $data = is_array($orderIds) ? $orderIds : [$orderIds];

        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');
            $result = $otpravkaApi->moveOrdersToBatch($batch, $data);
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        return $result;
    }

    // возвращает pdf файл с формой Ф103 для указанной партии ($bachName).
    // метод printF103 работает только если для партии выполнялся метод checkin (проверка партии $bachName )
    // в generateDocF103() возможны 2е константы: OtpravkaApi::PRINT_FILE  OtpravkaApi::DOWNLOAD_FILE
    public function printF103($bachName, $checkin = false)
    {

        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');
            if ($checkin) {
                $otpravkaApi->sendingF103form($bachName); //$otpravkaApi->sendingF103form($bachName, true);//с онлайн балансом
            }
            return $otpravkaApi->generateDocF103($bachName, OtpravkaApi::PRINT_FILE);
        }
        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }
    }

    // Генерация печатных форм заказа по id заказа. (ф7п это пдф с адресами от кого, и кому/куда)
    // Возвращает pdf файл, который может содержать в зависимости от типа отправления:
    // форму ф7п (посылка, посылка-онлайн, бандероль, курьер-онлайн) или
    // форму Е-1 (EMS, Бизнес курьер, Бизнес курьер экспресс) или конверт (письмо заказное).
    public function printPdfForms($orderId, $batchCreated = false)
    {
        $config = include 'lapaygroup_config.php';

        try {
            $otpravkaApi = new OtpravkaApi($config);
            return $otpravkaApi->generateDocOrderPrintForm($orderId, OtpravkaApi::PRINT_FILE, $batchCreated, new DateTimeImmutable('now'), OtpravkaApi::PRINT_TYPE_THERMO);
        }
        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }
    }

    // Запрос данных о заказах в партии
    public function getOrdersInBatch($batch)
    {
        $config = include 'lapaygroup_config.php';
        $result = [];

        try {
            $otpravkaApi = new OtpravkaApi($config);
            $result = $otpravkaApi->getOrdersInBatch($batch); // Может вызываться с фильтрами
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        return response()->json($result);
    }

    // Удаление заказов (по id заказа), которые уже были добавлены в партию.
    public function deleteOrdersInBatch($orderIds)
    {
        $config = include 'lapaygroup_config.php';
        $data = is_array($orderIds) ? $orderIds : [$orderIds];
        $result = [];

        try {
            $otpravkaApi = new OtpravkaApi($config);
            $result = $otpravkaApi->deleteOrdersInBatch($data);
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        return  $result; //response()->json($result);
    }

    // Удаление заказа (по баркоду), которые уже были добавлены в партию.
    // (не помещенные в партию заказы, по баркоду не находятся. поиск всегда дает [] )
    public function deleteOrdersInBatchByBarcode($barcode)
    {
        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');
            $resultFind = $otpravkaApi->findOrderByRpo($barcode); //dd($resultFind);

            if(!count($resultFind)) {
                return ['error' => true, 'message' => 'for barcode '. $barcode .' nothing not found'];
            }

            return $this->deleteOrdersInBatch($resultFind[0]['id']);//dd($this->deleteOrdersInBatch($resultFind[0]['id']));
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

    }

    /**
     * поиск всех партий (забыт номер партии и тп.)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllBatches()
    {
        $config = include 'lapaygroup_config.php';

        $result = [];
        try {
            $otpravkaApi = new OtpravkaApi($config);
            $result = $otpravkaApi->getAllBatches(); // Может вызываться с фильтрами
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }

        return response()->json($result);
    }

    public function createOrUpdateRegister(array $data)
    {
        $barcode     = $data['barcode'];
        $orderId     = $data['order_id'];
        $todayRecord = PostruRegisters::where('checkin', 0)->first();

        if ($todayRecord) {
            $batchName = $todayRecord->name;
            $this->moveOrdersToBatch($batchName, $orderId);

            $barcodes = json_decode($todayRecord->barcodes);
            $barcodes[] = $barcode;
            $barcodes = json_encode($barcodes);
            $todayRecord->update(['barcodes' => $barcodes, 'checkin' => 0]);

        } else {
            $result = $this->createBatchN($orderId); //dd($result, gettype($result));
            if (isset($result['batches'][0]['batch-status']) && ($result['batches'][0]['batch-status'] === 'CREATED') ) {
                PostruRegisters::create([
                    'name' => $result['batches'][0]['batch-name'],
                    'barcodes' => json_encode([$barcode]),
                ]);
            }
        }

        return [
            'ttn' => $barcode,
            'orderId' => $orderId,
        ];
    }

    // реализация того, что нам нужно в итоге ... //public function index(array $data)
    public function index(Request $request)
    {
        $address = $request->address; // $data['address'];
        $normAddress = $this->addressN($address);

        if (isset($normAddress['error']) && $normAddress['error']) {
            return ['error' => true, 'message' => 'адрес не нормализован почтой Росии'];
        }

        $order['index_to'] = $normAddress[0]['index'] ?? null; // почт.инд. получателя
        $order['postoffice_code'] = '308011'; // ~ почт.инд. отправляещего ОПС '308009','308011'
        $order['given_name'] = 'ИП Успешный Игорь'; // ~ имя (фио) отправителя посылки
        $order['house_to']  = $normAddress[0]['house'] ?? null; // № дома получателя
        $order['corpus_to'] = $normAddress[0]['corpus'] ?? null; // корпус получателя
        $order['place_to']  = $normAddress[0]['place'] ?? null; // город получателя
        $order['mass']      = $request->mass ? $request->mass : 300; // грамм (вес посылки)
        $order['order_num'] = $request->orderid; //наш номер заказа на посылку, навверное можно= id заказа магазина
        $order['recipient_name'] = $request->name; // ФИО получателя
        $order['region_to'] = $normAddress[0]['region'] ?? null; // область получателя
        $order['street_to'] = $normAddress[0]['street'] ?? null; // улица получателя
        $order['room_to']   = $normAddress[0]['room'] ?? null; // квартира получателя
        $order['surname']   = $request->name; // фамилия получателя
        $order['payment']   = ($request->sum_payment == 0) ? null : $request->sum_payment * 100; // наложенный платеж
        $order['mail-category'] = ($request->sum_payment == 0) ? 'WITH_DECLARED_VALUE' : 'WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY';
        $order['insr-value'] = $request->sum * 100; // объявленная стоимость коп.
        $order['phone']      = $request->phone; //тел. получателя
        $order['comment']    = 'н/з '. $request->orderid; // комментарий
        $order['mail-type']  = ($request->type_id == 1) ? 'POSTAL_PARCEL' : 'PARCEL_CLASS_1'; // вид РПО

        $postOrder = $this->createOrderN($order); //dd($postOrder, $postOrder['errors'][0]['error-codes']);

        if (isset($postOrder['errors'])) {
            return [
                'error' => true,
                'message' => $this->getPostruOrderErrors($postOrder),
            ];
        }

        return $this->createOrUpdateRegister([
            'barcode'  => $postOrder['orders'][0]['barcode'],
            'order_id' => $postOrder['orders'][0]['result-id'],
        ]);
    }

    public function checkin()
    {
        $todayRecord = PostruRegisters::where('checkin', 0)->first();

        if ( ! $todayRecord) {

            $todayRecord = PostruRegisters::where('checkin', 1)->orderBy('created_at', 'DESC')->first();

            if ( ! $todayRecord) {
                return ['error' => true, 'message' => 'текущая запись реестра почты России не найдена'];
            }

            $pdf = $this->printF103($todayRecord->name, false);
            if (is_object($pdf) && !$pdf->getError()) {
                $todayRecord->update(['checkin' => 1]);
                header("Content-type: application/pdf");
                print $pdf->getStream()->getContents();
            }

            return ['error' => true, 'message' => 'ошибка создания F103 pdf документа'];
        }

        if ($todayRecord->created_at->format('Y-m-d') !== Carbon::today()->format('Y-m-d')) {
            $this->changeBatchDay($todayRecord->name);
        }

        $pdf = $this->printF103($todayRecord->name, true);
        if (is_object($pdf) && !$pdf->getError()) {
            $todayRecord->update(['checkin' => 1]);
            header("Content-type: application/pdf");
            print $pdf->getStream()->getContents();
        }

        return ['error' => true, 'message' => 'ошибка создания F103 pdf документа'];
    }

    public function printPdfByBarcode($barcode)
    {
        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');
            $resultFind = $otpravkaApi->findOrderByRpo($barcode);

            if(!count($resultFind)) {
                return ['error' => true, 'message' => 'for barcode '. $barcode .' nothing not found'];
            }

            $pdf = $this->printPdfForms($resultFind[0]['id'], true);

            if (is_object($pdf) && !$pdf->getError()) {
                header("Content-type: application/pdf");
                print $pdf->getStream()->getContents();
                die();
            }
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }
    }

    // Расчет стоимости пересылки (Упрощенная версия)
    // и времени доставки для POSILKA & POSILKA_ONE_CLASS
    public function shippingCalc(Request $request)
    {
        $address = $request->address; //строка ненормализов. адреса
        $normAddress = $this->addressN($address); //нормализуем адрес

        if (isset($normAddress['error']) && $normAddress['error']) {
            return ['error' => true, 'message' => 'адрес не нормализован почтой Росии'];
        }

        $weight = $request->mass;  // грамм (вес посылки);
        $mailCategory = ($request->sum_payment == 0) ? 'WITH_DECLARED_VALUE' : 'WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY';
        $indexFrom = '308011';
        $declaredValue = $request->sum*100;

        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');

            // данные для расчета вариант1
            $variant1 = new ParcelInfo();
            $variant1->setIndexFrom($indexFrom); // Индекс пункта сдачи
            $variant1->setIndexTo($normAddress[0]['index']);
            $variant1->setMailCategory($mailCategory); // с декларир. стоим., с (или без) налож. платежем
            $variant1->setMailType('POSTAL_PARCEL'); // вид РПО
            $variant1->setWeight($weight);
            $variant1->setDeclaredValue($declaredValue);

            $tariffVariant1 = $otpravkaApi->getDeliveryTariff($variant1);
            $period1 = $otpravkaApi->getDeliveryPeriod(PostType::POSILKA, $indexFrom, $normAddress[0]['index'] );
            $periodText1 = (is_array($period1['delivery'])) ? ' ('.$period1['delivery']['min'].'-'.$period1['delivery']['max'] .' дней)':'';
            $postalParsel = (($tariffVariant1->getTotalRate() + $tariffVariant1->getTotalNds()) /100 ) . ' руб.'.$periodText1;
            //dd($postalParsel, $tariffVariant1, $period1);

            // данные для расчета вариант2
            $variant2 = new ParcelInfo();
            $variant2->setIndexFrom($indexFrom); // Индекс пункта сдачи
            $variant2->setIndexTo($normAddress[0]['index']);
            $variant2->setMailCategory($mailCategory); // с декларир. стоим., с (или без) налож. платежем
            $variant2->setMailType('PARCEL_CLASS_1'); // вид РПО
            $variant2->setWeight($weight);
            $variant2->setDeclaredValue($declaredValue);

            $tariffVariant2 = $otpravkaApi->getDeliveryTariff($variant2);
            $period2 = $otpravkaApi->getDeliveryPeriod(PostType::POSILKA_ONE_CLASS, $indexFrom, $normAddress[0]['index'] );
            $periodText2 = (is_array($period2['delivery'])) ?' ('.$period2['delivery']['min'].'-'.$period2['delivery']['max'] .' дней)':'';
            $parselClass1 = (($tariffVariant2->getTotalRate() + $tariffVariant2->getTotalNds()) /100 ) . ' руб.'.$periodText2;
            //dd($parselClass1, $tariffVariant2, $period2);


            return response()->json([
                'POSTAL_PARCEL' => $postalParsel,
                'PARCEL_CLASS_1'=> $parselClass1,
            ]);
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }
    }

    //получение всех ошибок, приходящих от почты россии (по созданию заказа)
    public function getPostruOrderErrors(array $errors)
    {
        $result = '';
        $errorsParsed = $errors['errors'][0]['error-codes'];

        if (!is_array($errorsParsed) || !count($errors)) return null;

        foreach ($errorsParsed as $error)
        {
            $result .= $error['description'].'. ';
        }
        return $result;
    }

    // Изменение дня отправки в почтовое отделение (для формир. F103 по старым? пачкам)
    public function changeBatchDay(string $batch)
    {
        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');
            return $otpravkaApi->changeBatchSendingDay($batch, new DateTimeImmutable(Carbon::today()->format('Y-m-d')));
            //dd($result);//true, или ошибка почты ру- ресурс не найден,(~ все отправления уже отправлены)
        }
        catch (\InvalidArgumentException $e) {
            dd('InvalidArgumentException: '. $e->getMessage());// Обработка ошибки
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            dd('LapayGroup RussianPostException: '. $e->getMessage());// Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            dd('General Exception: '. $e->getMessage());// Обработка нештатной ситуации
        }
    }

}
