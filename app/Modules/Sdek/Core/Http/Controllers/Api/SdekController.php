<?php

namespace App\Modules\Sdek\Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Sdek\Core\Services\Sdek;

class SdekController extends Controller
{
    public function offices(Request $request)
    {
        $sdek = new Sdek();

        $zip = trim($request->zip);
        $keyword = trim($request->keyword);

        if (!isset($zip) || is_null($zip)) {
            return [];
        }

        $offices = $sdek->getPostOffices($zip);
        $rows = [];

        foreach ($offices as $office) {
            $pos = '';

            if (strpos($office['name'], 'Постамат') !== false) {
                $pos = ' (Постамат)';
            }

            if (($keyword and strpos(mb_strtolower($office['address_full'], 'UTF-8'), mb_strtolower($keyword, 'UTF-8')) !== false) or !$keyword) {
                $rows[] =  $office['code'] . ' - ' . str_replace(array('\'', '"'), '', $office['address_full']) . ' (' . $office['postal_code'] . ')' . $pos;
            }
        }

        return response()->json($rows); //print json_encode($rows);
    }
}
