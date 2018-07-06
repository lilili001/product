<?php

namespace Modules\Product\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdateProductRequest extends BaseFormRequest
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
        $id = $this->route()->parameter('product')->id;
        return [
            'title' => 'required',
            'slug' =>  "required|unique:product__product_translations,slug,$id,product_id,locale,$this->localeKey"
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}
