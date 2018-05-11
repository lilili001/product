<?php

namespace Modules\Product\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Media\Repositories\FileRepository;
use Modules\Product\Entities\Attrset;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\CreateProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class ProductController extends AdminBaseController
{
    /**
     * @var ProductRepository
     */
    private $product;
    private $file;
    public function __construct(ProductRepository $product,FileRepository $file,CategoryRepository $cat)
    {
        parent::__construct();
        $this->file = $file;
        $this->product = $product;
        $this->cat = $cat;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $products = $this->product->all();
        return view('product::admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $attrsets = $this->product->getAllAttrsets();
        $cats = $this->cat->getAllCats();
        return view('product::admin.products.create',compact('attrsets','cats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProductRequest $request
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {
        $product = $this->product->create($request->all());
        //return redirect()->route('admin.product.product.edit',['product'=>$product]);
        return redirect()->route('admin.product.product.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('product::products.title.products')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        $attrsets = $this->product->getAllAttrsets();
        $cats = $this->cat->getAllCats();
        return view('product::admin.products.edit', compact('product','attrsets','cats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Product $product
     * @param  UpdateProductRequest $request
     * @return Response
     */
    public function update(Product $product, UpdateProductRequest $request)
    {
        $this->product->update($product, $request->all());

        //return redirect()->back();
        return redirect()->route('admin.product.product.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('product::products.title.products')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product $product
     * @return Response
     */
    public function destroy(Product $product)
    {
        $this->product->destroy($product);

        return redirect()->route('admin.product.product.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('product::products.title.products')]));
    }
}
