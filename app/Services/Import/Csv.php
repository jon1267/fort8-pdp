<?php

namespace App\Services\Import;

/**
 * Class Csv
 * Импорт (получение php массива) из csv файла
 * @package App\Services\Import
 */
class Csv
{
    public static function parseCsv($filename, $delimiter = ',')
    {
        $header = null;
        $data = [];

        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if(!$header) {
                    $header = self::lower($row);
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    private static function lower(array $arr) :array
    {
        $tmp = [];

        foreach ($arr as $item) {
            $tmp[] = strtolower($item);
        }

        return $tmp;
    }
}
