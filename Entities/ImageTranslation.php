<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ImageTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'product__image_translations';
}
