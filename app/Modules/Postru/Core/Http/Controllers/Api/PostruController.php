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
            return response()->json([]);
        }

        return response()->json($result); //echo '--- нормализация аддреса ---<br>'.'<pre>'.print_r($result,1).'</pre>';
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
            $order->setAreaTo(trim($request->area_to));
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
     * (заказ уже создан, имеется его id )
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
    // или один id заказа (строка/число)
    public function createBatchForRegister($orderIds)
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
            $result = $otpravkaApi->generateDocOrderPrintForm($orderId, OtpravkaApi::DOWNLOAD_FILE, $batchCreated, new DateTimeImmutable('now'));
            //dd($result);
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

    /**
     *
     * @param Request $request
     */
    public function createOrUpdateRegister(Request $request)
    {
        $barcode = $request->barcode;
        $orderId = $request->order_id;

        $todayRecord = PostruRegisters::whereDate('created_at', Carbon::today())->first();
        //dd($todayRecord);
        //return response()->json(['barcode' => $barcode, 'orderId' => $orderId, 'today' => $todayRecord]);

        if ($todayRecord) {
            // update record in postru_register: add new barcode in text field barcodes,
            // and not forget already presents barcodes.
            $barcodes = json_decode($todayRecord->barcodes); // надеюсь $barcodes это массив

            $barcodes[] = $barcode;
            $barcodes = json_encode($barcodes);
            $todayRecord->update(['barcodes' => $barcodes]);

            return  response()->json($todayRecord);
        } else {
            // create Batch get it name (1056), add record in postru_register
            $result = $this->createBatchForRegister($orderId);//dd($result, gettype($result));

            if (isset($result['batches'][0]['batch-status']) && ($result['batches'][0]['batch-status'] === 'CREATED') ) {
                PostruRegisters::create([
                    'name' => $result['batches'][0]['batch-name'],
                    'barcodes' => json_encode([$barcode]),
                ]);
            }
            return response()->json(['name' => $result['batches'][0]['batch-name'], 'barcodes' => json_encode($barcode) ]);
        }

    }


}
