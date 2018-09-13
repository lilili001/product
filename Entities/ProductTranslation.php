<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ProductTranslation extends Model
{
    use Searchable;
    public $timestamps = false;
    protected $fillable = ['title','keywords','meta_description','description','slug'];
    protected $table = 'product__product_translations';
}