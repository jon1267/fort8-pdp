<?php

namespace App\Modules\Novaposhta\Core\Services;

class NewPost
{
    const NEW_POST_JSON = 'https://api.novaposhta.ua/v2.0/json/';
    const API_KEY = '1d20ddd956971c092fdcf4685bc68ad9';


    /**
     * Онлайн поиск в справочнике населенных пунктов (апи кей необязателен)
     * этот метод необходим для ОНЛАЙН ПОИСКА населенных пунктов. С данным методом
     * нет необходимости хранить на своей стороне справочники и заботиться о их обновлениях.
     * вместо города, работает одна первая буква названия города, тогда limit
     * можно и 500 и 1000. выдача на украинском.
     * @param string $cityName
     * @param int $limit
     * @return mixed
     */
    public function getCitiesOnline(string $cityName = '', int $limit = 5)
    {
        $data = [
            'modelName' => 'Address',
            'calledMethod' => 'searchSettlements',
            'methodProperties' => ['CityName' => $cityName, 'Limit' => $limit],
        ];

        $result = json_decode($this->request(json_encode($data)));

        return $result->data; //$result - std php obj, with data, errors, warnings, totalCount, messages, & so on
    }

    // Справочник городов компании «Новая Почта» на украинском и русском языках. (нужен апи)
    public function getCities(string $cityName='', string $cityRef='', int $page = 1)
    {
        $data = [
            'apiKey' => self::API_KEY,
            'modelName' => 'Address',
            'calledMethod' => 'getCities',
            'methodProperties' => [
                'Ref' => $cityRef,
                'FindByString' => $cityName,
                'Page' => $page,
            ],
        ];

        $result = json_decode($this->request(json_encode($data)));

        return $result->data; //$result;
    }

    /**
     * Справочник улиц компании. Метод загружает справочник улиц в рамках населенных пунктов Украины
     * куда осуществляет доставку компания «Новая Почта» (отправления с типом доставки от/до адреса клиента)
     * @param string $ref Идентификатор города
     * @param string $street
     * @param int $page
     * @return mixed
     */
    public function getStreetsByPage(string $ref='', string $street='', $page = 1)
    {
        $data = [
            'apiKey' => self::API_KEY,
            'modelName' => 'Address',
            'calledMethod' => 'getStreet',
            'methodProperties' => [
                'CityRef' => $ref,
                'FindByString' => $street,
                'Page' => $page,
            ],
        ];

        $result = json_decode($this->request(json_encode($data)));

        return $result->data; //$result;
    }

    // Онлайн поиск улиц в справочнике населенных пунктов. метод необходим для ОНЛАЙН ПОИСКА
    // улиц в выбранном населенном пункте. (REF насел. пункта из справочника населенных пунктов)
    // С данным методом нет необходимости хранить
    // на своей стороне справочники и заботиться об их обновлениях.
    public function getStreetsOnline(string $ref='', string $street='', int $limit = 5)
    {
        $data = [
            'apiKey' => self::API_KEY,
            'modelName' => 'Address',
            'calledMethod' => 'searchSettlementStreets',
            'methodProperties' => [
                'SettlementRef' => $ref,
                'StreetName' => $street,
                'Limit' => $limit,
            ],
        ];

        $result = json_decode($this->request(json_encode($data)));

        return $result->data; //$result;
    }

    //метод загружает справочник отделений «Новая Почта» в рамках населенных пунктов Украины.
    public function getWarehouseByCity(string $cityName='', string $cityRef='', int $page = 1)
    {
        $data = [
            'apiKey' => self::API_KEY,
            'modelName' => 'AddressGeneral',// 'Address',
            'calledMethod' => 'getWarehouses',//'getSettlements', 'getWarehouses',
            'methodProperties' => [
                'CityName' => $cityName,
                'CityRef' => $cityRef,
                'Page' => $page,
            ],
        ];

        $result = json_decode($this->request(json_encode($data)));

        return $result->data; //$result;
    }

    // метод загружает справочник отделений «Новая Почта» по ссылке REF на город (населенный пункт)
    // 1-й параметр это Ref полученный из выдачи метода getSettlements() [первый параметр выдачи]
    // 2-й параметр (typeWarehouse) тип отделения новой почты (обычно почтовое отд. и грузовое отд.)
    public function getWarehousesByRef(string $settleRef = '', string $typeWarehouse, int $page)
    {
        $data = [
            'apiKey' => self::API_KEY,
            'modelName' => 'AddressGeneral',// 'Address',
            'calledMethod' => 'getWarehouses',//'getSettlements', 'getWarehouses',
            'methodProperties' => [
                'SettlementRef' => $settleRef,
                'TypeOfWarehouseRef' => $typeWarehouse,
                'Page' => $page,
                'Limit' => 500,
            ],
        ];

        $result = json_decode($this->request(json_encode($data)));

        return $result->data; //$result;
    }

    public function getWarehouseTypes(string $settleRef = '')
    {
        $data = [
            'apiKey' => self::API_KEY,
            'modelName' => 'AddressGeneral',
            'calledMethod' => 'getWarehouseTypes',
            'methodProperties' => [
                'SettlementRef' => $settleRef,
                //'CityName' => $cityName,
                //'CityRef' => $cityRef,
                //'Page' => $page,
            ],
        ];

        $result = json_decode($this->request(json_encode($data)));

        return $result->data; //$result;
    }

    // Справочник населенных пунктов Украины (рус & укр). для населенного пункта возвращает область, и район.
    // Выдача не более 150 записей на страницу. Для просмотра более 150 записей, необходимо использовать "Page"
    // $areaRef - код области, $ref - идентификатор адреса ? , $regionRef - код района ? ... пробовал город, и код обл.
    public function getSettlements(string $city='', string $areaRef='', string $ref='', $regionRef='', int $page = 1)
    {
        $data = [
            'apiKey' => self::API_KEY,
            'modelName' => 'AddressGeneral',
            'calledMethod' => 'getSettlements',
            'methodProperties' => [
                'FindByString' => $city,
                'AreaRef' => $areaRef,
                'Ref' => $ref,
                'RegionRef' => $regionRef,
                'Page' => $page,
                'Warehouse' => 1,
            ],
        ];

        $result = json_decode($this->request(json_encode($data)));

        return $result->data;
    }

    private function request($data)
    {
        $ch = curl_init(self::NEW_POST_JSON);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: string"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $data ); //http_build_query($data);//json_encode($data);
        curl_setopt($ch, CURLOPT_POST, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
