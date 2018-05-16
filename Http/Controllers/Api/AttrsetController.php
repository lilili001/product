<?php
namespace Modules\Product\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Repositories\AttrsetRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Repositories\SkuRepository;
use AjaxResponse;

class AttrsetController extends Controller
{
    protected $attrset;
    /**
     * ProductController constructor.
     * @param $product
     */
    public function __construct(AttrsetRepository $attrset)
    {
        $this->attrset = $attrset;
    }

    public function all()
    {
        return $this->attrset->all();
    }
    //根据属性集id获取属性列表
    public function attrs()
    {
        $attrset_id = request('attrset_id');

        return $this->attrset->getAttrsBysetId($attrset_id );
    }
}