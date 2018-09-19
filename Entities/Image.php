<?php

namespace Modules\Product\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use Translatable;

    protected $table = 'product__images';
    public $translatedAttributes = [];
    protected $fillable = [];
}
