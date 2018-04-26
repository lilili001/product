<?php

namespace Modules\Product\Http\Controllers;

use Modules\Core\Http\Controllers\BasePublicController;
use Modules\Product\Entities\Attr;
use Modules\Product\Entities\Attrset;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Repositories\ProductRepository;
use ShoppingCart;

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
        return view('product.index', compact('product'));
    }

    public function search()
    {
        $query = request('q');
        $products = Product::search($query)->paginate(24);
        return view('category.search', compact('products'));
    }


}