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
use LapayGroup\RussianPost\Entity\Item;

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
        //dd($batch, $data);

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
            // Генерация печатных форм до формирования партии (после формирования партии $batchCreated = true)
            return $otpravkaApi->generateDocOrderPrintForm($orderId, OtpravkaApi::PRINT_FILE, $batchCreated, new DateTimeImmutable('now'), OtpravkaApi::PRINT_TYPE_THERMO);

            //dd($result, $result->getStream()->getContents());
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
                //dd(['error' => true, 'message' => 'for barcode '. $barcode .' nothing not found']);
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

    // реестр партий (состоят из заказов) ПочтыРу
    //public function createOrUpdateRegister(Request $request)
    public function createOrUpdateRegister(array $data)
    {
        $barcode = $data['barcode'];
        $orderId = $data['order_id'];

        $todayRecord = PostruRegisters::whereDate('created_at', Carbon::today())->first();
        //dd($todayRecord); //return response()->json(['barcode' => $barcode, 'orderId' => $orderId, 'today' => $todayRecord]);

        if ($todayRecord) {
            // add in Batch ($today->name) Post ru order, update record in postru_register,
            //  add new barcode in text field barcodes, with already presents barcodes.
            $batchName = $todayRecord->name;
            $result = $this->moveOrdersToBatch($batchName, $orderId);
            //dd($result, gettype($result)); // тут если успех, то код ниже...

            $barcodes = json_decode($todayRecord->barcodes);
            $barcodes[] = $barcode;
            $barcodes = json_encode($barcodes);
            $todayRecord->update(['barcodes' => $barcodes]); //in postru_register update barcodes field

        } else {
            // create new Batch add in batch ($orderId), get batch name(1056), add record in postru_register
            $result = $this->createBatchN($orderId); //dd($result, gettype($result));
            if (isset($result['batches'][0]['batch-status']) && ($result['batches'][0]['batch-status'] === 'CREATED') ) {

                PostruRegisters::create([
                    'name' => $result['batches'][0]['batch-name'],
                    'barcodes' => json_encode([$barcode]),
                ]);
            }
            // in postru_register created today record, with batch-name & barcodes fields
            // $batchName = $result['batches'][0]['batch-name'];
        }

        // тут (по идее) у нас есть N партии, id заказа (от почты ру)
        // пробуем печатать пдф для посылки: отправитель/получатель (ф7п)
        $pdf = $this->printPdfForms($orderId, true); //dd($pdf, $pdf->getStream()->getContents());

        if(is_object($pdf) && !$pdf->getError())
        {
            return [
                'ttn' => $barcode,
                'pdf_content' => $pdf->getStream()->getContents(),
                'orderId' => $orderId,
            ];
        }

        return ['error' => true, 'message' => 'error creating f7p pdf form'];
    }

    // реализация того, что нам нужно в итоге ... //public function index(array $data)
    public function index(Request $request)
    {
        $address = $request->address; // $data['address'];
        $normAddress = $this->addressN($address);
        //dd($normAddress, gettype($normAddress));

        //if (!isset($normAddress[0]['validation-code']) && $normAddress[0]['validation-code'] !== 'VALIDATED')
        if (isset($normAddress['error']) && $normAddress['error']) {
            return ['error' => true, 'message' => 'address is not normalised by PostRu'];
        }

        $order['index_to'] = $normAddress[0]['index'] ?? null; // почт.инд. получателя
        $order['postoffice_code'] = '308009'; // ~ почт.инд. отправляещего ОПС '308009','308011'
        $order['given_name'] = 'PdPARIS'; // ~ имя (фио) отправителя посылки
        $order['house_to']  = $normAddress[0]['house'] ?? null; // № дома получателя
        $order['corpus_to'] = $normAddress[0]['corpus'] ?? null; // корпус получателя
        $order['place_to']  = $normAddress[0]['place'] ?? null; // город получателя
        $order['mass']      = 250; // грамм (вес посылки)
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

        //$order['fragile'] = true; //признак хрупкое
        //$item = new Item();
        //$item->setDescription('парфюм');
        //$item->setQuantity(1);
        //$item->setWeight(300);
        //$order['goods'][0] = $item;

        $postOrder = $this->createOrderN($order); //dd($postOrder);

        if (isset($postOrder['errors'])) {
            return ['error' => true, 'message' => 'error creating order by PostRu...'];
        }

        //dd($postOrder);//заказы надеюсь создаем по одному...
        return $this->createOrUpdateRegister([
            'barcode'  => $postOrder['orders'][0]['barcode'],
            'order_id' => $postOrder['orders'][0]['result-id'],
        ]);

    }

    // если чекин (закрытие) партии уже сделан, возвращаем пдф-ку этой партии, если нет: -
    // делаем checkin партии, получаем пдф-ку партии, обновляем запись реестра, возвращаем пдфку
    public function checkin()
    {
        $todayRecord = PostruRegisters::whereDate('created_at', Carbon::today())->first();

        if (!$todayRecord) {
            return ['error' => true, 'message' => 'today record in PostRu registry not found'];
        }

        if ($todayRecord->checkin)
        {
            $pdf = $this->printF103($todayRecord->name, false);
            if(is_object($pdf) && !$pdf->getError()) {
                return ['pdf_content' => $pdf->getStream()->getContents()];
            }

            return ['error' => true, 'message' => 'error creating F103 pdf document'];
        }

        // делаем checkin партии, получаем пдф-ку партии, обновляем запись реестра, возвращаем пдфку
        $pdf =  $this->printF103($todayRecord->name, true);
        if(is_object($pdf) && !$pdf->getError()) {
            $todayRecord->update(['checkin' => 1]);
            return ['pdf_content' => $pdf->getStream()->getContents()];
        }

        return ['error' => true, 'message' => 'error creating F103 pdf document'];
    }

    //249277, обл Калужская, р-н Думиничский, с Усты  д. 45
    //185002, Респ Карелия, г Петрозаводск, р-н Перевалка, ул Пархоменко, д. 33
    //678174, Респ Саха /Якутия/, у Мирнинский, г Мирный, ул Ойунского, д. 7, кв. 4
    //356200, край Ставропольский, р-н Шпаковский, с Пелагиада, ул Нахимова, д. 13, кв. 80
    //155070, обл Ивановская, р-н Ильинский, с Аньково, ул Луговая, д. 4, кв. 2
    //652971, обл Кемеровская область - Кузбасс, р-н Таштагольский, пгт Шерегеш, ул Советская, д. 3, кв. 27
    public function test()
    {
        $request = new Request();
        $request->name = 'Иванов Иван Иваныч';
        $request->sum = 1490;
        $request->orderid = '085_pdparis';
        $request->address = '155070, обл Ивановская, р-н Ильинский, с Аньково, ул Луговая, д. 4, кв. 2';
        $request->phone = '74956542146';
        $request->sum_payment = 1475; // наложенный платеж. (если = 0 нет наложенного платежа)
        $request->type_id = 1; //?ВИД РПО. 1— POSTAL_PARCEL(Посылка "нестандартная"), 2— PARCEL_CLASS_1 (Посылка 1-го класса)
        $this->index($request);

        //$this->checkin();

        //$this->deleteOrdersInBatchByBarcode(80081765207695);
    }

}
