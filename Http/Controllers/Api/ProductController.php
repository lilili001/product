<?php
namespace Modules\Product\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\ProductRepository;
use AjaxResponse;

class ProductController extends Controller
{
    protected $product;
    protected $attr;
    /**
     * ProductController constructor.
     * @param $product
     */
    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
    }

    public function updateSaleAttrs(Request $request, $productid)
    {
        $data = $request->all();
        $product = $this->product->find($productid);
        $bool = $this->product->updateSaleAttrs($product,$data);
        return   AjaxResponse::success('')  ;
    }
}