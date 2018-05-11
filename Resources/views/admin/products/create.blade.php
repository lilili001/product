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
    {!! Form::open(['route' => ['admin.product.product.store'], 'method' => 'post']) !!}

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
    <div id="myTabContent" class="tab-content">
        {{--base tab content--}}
        <div class="tab-pane fade in active" id="base">
            <div class="row mar-t20">
                <div class="col-md-10">
                    <div class="nav-tabs-custom">
                        @include('partials.form-tab-headers')
                        <div class="tab-content">
                            <?php $i = 0; ?>
                            @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                                <?php $i++; ?>
                                <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                                    @include('product::admin.products.partials.create-fields', ['lang' => $locale])
                                </div>
                            @endforeach
                        </div>
                        {{--@mediaSingle('profile_image')--}}
                    </div> {{-- end nav-tabs-custom --}}
                </div>
                <div class="col-md-2">
                    {!! Form::label("attrset", 'attrset:') !!}
                    <attrset attrsets="{{json_encode($attrsets)}}"></attrset>
                    {!! Form::label("category", 'category:') !!}
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">请选择</option>
                        <?php foreach ($cats as $cat): ?>
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        {{--image tab content--}}
        <div class="tab-pane fade" id="images">
            <div class="mar-t20">
                @mediaMultiple('gallery')
            </div>
        </div>
        {{--sku tab content--}}
        <div class="tab-pane fade" id="sku">
            <sku
                 locale="{{locale()}}"
                  >
            </sku>
        </div>
        {{--attr tab content--}}
        <div class="tab-pane fade" id="attr">
        <attr attrsets="{{ json_encode($attrsets)}}"
                  locale="{{locale()}}"></attr>
        </div>
    </div>

    <div class="box-footer">
        <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.create') }}</button>
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
        });
    </script>
@endpush
