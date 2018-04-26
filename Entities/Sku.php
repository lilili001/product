<?php

namespace Modules\Product\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    use Translatable;

    protected $table = 'product__skus';
    public $translatedAttributes = [];
    protected $fillable = [];
    protected $hidden = ['created_at','updated_at'];
    protected $casts = [
        'settings' => 'array',
    ];
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
