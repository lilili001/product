<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Currency\Entities\CurrencyRate;
use Modules\Media\Image\Imagy;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ShoppingCart;
use Modules\Product\Repositories\ProductRepository;
use AjaxResponse;
use Mockery\Exception;
use Modules\Product\Repositories\ShoppingCartRepository;
use Modules\User\Entities\UserAddress;
use Cart;

/**
 * Class CartController
 * @package Modules\Product\Http\Controllers
 */
class CartController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected $product;
    /**
     * @var
     */
    protected $attr;
    /**
     * @var
     */
    protected $store;
    /**
     * @var ShoppingCartRepository
     */
    protected $shopCart;
    /**
     * ProductController constructor.
     * @param $product
     */
    protected $allowedCurrencies;

    protected $rate;//汇率
    public function __construct(ProductRepository $product, Imagy $imagy , ShoppingCartRepository $shoppingcart )
    {
        $this->imagy = $imagy;
        $this->product = $product;
        $this->shopCart = $shoppingcart;//引入ShoppingCartRepository

        $allowedCurrencies = CurrencyRate::all()->toArray();
        $allowedCurrencies = arrayChangeKey( $allowedCurrencies , 'currency_to' );
        $this->allowedCurrencies = $allowedCurrencies;
        $this->rate = $allowedCurrencies[ getCurrentCurrency() ]['rate'];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cart()
    {
        $total = number_format($this->shopCart->getSelectedTotal(),2);
        return view('cart',compact('total'));
    }

    public function cartItems(Request $request)
    {
        $this->shopCart->compareSessionVsDb();
        $items = $this->shopCart->getCurrentUserCart();

        //total 是Cart计算出来的 不需要前台进行计算 前台直接显示值即可
        $total = number_format($this->shopCart->getSelectedTotal(),2);
        if($request->ajax() || $request->expectsJson() ){
            return AjaxResponse::success('',[
                'cart' => $items,
                'total' => $total
            ]);
        }
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkout()
    {
        $this->shopCart->compareSessionVsDb();
        $user = user()->toArray();
        $items = $this->shopCart->getCurrentUserCart(true);

        $addresses = UserAddress::where([
            'user_id' => user()->id
        ])->get();

        $total = $this->shopCart->getSelectedTotal() * $this->rate ;

        $currencySymbol = getCurrentCurrency() . $this->allowedCurrencies[getCurrentCurrency()]['symbol'];

        return view('checkout',compact('items','user','addresses','total' , 'currencySymbol' ));
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return mixed
     */
    public function getSku(Product $product, Request $request)
    {
        $options = request('options');
        $sku = $this->product->findSku($product, $options);
        return AjaxResponse::success('addToCart', $sku);
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return mixed
     */
    public function addToCart(Product $product, Request $request)
    {
        $options = request('options');
        $sku = $this->product->findSku($product, $options);

        if ($sku['stock'] == 0) {
            return AjaxResponse::success('库存不足', $sku['stock']);
        } else {
            $swatchColors = json_decode($product->swatch_colors, true);
            $imagePath = !empty($swatchColors) ? $swatchColors[$options['color']]['filepath'] : [];

            $item = [
                'id' => $product->id,
                'name' => $product->title,
                'price' => $sku['price'] ,
                'qty' => request('qty'),
                'options' => [
                    'sku_options' => $options,
                    'selectedItemLocale' => request('selectedObjLocale'),
                    'selected' => false,
                    'image' => !empty($imagePath) ? $imagePath : $this->imagy->getThumbnail($product->featured_images->first()->path, 'smallThumb'),
                    'slug' => '/product/' . $product->slug,
                    'userId' => user()->id,
                    'index' => Cart::instance('cart')->count()+1
                ]
            ];

            //查询数据库有没有shoppingcart实例
            $instances = ShoppingCart::pluck('instance')->toArray();

            $cartInstance = ShoppingCart::where([
                'identifier' => user()->id,
                'instance'   => 'cart'
            ])->first();

            if( in_array( 'cart' ,  $instances ) ){
                //首先将数据库的 cart instance 赋值给session
                $content = $cartInstance->content;
                session('cart.cart', unserialize($content));
                Cart::instance('cart')->add($item);
            }else{
                session()->remove('cart.cart');
                Cart::instance('cart')->add($item);
                Cart::instance('cart')->store(user()->id);
            }
            $this->updateDbcart();
            $items = $this->shopCart->getCurrentUserCart();
            return AjaxResponse::success('添加成功',[
                'cart' => $items,
                'total' => $this->shopCart->getSelectedTotal()
            ]);
        }
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function updateCart(Product $product)
    {
        $rawId = request('rawId');
        $qty = request('qty');

        $item = Cart::instance('cart')->get($rawId);
        $sku = $this->product->findSku($product, $item->options['sku_options']);

        if ($sku['stock'] < $qty) {
            return AjaxResponse::fail('库存不足', $sku['stock']);
        } else {
            Cart::instance('cart')->update($rawId,$qty);
            $this->updateDbcart();
            $items = $this->shopCart->getCurrentUserCart();
            return AjaxResponse::success('修改成功',[
                'cart' => $items,
                'total' => $this->shopCart->getSelectedTotal()
            ]);
        }
    }

    /**
     * @param null $rawId
     * @param null $type
     * @return mixed
     */
    public function updateStatus($rawId = null, $type=null )
    {
        $rawId = request('rowId');
        $type =  request('type');

        $this->shopCart->compareSessionVsDb();
        $item = Cart::instance('cart')->get($rawId);
        $item->options['selected'] = $type;
        Cart::instance('cart')->update($rawId, ['options.selected' => $type]);
        $this->updateDbcart();
        $items = $this->shopCart->getCurrentUserCart();
        return AjaxResponse::success('修改成功',[
            'cart' => $items,
            'total' => $this->shopCart->getSelectedTotal()
        ]);
    }


    //批量改状态 是否选中 并设置选中总价

    /**
     * @return mixed
     */
    public function bulkUpdateStatus()
    {
        $contents = Cart::instance('cart')->content();

        foreach( $contents as $key=>$item ){

            $rowId = $item->rowId;
            $type = request('data') != 0;

            $this->shopCart->compareSessionVsDb();
            $item = Cart::instance('cart')->get($rowId);
            $item->options['selected'] = $type;
            Cart::instance('cart')->update($rowId, ['options.selected' => $type]);
            $this->updateDbcart();
        }
        $items = $this->shopCart->getCurrentUserCart();
        return AjaxResponse::success('修改成功',[
            'cart' => $items,
            'total' => $this->shopCart->getSelectedTotal()
        ]);
    }
    //删除数据库

    /**
     * @return mixed
     */
    public function deleteCartItem()
    {

        $rawId = request('rawId');
        try{
            Cart::instance('cart')->remove($rawId);
            $this->updateDbcart();
            $items = $this->shopCart->getCurrentUserCart();
        }catch ( Exception $e  ){
            return  AjaxResponse::fail('',$e->getMessage()) ;
        }
        return AjaxResponse::success('修改成功',
        [
            'cart' => $items,
            'total' => $this->shopCart->getSelectedTotal()
        ]);
    }

    //更新数据库cart
    /**
     * @param array $dataToUpdateDatabase
     */
    protected function updateDbcart($dataToUpdateDatabase = []){
        ShoppingCart::where([
            'identifier' => user()->id,
            'instance'   => 'cart'
        ])->update(array_merge([
            'content' => serialize( Cart::instance('cart')->content() ),
            'selected_total' => $this->shopCart->getSelectedTotal()
        ],$dataToUpdateDatabase));
    }
}