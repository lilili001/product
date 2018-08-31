@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('product::products.title.create product') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.product.product.index') }}">{{ trans('product::products.title.products') }}</a></li>
        <li class="active">{{ trans('product::products.title.create product') }}</li>
    </ol>
@stop

@section('content')
      <div class="box box-info">
          <div class="box-header with-border">
              <h3 class="box-title">类目选择</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form class="form-horizontal" action="{{route('admin.product.product.create')}}">
              <div class="box-body" style="min-height: 100px; ">
                  <div class="form-group">
                      <label for="inputEmail3" class="col-sm-3 control-label">属性集</label>
                      <div class="col-sm-6">
                          <select name="attrset" class="form-control">
                              @foreach( $attrsets as $attrset )
                                  <option value="{{$attrset->id}}">{{$attrset->key}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer text-center" style="position:relative">
                  <button type="submit" class="btn btn-info ">提交</button>
              </div>
              <!-- /.box-footer -->
          </form>
      </div>
@stop

