<?php
    $attrset = \Modules\Product\Entities\Attrset::find( $product->attrset_id );
    $skuAttrs = $attrset->attrs()->where('is_for_sku',true)
        ->get()->toArray() ;

    $num1 = count( $skuAttrs );
    $num2 = count( $product->attr()->where('is_for_sku',true)->get()->toArray() );
?>
<sku 
     pdc="{{$num1 == $num2 ? json_encode($product) :null }}"
     locale="{{locale()}}"
     filled-attr="{{ $num1 == $num2 ? json_encode($product->attr()->where('is_for_sku',true)->get()) : null }}"
     filled-sku="{{ $num1 == $num2 ? json_encode($product->sku) : null }}">
</sku>
