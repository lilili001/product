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
    <el-card>

        {{--base tab content--}}
        <div id="base">
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

                    <attr attrsets="{{ json_encode($attrsets) }}" locale="{{locale()}}"></attr>
                    <sku locale="{{locale()}}"></sku>
                    <hr>
                    @mediaMultiple('gallery')
                    <hr>
                    @include('product::admin.products.partials.supplier')

                </div>
                <div class="col-md-2">
                    <div class="form-group{{ $errors->has("attrset_id") ? ' has-error' : '' }}">
                        {!! Form::label("attrset", 'attrset:') !!}
                        <attrset attrsets="{{json_encode($attrsets)}}" attrset-id="{{$attrset}}"></attrset>
                        {!! $errors->first("attrset_id", '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group{{ $errors->has("category_id") ? ' has-error' : '' }}">
                        {!! Form::label("category", 'category:') !!}
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">请选择</option>
                            <?php foreach ($cats as $cat): ?>
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            <?php endforeach; ?>
                        </select>
                        {!! $errors->first("category_id", '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group{{ $errors->has("is_featured") ? ' has-error' : '' }}">
                        {!! Form::label("is featured", 'Is Featured:') !!}
                        <select name="is_featured" id="is_featured" class="form-control">
                            <option value="">请选择</option>
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                        {!! $errors->first("is_featured", '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group{{ $errors->has("status") ? ' has-error' : '' }}">
                        {!! Form::label("Status", 'Status:') !!}
                        <select name="status"  class="form-control">
                            <option value="">请选择</option>
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                        {!! $errors->first("status", '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>
        {{--image tab content--}}

        <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.create') }}</button>
            <a class="btn btn-danger btn-flat" href="{{ route('admin.product.product.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
        </div>
    </el-card>
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
    <script src="/js/slug.js"></script>
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
