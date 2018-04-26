<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Media\Image\Imagy;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\ProductRepository;
use AjaxResponse;
use Cart;
use Modules\User\Entities\Address;
use ShoppingCart;

class CartController extends Controller
{
    protected $product;
    protected $attr;

    /**
     * ProductController constructor.
     * @param $product
     */
    public function __construct(ProductRepository $product, Imagy $imagy)
    {
        $this->imagy = $imagy;
        $this->product = $product;
    }

    public function getCurrentUserCart($type = false)
    {
        $items = [];
        foreach (Cart::contents() as $key => $item) {
            $equalUserId = $item->options['userId'] == user()->id;
            $condition = $type ? $equalUserId &&  $item->type == true : $equalUserId ;
            if ($condition) {
                $item->__raw_id = $key;
                $items[] = $item->toArray();
            }
        }
        return $items;
    }

    public function cart()
    {
        $items = $this->getCurrentUserCart();

        return view('cart', compact('items'));
    }

    public function checkout()
    {
        $user = user()->toArray();
        $items = $this->getCurrentUserCart(true);

        $addresses = Address::where([
            'user_id' => user()->id
        ])->get();

        return view('checkout',compact('items','user','addresses'));
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
                'quantity' => request('qty'),
                'tax' => 0,
                'options' => [
                    'sku_options' => $options,
                    'selectedItemLocale' => request('selectedItemLocale'),
                    'selected' => false,
                    'image' => !empty($imagePath) ? $imagePath : $this->imagy->getThumbnail($product->featured_images->first()->path, 'smallThumb'),
                    'slug' => '/product/' . $product->slug,
                    'userId' => user()->id
                ]
            ];
            Cart::insert($item);

            return AjaxResponse::success('添加成功', ShoppingCart::all());
        }
    }

    public function updateCart(Product $product)
    {
        $rawId = request('rawId');
        $qty = request('quantity');

        $item = Cart::item($rawId);
        $sku = $this->product->findSku($product, $item->options['sku_options']);

        if ($sku['stock'] < $qty) {
            return AjaxResponse::fail('库存不足', $sku['stock']);
        } else {
            Cart::update($rawId, 'quantity', $qty);
            return AjaxResponse::success('修改成功');
        }
    }

    public function updateStatus()
    {
        $rawId = request('rawId');
        $type = request('type');

        Cart::update($rawId,'type',$type);

        return AjaxResponse::success('修改成功');
    }

    public function deleteCartItem(Product $product)
    {
        $rawId = request('rawId');
        $bool = Cart::remove($rawId);
        return $bool ? AjaxResponse::success('修改成功') : AjaxResponse::fail('失败');
    }
}