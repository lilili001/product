<?php
namespace Modules\Product\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Repositories\SkuRepository;
use AjaxResponse;

class SkuController extends Controller
{
    protected $sku;
    protected $product;
    /**
     * ProductController constructor.
     * @param $product
     */
    public function __construct(SkuRepository $sku,ProductRepository $product)
    {
        $this->sku = $sku;
        $this->product = $product;
    }

    public function sku(Request $request,$productId)
    {
        $data = $request->all();
        $data['productId'] = $productId;

        $bool = $this->sku->create($data);
        return  $bool ? AjaxResponse::success('') : AjaxResponse::success('');
    }
}