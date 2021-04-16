<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;

/**
 * Class Sms send sms) via turbosms.ua
 * Params: $phone - sms receiver, $smsSender - alfa name sms sender, create in turbosms.ua cabinet,
 * $text - text sms message.
 * Curl request send via Guzzle http client. It install in Laravel 8 by default.
 * (Illuminate\Support\Facades\Http - is laravel guzzle client facade)
 *
 * Class Sms
 * @package App\Services\Sms
 */
class Sms
{
    //const TURBO_SMS_URL   = 'https://api.turbosms.ua/message/ping.json'; // PING - PONG OK.
    const TURBO_SMS_URL   = 'https://api.turbosms.ua/message/send.json';
    const TURBO_SMS_TOKEN = 'c96addf19b5965a869c8df1e17d758d1413c167b';
    const TURBO_SMS_SENDER = 'ParfDeParis';

    public function sendSms(string $phone = null, string $text = null)
    {
        if (is_null($phone) || is_null($text)) {
            return false;
        }

        $data = [
            'recipients' => [$phone,],
            'sms' => [
                'sender' => self::TURBO_SMS_SENDER,
                'text'=> $text
            ],
        ];

        $result = Http::withToken(self::TURBO_SMS_TOKEN)
            ->post(self::TURBO_SMS_URL, $data);

        $result = $result->json(); // result may be string $result->body(), or array $result->json()
        //dd($result);

        $isSendSuccess = false;
        if (is_null($result['response_result']))  {
            $isSendSuccess = false;
        } elseif (is_array($result['response_result']) && $result['response_result'][0]['response_code']==0 && $result['response_result'][0]['response_status']=='OK') {
            $isSendSuccess = true;
        }

        return $isSendSuccess; //$result; for full diagnostic from turbosms.ua
    }
}
