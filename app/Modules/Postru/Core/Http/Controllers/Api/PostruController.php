<?php

namespace App\Modules\Postru\Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Postru\Core\Services\PostRu;
use LapayGroup\RussianPost\Providers\OtpravkaApi;
use LapayGroup\RussianPost\AddressList;
use LapayGroup\RussianPost\Entity\Order;
use App\Modules\Postru\Core\Http\Requests\CreateOrderRequest;

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
            // Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            // Обработка нештатной ситуации
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
            /*$order->setAddressTypeTo('DEFAULT');
            $order->setMailCategory('ORDINARY');
            $order->setMailDirect(643);
            $order->setMailType('POSTAL_PARCEL');
            $order->setTelAddress(79459562067);
            $order->setTransportType('SURFACE');
            $order->setFragile(true);*/
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
            // Обработка ошибки заполнения параметров
        }

        catch (\LapayGroup\RussianPost\Exceptions\RussianPostException $e) {
            // Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            // Обработка нештатной ситуации
        }

        //echo '--- создание заказа v2 ---<br>'.'<pre>'.print_r($result,1).'</pre>';
        return $result; //return response()->json($result);
    }

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
            // Обработка ошибочного ответа от API ПРФ
        }

        catch (\Exception $e) {
            // Обработка нештатной ситуации
        }

        //echo '--- создание заказа v2 ---<br>'.'<pre>'.print_r($result,1).'</pre>';
        return response()->json($result);
    }
}
