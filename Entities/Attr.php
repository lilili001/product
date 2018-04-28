<?php

namespace Modules\Product\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Attr extends Model
{
    use Translatable;

    protected $table = 'product__attrs';
    public $translatedAttributes = [];
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

    // 多where
    public function scopeMultiwhere($query, $arr)
    {
        if (!is_array($arr)) {
            return $query;
        }
        foreach ($arr as $key => $value) {
            $query = $query->where($key, 'like', '%'.$value.'%');
        }
        return $query;
    }
}
