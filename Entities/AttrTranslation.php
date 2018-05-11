<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class AttrTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'product__attr_translations';
}
