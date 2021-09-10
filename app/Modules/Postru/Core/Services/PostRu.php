<?php

namespace App\Modules\Postru\Core\Services;

/**
 * Класс для работы с почтой россии. Порядок: сначала создается заказ (отправление).
 * Потом заказ помещается в партию (shipment). На этапе заказ помещен в партию -
 * работает метод printPdfForms (что то типа накладной F7p pdf). Далее для партии
 * делается checkin. После checkin работает метод printF103()
 *
 * Class PostRu
 */
class PostRu
{
    const POST_RU_BASE = 'https://otpravka-api.pochta.ru';
    const API_TOKEN = 'FIsYIJDNhyY0lamsA1YxsZ0cbERUyoIe';
    const API_USER_AUTH = 'Y3VzdG9tZXIuc2VydmljZS5wZHBhcmlzQGdtYWlsLmNvbTprbGVvcGF0cmEwNzA3';
    //const API_LOGIN = 'customer.service.pdparis@gmail.com';
    //const API_PASS = 'kleopatra0707';

    /**
     * Поиск почтового отделения по индексу.
     * возвращает одно(!) ОПС (отделение почт. связи) по правильному почтовому индексу (типа 399058)
     * @param string $index
     * @return array|mixed
     */
    public function getOfficeByIndex($index='')
    {
        if ($index == '') return [];

        $apiAdd = '/postoffice/1.0/'. trim($index);
        $response = $this->getRequest($apiAdd);

        return json_decode($response);
    }

    /**
     * Поиск обслуживающего ОПС (отделение почт. связи) по адресу: 'Белгород, проспект Славы, 24'
     * @param string $address
     * @return array|mixed
     */
    public function getOfficeByAddress(string $address)
    {
        if ($address == '') return [];

        $data = ['address' => $address, 'top' => 3]; //top: кол-во ближайших ОПС. 3 по дефолту.
        $apiAdd = '/postoffice/1.0/by-address?' . http_build_query($data);

        $response = $this->getRequest($apiAdd);

        return json_decode($response);
    }

    /**
     * Поиск почтовых индексов в населённом пункте.
     * $data = ['region'=>'Московская обл', 'district' => 'Подольск', 'settlement'=>'Стрелково'];
     * Московская обл, Подольский р-н, с Стрелково. Или $data = ['settlement'=>'Белгород'];
     * @param array $data
     * @return array|mixed
     */
    public function getPostIndexes(array $data)
    {
        $apiAdd = '/postoffice/1.0/settlement.offices.codes?' . http_build_query($data);

        return  json_decode($this->getRequest($apiAdd));
    }

    /**
     * Создание заказа (версия1)
     * @param array $data
     * @return mixed
     */
    public function createOrder(array $data)
    {
        $apiAdd = '/1.0/user/backlog';

        //примерный вид $data для 1-го отправления. таких отправлений может быть несколько...
        /*$data = [
            [
                "address-type-to" => "DEFAULT",
                "given-name" => "Иван",
                "house-to" => "13",
                "index-to" => 630084,
                "mail-category" => "ORDINARY",
                "mail-direct" => 643,
                "mail-type" => "POSTAL_PARCEL",
                "mass" => 1000,
                "middle-name" => "Иванович",
                "order-num" => "002",
                "place-to" => "г Белгород",
                "postoffice-code" => "308009",
                "region-to" => "обл Белгородская",
                "room-to" => "99",
                "street-to" => "проезд Газовый",
                "surname" => "Иванов",
                "tel-address" => 79458712076,
                "transport-type" => "SURFACE"
            ],
        ];*/

        $response = $this->putRequest($data, $apiAdd);

        return $response;//json_decode($response);
    }

    // поиск информации о заказе по "order-num" - назначается магазином при вводе заказа
    public function orderInfoByNum(string $orderNum)
    {
        $apiAdd = '/1.0/backlog/search?query=' . trim($orderNum);

        $response = $this->getRequest($apiAdd);

        return json_decode($response);
    }
    //  поиск информации о заказе по id почты россии (внутренний id почты россии)
    public function orderInfoById($id)
    {
        $apiAdd = '/1.0/backlog/'. $id;

        $response = $this->getRequest($apiAdd);

        return json_decode($response);
    }

