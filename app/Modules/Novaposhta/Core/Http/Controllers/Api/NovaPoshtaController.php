<?php

namespace App\Modules\Novaposhta\Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\City;

class NovaPoshtaController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cities(Request $request)
    {
        $q = $request->get('q');

        $cities = City::where('name_ru', 'LIKE', $q.'%')
            ->orWhere('name_ua', 'LIKE', $q.'%')
            ->get(['ref', 'name_ua', 'region', 'area'])
            ->take(20)->toArray();

        return response()->json($cities);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function offices(Request $request)
    {
        $ref = $request->get('ref');

        if ( ! isset($ref)) {
            return [];
        }

        $offices = Office::where('ref', $ref)->get(['number', 'name_ua', 'name_ru'])->toArray();;

        return response()->json($offices);
    }
}
