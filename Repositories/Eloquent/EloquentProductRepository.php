<?php

namespace Modules\Product\Repositories\Eloquent;

//use Modules\Product\Events\ProductIsCreating;
//use Modules\Product\Events\ProductIsUpdating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Mockery\Exception;
use Modules\Product\Entities\Attr;
use Modules\Product\Entities\Attrset;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Events\ProductWasCreated;
use Modules\Product\Events\ProductWasDeleted;
use Modules\Product\Events\ProductWasUpdated;
use Modules\Product\Repositories\ProductRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Product\Support\Util;
use AjaxResponse;

class EloquentProductRepository extends EloquentBaseRepository implements ProductRepository
{

    public function create($data)
    {

        return DB::transaction(function () use ($data) {
            $product = $this->model->create($data);

            if( isset( $data['date_special_price'] ) ){
                $special_date = [
                    'special_from' => explode(',',$data['date_special_price'])[0],
                    'special_to'   => explode(',',$data['date_special_price'])[1]
                ];
                $product->update($special_date);
            }

            $product->cats()->sync([$data['category_id']]);

            //处理sku相关数据
            if (!empty($data['sku_data']) && $data['sku_data'] !== "{}" ) {
                $this->handleSkuData($data['sku_data'], $product);
            }

            //处理色卡
            if (!empty($data['swatch_color']) && $data['swatch_color'] !== "{}" ) {
                $this->handleColorSwatch($data['swatch_color'], $product);
            }

            //处理销售属性相关数据
            if (!empty($data['sale_attr_data']) && $data['sale_attr_data'] !== "{}"  ) {
                $this->handleSaleAttrData($data['sale_attr_data'], $product);
            }

            event(new ProductWasCreated($product, $data));
            return $product;
        });
    }

    public function update($product, $data)
    {
        info($data);
        return DB::transaction(function () use ($product, $data) {
            $product->update($data);

            if (isset($data['category_id'])) {
                $product->cats()->sync([$data['category_id']]);
            }
            //处理sku相关数据
            if (!empty($data['sku_data']) ) {
                //info( $data['skuData'] );
                $this->handleSkuData($data['sku_data'], $product);
            }
            //处理色卡
            if (!empty($data['swatch_color']) ) {
                $this->handleColorSwatch($data['swatch_color'], $product);
            }
            //处理销售属性相关数据
            if (!empty($data['sale_attr_data']) ) {
                $this->handleSaleAttrData($data['sale_attr_data'], $product);
            }
            event(new ProductWasUpdated($product, $data));
            return $product;
        });
    }

    public function destroy($product)
    {
        event(new ProductWasDeleted($product));
        return DB::transaction(function () use ($product) {
            try {
                $product->translations()->delete();
                $product->attr()->delete();
                $product->sku()->delete();
                $product->delete();
            } catch (Exception $e) {
                return AjaxResponse::fail('', [
                    'errCode' => $e->getCode(),
                    'errMsg' => $e->getMessage()
                ]);
            }
        });
    }

    public function getAllAttrsets()
    {
        return Attrset::all();
    }

    public function getAttrsByProductId($productId, $isForSku)
    {
        $product = Product::find($productId);
        $attrs = $product->attr()->where('is_for_sku', $isForSku)->get();
        return $attrs;
    }

    protected function handleSkuData($data, $product)
    {
        if( $data['tableData6'] == "[]" || $data['checkList'] == "{}" ) return;
        if( gettype($data['tableData6']) == 'string' ){
            $tableData6 = json_decode($data['tableData6'],true);
            $skuCheckList = json_decode($data['checkList'],true);
        }else{
            $tableData6 = $data['tableData6'];
            $skuCheckList = $data['checkList'];
        }

        //dd($skuCheckList);return;
        //dd(($skuCheckList));
        //更新product price和stock
        /*$product->update([
            'price' => $data['price'],
            'stock' => $data['stock']
        ]);*/

        //获取sku options列表
        $dataSkus = (new Util())->assignSkuIds($tableData6, $product->id);
        //开始操作数据库
        $product->sku()->delete();
        //sku table
        DB::table('product__skus')->insert($dataSkus);

        //获取选中的sku 属性值列表
        $dataAttrSkus = (new Util())->assignAttrIds($skuCheckList, $product->id, true);
        //skuattr table
        $product->attr()->delete();
        DB::table('product__attrs')->insert($dataAttrSkus);
    }

    protected function handleSaleAttrData($data, $product)
    {
        if($data == "{}") return;
        //获取选中的sku 属性值列表
        $data = json_decode($data);
        $dataAttrs = (new Util())->assignAttrIds($data, $product->id);
        //skuattr table
        $product->attr()->where('is_for_sku', false)->delete();

        DB::table('product__attrs')->insert($dataAttrs);
    }

    protected function handleColorSwatch($data, $product)
    {
        //去掉域名
        $pattern = '/http:\/+.*?\//';
        $bool = $product->update([
            'swatch_colors' => preg_replace($pattern ,'/',$data)
        ]);
    }

    public function search($query = "")
    {
        $data = $this->model->search($query)->paginate(24);
        return $data;
    }

    public function handleSearch($params, $cat)
    {
        return (new Util())->checkIfHasProducts($params, $cat->id);
    }

    public function findSku($product,$options)
    {
        $res = [];
        $settingsList = $product->sku->pluck('settings');
        foreach($settingsList as $setting){
            $heji = array_intersect( $setting , $options );
            if(  !empty(  $heji ) ){
                if(  count( $heji ) == count($options) ){
                    $res = $setting;
                    break;
                }
            }
        }
        return $res;
    }
}
