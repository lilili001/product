<?php

namespace Modules\Product\Http\Controllers;

use App\Acart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Media\Image\Imagy;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ShoppingCart;
use Modules\Product\Repositories\ProductRepository;
use AjaxResponse;
use Modules\User\Entities\UserAddress;
use   Cart;
class CartController extends Controller
{
    protected $product;
    protected $attr;
    protected $store;
    /**
     * ProductController constructor.
     * @param $product
     */
    public function __construct(ProductRepository $product, Imagy $imagy )
    {
        $this->imagy = $imagy;
        $this->product = $product;
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

    public function cart()
    {
        $this->compareSessionVsDb();
        $items = $this->getCurrentUserCart();
        $total = $this->getSelectedTotal();
        return view('cart', compact('items','total'));
    }

    public function checkout()
    {
        $this->compareSessionVsDb();
        $user = user()->toArray();
        $items = $this->getCurrentUserCart(true);

        $addresses = UserAddress::where([
            'user_id' => user()->id
        ])->get();

        $total = $this->getSelectedTotal();

        return view('checkout',compact('items','user','addresses','total'));
    }

    public function getSku(Product $product, Request $request)
    {
        $options = request('options');
        $sku = $this->product->findSku($product, $options);
        return AjaxResponse::success('addToCart', $sku);
    }

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
                'price' => $sku['price'],
                'qty' => request('qty'),
                'options' => [
                    'sku_options' => $options,
                    'selectedItemLocale' => request('selectedObjLocale'),
                    'selected' => false,
                    'image' => !empty($imagePath) ? $imagePath : $this->imagy->getThumbnail($product->featured_images->first()->path, 'smallThumb'),
                    'slug' => '/product/' . $product->slug,
                    'userId' => user()->id
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

            return AjaxResponse::success('添加成功',Cart::instance('cart')->content());
        }
    }

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
            return AjaxResponse::success('修改成功',$this->getSelectedTotal());
        }
    }

    public function updateStatus($rawId = null, $type=null)
    {
        $rawId = request('rowId');
        $type = request('type');

        $this->compareSessionVsDb();
        $item = Cart::instance('cart')->get($rawId);
        $item->options['selected'] = $type;
        Cart::instance('cart')->update($rawId, ['options.selected' => $type]);
        $this->updateDbcart();
        return AjaxResponse::success('修改成功',  Cart::instance('cart')->content()   );
    }

    //批量改状态 是否选中 并设置选中总价
    public function bulkUpdateStatus()
    {
        $contents = Cart::instance('cart')->content();
        foreach( $contents as $key=>$item ){
            Cart::instance('cart')->update($item->rowId, ['options.selected' =>  request('data') !== 0  ]);
        }
        $this->updateDbcart();
        return AjaxResponse::success('修改成功');
    }
    //删除数据库
    public function deleteCartItem(Product $product)
    {
        $rawId = request('rawId');
        $bool = Cart::instance('cart')->remove($rawId);
        $this->updateDbcart();
        return $bool ? AjaxResponse::success('修改成功') : AjaxResponse::fail('失败');
    }

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
    protected function compareSessionVsDb(){
        $dataFromDb = $this->getCartFromDb();
        if( !session()->has('cart.cart') && isset( $dataFromDb )   ){
            Cart::instance('cart')->add( $dataFromDb->toArray()  );
        }
    }
    //获取数据库cart对象
    protected function getCartFromDb(){
        if( ShoppingCart::count() == 0 ) return null;
        $cartInstance = ShoppingCart::where([
            'identifier' => user()->id,
            'instance'   => 'cart'
        ])->first()->content;
        $cartInstance = unserialize( $cartInstance );
        return $cartInstance;
    }
    //更新数据库cart
    protected function updateDbcart($dataToUpdateDatabase = []){
        ShoppingCart::where([
            'identifier' => user()->id,
            'instance'   => 'cart'
        ])->update(array_merge([
            'content' => serialize( Cart::instance('cart')->content() ),
            'selected_total' => $this->getSelectedTotal()
        ],$dataToUpdateDatabase));
    }
}