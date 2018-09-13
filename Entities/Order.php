<?php

namespace Modules\CustomerAdress\Entities;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'sales_flat_order';
    protected $guarded = [];
}
