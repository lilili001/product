<?php

namespace Modules\Product\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateProductRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'attrset_id' => 'required',
            'category_id' => 'required',
            'price' => 'required'
        ];
    }

    public function translationRules()
    {
        return [
            'title' => 'required',
            'slug' =>  "required|unique:product__product_translations,slug,null,product_id,locale,$this->localeKey"
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'attrset_id.required' => trans('product::messages.attrset_id is required'),
            'category_id.required' => trans('category_id::messages.attrset_id is required'),
            'price.required' => trans('price::messages.attrset_id is required'),
        ];
    }

    public function translationMessages()
    {
        return [
            'title.required' => trans('product::messages.title is required'),
            'slug.required' => trans('product::messages.slug is required'),
            'slug.unique' => trans('product::messages.slug is unique'),
        ];
    }
}
