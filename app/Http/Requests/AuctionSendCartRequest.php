<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuctionSendCartRequest extends FormRequest
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
            'key' => 'required',
            'userphone' => 'required|max:15',//? надо ли проверять, что он существует в табл. clients
            'name'  => 'required|max:255',
            'lastname'  => 'required|max:255',
            'city'  => 'required|max:255',
            'postoffice'  => 'required|max:255',
            'phone'  => 'required|max:15',//? есть userphone
            'email'  => 'required|email',
            'paymethod' => 'required',
            'discount' => 'required',
            'partnersum' => 'required',
            'orderid' => 'required'
        ];
    }
}
