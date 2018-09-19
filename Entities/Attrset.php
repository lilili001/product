<?php

namespace Modules\Product\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Attribute\Entities\Attribute;

class Attrset extends Model
{
    use Translatable;

    protected $table = 'product__attrsets';
    public $translatedAttributes = ['name'];
    //protected $fillable = [];
    protected $guarded = [];

    protected $hidden = ['created_at','updated_at'];

    public function attrs()
    {
        return $this->belongsToMany(Attribute::class,'attrset_attr');
    }
}
