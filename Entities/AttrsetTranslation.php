<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class AttrsetTranslation extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'product__attrset_translations';
}
