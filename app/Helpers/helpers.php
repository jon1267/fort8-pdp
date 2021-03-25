<?php

if (! function_exists('phone_format')) {

    /**
     * Convert phone string type~ '+38 (067) 655-19-52' to string~ '+380676551952'
     * @param $phone
     * @param array $format
     * @param string $mask
     * @return false|string
     */
    function phone_format($phone, $format = [], $mask = '#')
    {
        $format = [
            '9'  => '+380#########',
            '10' => '+38##########',
            '11' => '+3###########',
            '12' => '+############'
        ];

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if(is_array($format))
        {
            if(array_key_exists(strlen($phone), $format))
            {
                $format = $format[strlen($phone)];
            }
            else
            {
                return false;
            }
        }

        $pattern = '/'.str_repeat('([0-9])?', substr_count($format, $mask)).'(.*)/';

        $format = preg_replace_callback(str_replace('#', $mask, '/([#])/'),
            function() use (&$counter){
                return '${'.(++$counter).'}';
            }, $format);

        return ($phone) ? trim(preg_replace($pattern, $format, $phone, 1)) : false;
    }


}