    //удаление заказа параметр: или ID заказа от почты россии, или массив из нескольких таких заказов
    public function deleteOrders($orderIds)
    {
        $apiAdd = '/1.0/backlog';
        $data = is_array($orderIds) ? $orderIds : [$orderIds];

        $response = $this->delRequest($data, $apiAdd);

        return json_decode($response);
    }

    // перенос заказа в партию.(тех.спец. почты_росии сказал что пдф документы смогут создаться только после этого)
    //(с)"отправления для начала необходимо поместить в партию, только тогда сможете сформировать печатные формы.
    //И после того как партия заполнится необходимо сделать checkin - https://otpravka.pochta.ru/specification#/documents-checkin"
    public function createShipment($orderIds)
    {
        $apiAdd = '/1.0/user/shipment';
        $data = is_array($orderIds) ? $orderIds : [$orderIds];

        return $this->postRequest($data, $apiAdd);
    }

    //checkin партии
    public function shipmentCheckin($bachName, $useOnlineBalance=false)
    {
        $apiAdd = $useOnlineBalance ?
            '/1.0/batch/'.$bachName.'/checkin' :
            '/1.0/batch/'.$bachName.'/checkin?useOnlineBalance=true';

        return $this->postRequest([], $apiAdd);
    }

    // удаление заказов из партии. параметр или массив заказов, или строка заказа
    public function deleteOrdersFromShipment($orderIds)
    {
        $apiAdd = '/1.0/shipment';
        $data = is_array($orderIds) ? $orderIds : [$orderIds];

        $response = $this->delRequest($data, $apiAdd);

        return json_decode($response);
    }

    //генерацию печатных форм по id заказа.
    public function printPdfForms($id)
    {
        $apiAdd = '/1.0/forms/'.$id.'/forms';

        $ch = curl_init(self::POST_RU_BASE . $apiAdd);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Accept: application/json;charset=UTF-8",
            "Authorization: AccessToken " . self::API_TOKEN,
            "X-User-Authorization: Basic " . self::API_USER_AUTH,
        ]);

        header("Content-type: application/pdf");

        curl_exec($ch);
        curl_close($ch);

        exit;
    }

    //генерацию формы f103 для партии.
    public function printF103($bachName)
    {
        //$fileName = 'f103_shipment_'.$id.'.pdf';
        $apiAdd = '/1.0/forms/'.$bachName.'/f103pdf';


        $ch = curl_init(self::POST_RU_BASE . $apiAdd);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Accept: application/json;charset=UTF-8",
            "Authorization: AccessToken " . self::API_TOKEN,
            "X-User-Authorization: Basic " . self::API_USER_AUTH,
        ]);

        header("Content-type: application/pdf");

        curl_exec($ch);
        curl_close($ch);

        exit;
    }



    private function getRequest(string $apiAdd)
    {
        $ch = curl_init(self::POST_RU_BASE . $apiAdd);

        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json;charset=UTF-8",
            "Accept: application/json;charset=UTF-8",
            "Authorization: AccessToken " . self::API_TOKEN,
            "X-User-Authorization: Basic " . self::API_USER_AUTH,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function putRequest(array $data=[], string $apiAdd)
    {

        $ch = curl_init(self::POST_RU_BASE . $apiAdd);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Accept: application/json;charset=UTF-8",
            "Authorization: AccessToken " . self::API_TOKEN,
            "X-User-Authorization: Basic " . self::API_USER_AUTH,
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response); //$response;
    }

    private function postRequest(array $data=[], string $apiAdd)
    {
        $ch = curl_init(self::POST_RU_BASE . $apiAdd);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json;charset=UTF-8",
            "Accept: application/json;charset=UTF-8",
            "Authorization: AccessToken " . self::API_TOKEN,
            "X-User-Authorization: Basic " . self::API_USER_AUTH,
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response); //$response;
    }

    // delete order  ??? это тестить ...
    public function delRequest(array $data=[], string $apiAdd)
    {
        $ch = curl_init(self::POST_RU_BASE . $apiAdd);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json;charset=UTF-8",
            "Authorization: AccessToken " . self::API_TOKEN,
            "X-User-Authorization: Basic " . self::API_USER_AUTH,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($data)); //json_encode($data)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
