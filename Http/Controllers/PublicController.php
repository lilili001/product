<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\Product\Entities\Attr;
use Modules\Product\Entities\Attrset;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Sale\Entities\OrderReview;
use ShoppingCart;
use AjaxResponse;

class PublicController extends BasePublicController
{

    /**
     * PublicController constructor.
     */
    protected $cat;
    protected $product;

    public function __construct(CategoryRepository $cat, ProductRepository $product)
    {
        $this->cat = $cat;
        $this->product = $product;
    }

    public function index()
    {
        return view('category.index');
    }

    public function cat($slug)
    {
        $params = request()->all();
        $cat = $this->cat->findBySlug($slug);
        $data = $this->product->handleSearch($params, $cat);
        return view('category.index', $data);
    }

    public function productDetail($slug)
    {
        $product = $this->product->findBySlug($slug);
        $reviews = OrderReview::where([
            'goods_id' => $product->id
        ])->with('replies')->get();

        $cat = $product->cats->toArray();
        $cat = $cat[0]['id'];
        //related products

        $relatedProducts = Product::with(['cats'=>function($q)use($cat){
            $q->where('category_id' , $cat);
        }])->inRandomOrder()
            ->take(5)
            ->get();

        $favorite_count = count( $product->favoriters()->get() );

        return view('product.index', compact('product','reviews','favorite_count' ,'relatedProducts' ));
    }

    public function search()
    {
        $query = request('q');
        $products = Product::search($query)->paginate(24);
        dd($products);
        return view('category.search', compact('products'));
    }

    public function addToFavorite(Request $request)
    {
        try{
            $product = Product::find($request->get('id'));
            $res = user()->toggleFavorite($product);
        }catch (Exception $e){
            return AjaxResponse::fail('',$e->getMessage());
        }
        return  AjaxResponse::success('',$res);
    }
}