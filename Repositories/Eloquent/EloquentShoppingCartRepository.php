<?php

namespace Modules\Product\Repositories\Eloquent;

use Mockery\Exception;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Product\Repositories\ShoppingCartRepository;
use Modules\Product\Support\Util;
use DB;
use Cart;
use Modules\Product\Entities\ShoppingCart;

/**
 * Class EloquentShoppingCartRepository
 * @package Modules\Product\Repositories\Eloquent
 */
class EloquentShoppingCartRepository extends EloquentBaseRepository implements ShoppingCartRepository
{
    //从当前cart session 中获取选中的总金额
    /**
     * @return float|int
     */
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

    /**
     *
     */
    public function compareSessionVsDb(){
        $dataFromDb = $this->getCartFromDb();
        if( !session()->has('cart.cart') && isset( $dataFromDb )   ){
            Cart::instance('cart')->add( $dataFromDb->toArray()  );
        }
    }
    //获取数据库cart对象

    /**
     * @return mixed|null
     */
    public function getCartFromDb(){
        if( ShoppingCart::count() == 0 ) return null;
        $cartInstance = ShoppingCart::where([
            'identifier' => user()->id,
            'instance'   => 'cart'
        ])->first()->content;
        $cartInstance = unserialize( $cartInstance );
        return $cartInstance;
    }

    /**
     * @param bool $type
     * @return array
     */
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

    /**
     * @param $rawId
     */
    public function remove($rawId)
    {
        //删除
        Cart::instance('cart')->remove($rawId);
        //删除数据库购物车
        DB::table('shoppingcart')->where([
            'identifier' => user()->id,
            'instance'   => 'cart'
        ])->update( [
            'content' => serialize( Cart::instance('cart')->content() ),
            'selected_total' => $this->getSelectedTotal()
        ]);
    }

    /**
     * @return string orderNo.
     */
    public  function StrOrderOne(){
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * @return mixed Order Amount
     */
    public function getSelectedAmount(){
        return ShoppingCart::where([
            'identifier' => user()->id,
            'instance' => 'cart'
        ])->first()->selected_total ;
    }
}


