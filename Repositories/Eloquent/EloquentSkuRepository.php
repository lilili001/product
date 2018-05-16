<?php

namespace Modules\Product\Repositories\Eloquent;

use Mockery\Exception;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\SkuRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Product\Support\Util;
use DB;
class EloquentSkuRepository extends EloquentBaseRepository implements SkuRepository
{
    public function create($data)
    {
        return DB::transaction(function()use ($data){
           try{

               $productId = $data['productId'];
               $product = Product::find($data['productId']);
               $tableData6 = $data['tableData6'];
               $skuCheckList = $data['checkList'];

               //更新product price和stock
               $product->update([
                   'price' => $data['price'],
                   'stock' => $data['stock']
               ]);

               //获取sku options列表
               $dataSkus = (new Util())->assignSkuIds($tableData6,$productId);
               //获取选中的sku 属性值列表
               $dataAttrSkus = (new Util())->assignAttrIds($skuCheckList,$productId,true);

               //开始操作数据库
               $product->sku()->delete();
               //sku table
               DB::table('product__skus')->insert( $dataSkus );

               //skuattr table
               $product->attr()->delete();
               DB::table('product__attrs')->insert($dataAttrSkus);

           }catch (Exception $e){
               return AjaxResponse::fail('',[
                   'errCode' => $e->getCode(),
                   'errMsg'  => $e->getMessage()
               ]);
           }
        });
    }
}
