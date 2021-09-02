<?php

namespace App\Modules\Clients\Payments\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientPaymentRequest extends FormRequest
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
            'sum' => 'required',
            'card' => 'required|string|max:255',
        ];
    }
}
