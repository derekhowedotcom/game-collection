<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionItemRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'barcode' => 'nullable|string',
            'thumbnail' => 'nullable|sometimes|mimes:jpeg,png,jpg,gif',
            'category_id' => ['required', 'exists:categories,id'],
            'value' => 'nullable|numeric',
            'price_paid' => 'nullable|numeric',
        ];
    }

    public function attributes()
    {
        return ['category_id' => 'category'];
    }
}
