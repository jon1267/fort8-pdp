<?php

namespace App\Modules\Fops\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminFopUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'city_sender' => 'required|string|max:255',
            'sender' => 'required|string|max:255',
            'sender_address' => 'required|string|max:255',
            'contact_sender' => 'required|string|max:255',
            'senders_phone' => 'required|string|max:255',
            //'active' => 'required|numeric|min:0|max:1',
            //'payment_control' => 'required|numeric|min:0|max:1',
            'payment_method' => 'required|string|max:255',
        ];
    }
}
