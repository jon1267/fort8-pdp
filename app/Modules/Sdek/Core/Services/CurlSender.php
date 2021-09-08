<?php

namespace App\Modules\Sdek\Core\Services;

/**
 * Class CurlSender
 * Использование: (use App\Services\Sdek\CurlSender;)
 * $link='https://.../?with_get_params';
 * $curl=CurlSender::init($link); $res = $curl->get();
 * $status = $curl->getStatus(); $curl->close();
 * в $res будет результат запроса, в $status http статус код.
 *
 * @package App\Services\curl
 */
class CurlSender
{
    protected $curl; //cURL resource
    protected $url;  //url (full link with get params if GET) or POST requests
    protected $headers=[]; //array of headers for POST request

    public function __construct($link) {
        $this->url = $link;
        $this->curl = curl_init($link);
    }

    public static function init($link) {
        return new self($link);
    }

    public function get() {

        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        // without bearer token this animal (sdek) not work!
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers );

        //------ this headers somewhere need, somewhere not ---------------
        //curl_setopt($this->curl, CURLOPT_URL, $this->url);
        //curl_setopt($this->curl, CURLOPT_HEADER, false);
        //curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        //------------------------------------------------------------------

        return curl_exec($this->curl);
    }


    //public function post(array $data=[]) {
    public function post($data=array(), $isJsonData = 0) {

        //dd($data);

        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($this->curl, CURLOPT_URL, $this->url);

        // для запроса на Sdek: bearer token нужно http_build_query($data) и $data: php array
        // а для запроса на регистрацию заказа $data должна быть json string
        ($isJsonData == 0) ?
        curl_setopt($this->curl, CURLOPT_POSTFIELDS,  http_build_query($data) ) :
        curl_setopt($this->curl, CURLOPT_POSTFIELDS,  $data);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers );

        return curl_exec($this->curl); //json_decode(curl_exec($this->curl));
    }

    // delete order (token in header, order Uuid send in url, as get param)
    public function delete()
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers );
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($this->curl);
    }


    public function setHeaders(array $headers) {
        $this->headers = $headers;
    }

    public  function addHeader($key, $value) {
        $this->headers[$key] =  $value;
    }

    public function getError() {
        return curl_error($this->curl);
    }

    /**
     * [Successful 2xx: 200="OK", 201="Created", 202="Accepted", 203, 204,205, 206 ]
     * [Redirection 3xx: 300,301,302,303,304,305,306]
     * [Client Error 4xx: 400="Bad Request", 401="Unauthorized", 402="Payment Required", 403="Forbidden", 404="Not Found", 405="Method Not Allowed", 406="Not Acceptable"]
     * [Server Error 5xx: 500="Internal Server Error" 501="Not Implemented" 502="Bad Gateway" 503="Service Unavailable" 504="Gateway Timeout" 505="HTTP Version Not Supported"]
     *
     * @return mixed
     */
    public function getStatus() {
        return  (int) curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    }

    public function close() {
        curl_close($this->curl);
    }

    //если забыли $curl->close(); то сработает деструкт.
    public function __destruct() {
       if (is_resource($this->curl)) {
           curl_close($this->curl);
       }
    }
}
