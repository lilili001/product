<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title','keywords','meta_description','description','slug'];
    protected $table = 'product__product_translations';
}