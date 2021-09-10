<?php

namespace App\Modules\Postru\Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Postru\Core\Services\PostRu;

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
}
