<?php

namespace App\Modules\Advs\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAdvStoreRequest extends FormRequest
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
            'name' => 'required|max:255|unique:advs',
        ];
    }
}
