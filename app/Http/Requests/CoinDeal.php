<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoinDeal extends FormRequest
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

    public function rules()
    {
        return [
            'name' => 'required|string',
            'price' => 'required|float',
            'quantity' => 'required|int',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please input name',
            'price.required'  => 'Please input price',
            'description.required'  => 'Please input description',
            'quantity.required'  => 'Please input quantity',

            'name.string' => 'name must be a string',
            'price.float' => 'price must be a float',
            'quantity.int' => 'quantity must be a int',
            'description.string' => 'description must be a string',
        ];
    }
}
