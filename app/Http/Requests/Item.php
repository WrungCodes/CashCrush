<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Item extends FormRequest
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
            'name' => 'required|string',
            'price' => 'required|int',
            'description' => 'required|string',
            'type' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please input name',
            'price.required'  => 'Please input price',
            'description.required'  => 'Please input description',
            'type.required'  => 'Please input type',

            'name.string' => 'name must be a string',
            'price.int' => 'price must be a int',
            'description.string' => 'description must be a string',
            'type.string' => 'type must be a string',
        ];
    }
}
