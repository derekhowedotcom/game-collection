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
    public function rules(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'barcode' => 'nullable|string',
            'thumbnail' => 'nullable|sometimes|mimes:jpeg,png,jpg,gif',
            'cex_image' => 'nullable',
            'category_id' => ['required', 'exists:categories,id'],
            'rarity_id' =>  'nullable||exists:rarities,id',
            'value' => 'nullable|numeric|between:0,999999',
            'price_paid' => 'nullable|numeric|between:0,999999',
            'boxed' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return ['category_id' => 'category'];
    }
}
