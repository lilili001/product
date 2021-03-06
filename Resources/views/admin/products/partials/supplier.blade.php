<div class="form-horizontal">
    <div class="form-group mar-t10">
        <label class="col-sm-1" for="">供应商</label>
        <div class="col-sm-11">
        <select name="supplier_id" class="form-control" id="" >
            <option value="">请选择</option>
            @foreach($suppliers as $supplier)
                <option value="{{$supplier->id}}" {{ isset($product->supplier) && $supplier->id == $product->supplier->id ? 'selected' : ''  }}>{{$supplier->supplier_name}}</option>
            @endforeach
        </select>
        </div>
    </div>

    <div class="form-group mar-t10">
        <label class="col-sm-1" for="">供应商商品url</label>
        <div class="col-sm-11">
            <input type="text" value="{{ isset( $product->supplier_product_url ) ? $product->supplier_product_url : ''  }}" name="supplier_product_url" class="form-control" placeholder="">
        </div>
    </div>

    <div class="form-group mar-t10">
        <label class="col-sm-1" for="">供应商价格(rmb)</label>
        <div class="col-sm-11">
        <input type="text" value="{{ isset( $product->supplier_price ) ? $product->supplier_price : ''  }}" name="supplier_price" class="form-control" placeholder="">
        </div>
    </div>
</div>