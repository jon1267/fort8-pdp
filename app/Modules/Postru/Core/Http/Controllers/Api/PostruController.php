<?php

namespace App\Modules\Postru\Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Http\Request;
use App\Modules\Postru\Core\Services\PostRu;
use LapayGroup\RussianPost\Providers\OtpravkaApi;
use LapayGroup\RussianPost\AddressList;
use LapayGroup\RussianPost\Entity\Order;
use App\Modules\Postru\Core\Http\Requests\CreateOrderRequest;
use App\Models\PostruRegisters;

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
        $config = include 'lapaygroup_config.php';
        $address = trim($request->q);
        $result = [];

        try {
            $otpravkaApi = new OtpravkaApi($config);
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
            return ['error' => true, 'message' => 'address is not normalised by PostRu'];
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
            $order->setPlaceTo(trim($orderData['place_to']));//'Москва'
            $order->setRecipientName(trim($orderData['recipient_name']));//'Иванов Иван'
            $order->setRegionTo(trim($orderData['region_to']));//'Москва'
            $order->setStreetTo(trim($orderData['street_to']));//'Каширское шоссе'
            $order->setRoomTo(trim($orderData['room_to']));//'1'
            $order->setSurname(trim($orderData['surname']));//'Иванов'
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBatch(Request $request)
    {
        $config = include 'lapaygroup_config.php';
        $data = [$request->order_id];
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
        return response()->json($result);
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

    // массив заказов помещает в уже существующую партию. (не тестил)
    public function addOrdersToBatch(string $batch, array $orders)
    {
        try {
            $otpravkaApi = new OtpravkaApi($config = include 'lapaygroup_config.php');
            $result = $otpravkaApi->addOrdersToBatch($batch, $orders); // Ответ аналогичен созданию заказов
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

        return response()->json($result);
    }

    // возвращает pdf файл с формой Ф103 для указанной партии ($bachName).
    // метод printF103 работает только если для партии выполнялся метод checkin (проверка партии $bachName )
    public function printF103($bachName)
    {
        $config = include 'lapaygroup_config.php';

        try {
            $otpravkaApi = new OtpravkaApi($config);
            $otpravkaApi->sendingF103form($bachName); //$otpravkaApi->sendingF103form($bachName, true); // С онлайн балансом
            $result = $otpravkaApi->generateDocF103($bachName, OtpravkaApi::DOWNLOAD_FILE);
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
    public function printPdfForms($orderId, $batchCreated = true)
    {
        $config = include 'lapaygroup_config.php';

        try {
            $otpravkaApi = new OtpravkaApi($config);
            // Генерация печатных форм до формирования партии (после формирования партии $batchCreated = true)
            return $otpravkaApi->generateDocOrderPrintForm($orderId, OtpravkaApi::PRINT_FILE, $batchCreated, new DateTimeImmutable('now'));
            //dd($result, gettype($result), $result->getError());
            /*if (!$result->getError()) {
                $savePdf = public_path().'\src\\'.'postru_'.$orderId.'.pdf';
                file_put_contents($savePdf, $result->getStream());
            }*/
            //header("Content-type: application/pdf");
            //print $result->getStream();
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

    // Удаление заказов, которые уже были добавлены в партию.
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

        return response()->json($result);
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

    // реестр партий (состоят из заказов) ПочтыРу
    //public function createOrUpdateRegister(Request $request)
    public function createOrUpdateRegister(array $data)
    {
        $barcode = $data['barcode'];
        $orderId = $data['order_id'];

        $todayRecord = PostruRegisters::whereDate('created_at', Carbon::today())->first();
        //dd($todayRecord); //return response()->json(['barcode' => $barcode, 'orderId' => $orderId, 'today' => $todayRecord]);

        if ($todayRecord) {
            // update record in postru_register, add new barcode in text field barcodes, with already presents barcodes.
            $barcodes = json_decode($todayRecord->barcodes);

            $barcodes[] = $barcode;
            $barcodes = json_encode($barcodes);
            $todayRecord->update(['barcodes' => $barcodes]);
            // in postru_register update barcodes field
            $batchName = $todayRecord->name;
        } else {
            // create new Batch, get it name (1056), add record in postru_register
            $result = $this->createBatchN($orderId); //dd($result, gettype($result));
            if (isset($result['batches'][0]['batch-status']) && ($result['batches'][0]['batch-status'] === 'CREATED') ) {

                PostruRegisters::create([
                    'name' => $result['batches'][0]['batch-name'],
                    'barcodes' => json_encode([$barcode]),
                ]);
            }
            // in postru_register created today record, with batch-name & barcodes fields
            $batchName = $result['batches'][0]['batch-name'] ?? '';
        }

        // тут (по идее) у нас есть № партии, id заказа(почты ру) пробуем печатать пдф
        // к-рая для посылки: отправитель получатель (ф7п)
        $result = $this->printPdfForms($orderId);
        if(is_object($result) && !$result->getError()) {
            return ['ttn' => $barcode, 'pdf_content' => $result->getStream()];
        }

        return ['error' => true, 'message' => 'error creating f7p pdf form'];
    }

    // реализация того, что нам нужно в итоге ... //public function index(array $data)
    public function index(Request $request)
    {
        $address = $request->address; // $data['address'];
        $normAddress = $this->addressN($address);

        if (!isset($normAddress[0]['validation-code']) && $normAddress[0]['validation-code'] !== 'VALIDATED')
        {
            return ['error' => true, 'message' => 'address is not normalised by PostRu'];
        }

        $order['index_to'] = $normAddress[0]['index']; // почт.инд. получателя
        $order['postoffice_code'] = '308011'; // ~ почт.инд. отправляещего ОПС
        $order['given_name'] = 'PdPARIS'; // ~ имя (фио) отправителя посылки
        $order['house_to']  = $normAddress[0]['house']; // № дома получателя
        $order['corpus_to'] = $normAddress[0]['corpus']; // корпус получателя
        $order['place_to']  = $normAddress[0]['place']; // город получателя
        $order['mass']      = 1000; // грамм (вес посылки)
        $order['order_num'] = $request->orderid; //наш номер заказа на посылку, делаем = id заказа магазина
        $order['recipient_name'] = $request->name; // ФИО получателя
        $order['region_to'] = $normAddress[0]['region']; // область получателя
        $order['street_to'] = $normAddress[0]['street']; // улица получателя
        $order['room_to']   = $normAddress[0]['room']; // квартира получателя
        $order['surname']   = $request->name; // фамилия получателя

        $postOrder = $this->createOrderN($order);

        if (!isset($postOrder['orders']['barcode']) || !isset($postOrder['orders']['result-id']))
        {
            return ['error' => true, 'message' => 'Error creating PostRu order'];
        }

        return $this->createOrUpdateRegister([
            'barcode'  => $postOrder['orders']['barcode'],
            'order_id' => $postOrder['orders']['result-id'],
        ]);

    }
}
