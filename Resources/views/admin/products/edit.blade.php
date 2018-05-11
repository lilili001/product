@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('product::products.title.edit product') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.product.product.index') }}">{{ trans('product::products.title.products') }}</a></li>
        <li class="active">{{ trans('product::products.title.edit product') }}</li>
    </ol>
@stop

@section('content')
{!! Form::open(['route' => ['admin.product.product.update', $product->id], 'method' => 'put','novalidate'=>"true"]) !!}
    <ul id="myTab" class="nav nav-tabs">
        <li class="active">
            <a href="#base" data-toggle="tab">
                基础信息
            </a>
        </li>
        <li><a href="#images" data-toggle="tab">图片信息</a></li>
        <li><a href="#sku" data-toggle="tab">sku属性</a></li>
        <li><a href="#attr" data-toggle="tab">销售属性</a></li>
    </ul>
    <a href="{{route('admin.product.product.index')}}"><button class="button btn-primary pull-right">返回列表</button></a>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="base">
            <div class="col-md-12" style="margin-top: 20px;">
                    {{--main content start--}}
                    
                    <div class="row">
                        <div class="col-md-10">
                            <div class="nav-tabs-custom">
                                @include('partials.form-tab-headers')

                                <div class="tab-content">
                                    <?php $i = 0; ?>
                                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                                        <?php $i++; ?>
                                        <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                                            @include('product::admin.products.partials.edit-fields', ['lang' => $locale])
                                        </div>
                                    @endforeach
                                        {{--@mediaSingle('profile_image', $product)--}}
                                </div>
                            </div> {{-- end nav-tabs-custom --}}
                        </div>

                    <div class="col-md-2">
                        {!! Form::label("attrset", 'attrset:') !!}
                        <!-- <select name="attrset_id" id="attrset_id" class="form-control" disabled>
                            <option value="">请选择</option>
                            <?php foreach ($attrsets as $set): ?>
                            <option value="{{ $set->id }}" {{ $set->id == $product->attrset_id ? 'selected' : ''  }}>{{ $set->name }}</option>
                            <?php endforeach; ?>
                        </select> -->

                        <attrset attrsets="{{json_encode($attrsets)}}" attrset-id="{{$product->attrset_id}}"></attrset>

                        {!! Form::label("category", 'category:') !!}
                        @if( !empty($cats)  )
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">请选择</option>
                            <?php foreach ($cats as $cat): ?>
                            <option value="{{ $cat->id }}" {{ !empty($product->cats->toArray()) && $cat->id == $product->cats->toArray()[0]['id'] ? 'selected' : ''  }}>{{ $cat->name }}</option>
                            <?php endforeach; ?>
                        </select>
                        @endif
                    </div>
                    </div>
                    {{--main content end--}}
            </div>

        </div>
        <div class="tab-pane fade" id="images">
            <div class="mar-t20">
                @mediaMultiple('gallery',$product)
            </div>
        </div>
        <div class="tab-pane fade" id="sku">
             @include('product::admin.products.partials.sku')
        </div>
        <div class="tab-pane fade" id="attr">
            <attr attrsets="{{ json_encode($attrsets)  }}"
                  fillsale="{{json_encode( $product->attr()->where('is_for_sku',false)->get()->toArray() )}}"
                  product="{{$product}}"
                  locale="{{locale()}}"></attr>
        </div>
    </div>

     <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                        <a class="btn btn-danger btn-flat" href="{{ route('admin.product.product.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                    </div>
    {!! Form::close() !!}
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index') }}</dd>
    </dl>
@stop

@push('js-stack')
<script src="{{mix('js/lib.js')}}"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.product.product.index') ?>" }
                ]
            });
        });
    </script>
    <script>
        $( document ).ready(function() {
            $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
                $("form").validate({
                    ignore: "",
                    invalidHandler: function(e, validator) {
                        var errors = validator.numberOfInvalids();
                        if (errors) {
                            var message = errors == 1
                                ? 'You missed 1 field. It has been highlighted below'
                                : 'You missed ' + errors + ' fields.  They have been highlighted below';
                            alert('校验失败');
                            $("div.error span").html(message);
                            $("div.error").show();
                        } else {
                            $("div.error").hide();
                        }
                    },
                    submitHandler:function(form){
                        alert("验证成功!");
                        form.submit();
                    }
                });

        });
    </script>
@endpush
