<?php

namespace Modules\Product\Support;

use Carbon\Carbon;
use Modules\Product\Entities\Attr;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;

class Util
{
    //sku table
    public function assignSkuIds(array $data = [], $product_id = null)
    {
        return array_map(function ($item) use ($product_id) {
            $temp = [];
            $temp['product_id'] = $product_id;
            $temp['settings'] = json_encode($item);
            $temp['created_at'] = Carbon::now();
            $temp['updated_at'] = Carbon::now();
            return $temp;
        }, $data);
    }

    //attr table
    public function assignAttrIds($data, $product_id, $isSku = false)
    {

        if (is_array($data) || is_object($data)) {
            info($isSku . '|' . gettype($data));
            foreach ($data as $key => $item) {
                $arr[] = [
                    'attr_key' => $key,
                    'value' => json_encode($item),
                    'product_id' => $product_id,
                    'is_for_sku' => $isSku,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        } else {
            $arr = [];
            info($isSku . '| not an array nor an object' . $data);
        }
        return $arr;
    }

    public function checkIfHasProducts($query_strings, $catId, $isForUrlCheck = 0)
    {
        //当前分类下的所有产品
        $cat = Category::find($catId);
        $allproducts = $cat->products;
        //当前分类下的所有产品id数组集合
        $allproductIds = $allproducts->pluck('id')->toArray();//all product ids
        //当前分类
        $catId = $cat->id;
        if (empty($query_strings)) {
            //没有筛选参数时 只显示当前分类下的所有产品
            $products = $cat->products()->paginate(24);
        } else {
            //根据筛选条件 根据product__attr查询 筛选条件 得出产品集合 ids
            $result = [];
            $firstVal = reset($query_strings);

                foreach ($query_strings as $v1 => $v2) {
                    if (strstr($v2, '-')) {
                        $words = explode('-', $v2);
                        static $query;
                        $i = 0;
                        while ($i < count($words)) {
                            if ($i == 0) {
                                $query = Attr::where([
                                    'attr_key' => $v1,
                                    ['value', 'like', '%' . $words[0] . '%']
                                ]);
                            } else {
                                $query = $query->orWhere([
                                    'attr_key' => $v1,
                                    ['value', 'like', '%' . $words[$i] . '%']
                                ]);
                            }
                            $i++;
                        }

                        $newPdcIds = $query->pluck('product_id')->toArray();
                    } else {
                        $newPdcIds = Attr::where([
                            'attr_key' => $v1,
                            ['value', 'like', '%' . $v2 . '%']
                        ])->pluck('product_id')->toArray();
                    }
                    $result = $v2 == $firstVal ? $newPdcIds : array_intersect($result, $newPdcIds);
                }



            //获得查询的产品结果
            $products = Product::whereIn('id', $result)->whereHas(
                'cats', function ($q) use ($catId) {
                $q->where('category_id', $catId);
            }
            )->paginate(24);

            //现在的所有产品是当前筛选结果的范围
            $allproducts = $products;
        }
        return compact('allproductIds', 'products', 'allproducts', 'catId');
    }
}