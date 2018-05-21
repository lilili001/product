<?php

namespace Modules\Product\Repositories\Eloquent;

use Mockery\Exception;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Product\Repositories\ShoppingCartRepository;
use Modules\Product\Support\Util;
use DB;
use Cart;
use Modules\Product\Entities\ShoppingCart;

class EloquentShoppingCartRepository extends EloquentBaseRepository implements ShoppingCartRepository
{
    //从当前cart session 中获取选中的总金额
    public function getSelectedTotal(){
        $total = 0;
        $instance = Cart::instance('cart')->content();
        foreach($instance as $key=>$item){
            if($item->options['selected']){
                $total += (float) ($item->price) * ($item->qty) ;
            }
        }
        return $total;
    }
    //如果没有session 就从数据库里取 并赋给session
    public function compareSessionVsDb(){
        $dataFromDb = $this->getCartFromDb();
        if( !session()->has('cart.cart') && isset( $dataFromDb )   ){
            Cart::instance('cart')->add( $dataFromDb->toArray()  );
        }
    }
    //获取数据库cart对象
    public function getCartFromDb(){
        if( ShoppingCart::count() == 0 ) return null;
        $cartInstance = ShoppingCart::where([
            'identifier' => user()->id,
            'instance'   => 'cart'
        ])->first()->content;
        $cartInstance = unserialize( $cartInstance );
        return $cartInstance;
    }

    public function getCurrentUserCart($type = false)
    {
        $items = [];
        foreach (Cart::instance('cart')->content() as $key => $item) {
            $equalUserId = $item->options['userId'] == user()->id;
            $condition = $type ?  $equalUserId &&   !!($item->options['selected'])   : $equalUserId ;

            if ($condition) {
                $items[] = $item->toArray();
            }
        }
        return $items;
    }

}
