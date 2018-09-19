<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $table = 'shoppingcart';

    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}
