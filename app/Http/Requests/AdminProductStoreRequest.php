<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProductStoreRequest extends FormRequest
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
            'sort' =>'required|int',
            'vendor' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'categories' => 'required|array|min:1',
            'notes' => 'required|array|min:1',
            'img' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'brand_id' => 'required|int|exists:brands,id',
            //'aroma_id' => 'required|int|exists:aromas,id',
            'variants' => 'required|array|min:1',

        ];
    }
}
