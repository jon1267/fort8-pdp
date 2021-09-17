<?php

namespace App\Modules\Postru\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'index_to' => 'required|numeric|max:999999',
            'postoffice_code' => 'required|numeric|max:999999',
            'given_name' => 'required|string|max:255',
            'house_to' => 'required|string|max:100',
            'corpus_to' => 'required|string|max:100', // ???
            'mass' => 'required|numeric', // ???
            'order_num' => 'required|string|max:100', // ??? дает инет магазин
            'place_to' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255', // ???
            'region_to' => 'required|string|max:255',
            'street_to' => 'required|string|max:255',
            'room_to' => 'required|string|max:255',
            'surname' => 'required|string|max:255', // 'lastname'
        ];
    }
}
