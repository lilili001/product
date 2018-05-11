<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class SkuTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'product__sku_translations';
}
