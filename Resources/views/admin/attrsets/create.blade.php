@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('product::attrsets.title.create attrset') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.product.attrset.index') }}">{{ trans('product::attrsets.title.attrsets') }}</a></li>
        <li class="active">{{ trans('product::attrsets.title.create attrset') }}</li>
    </ol>
@stop

@section('content')

        {!! Form::open(['route' => ['admin.product.attrset.store'], 'method' => 'post']) !!}
        <div class="row">
            <div class="col-md-10">
                <div class="nav-tabs-custom">
                    @include('partials.form-tab-headers')
                    <div class="tab-content">
                        <?php $i = 0; ?>
                        @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                            <?php $i++; ?>
                            <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                                @include('product::admin.attrsets.partials.create-fields', ['lang' => $locale])
                            </div>
                        @endforeach

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.create') }}</button>
                            <a class="btn btn-danger pull-right btn-flat" href="{{ route('admin.product.attrset.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                        </div>
                    </div>
                </div> {{-- end nav-tabs-custom --}}
            </div>

            <div class="col-md-2">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::label("pid", 'Parent:') !!}

                            <select name="pid" id="pid" class="form-control">
                                <option value="0">请选择</option>
                                <?php foreach ($attrsets as $attrset): ?>
                                <option value="{{ $attrset->id }}">{{ $attrset->name }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <!---  Field --->
                            <div class="form-group">
                                {!! Form::label('key', 'Key:') !!}
                                {!! Form::text('key', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <!---  Field --->
                            <div class="form-group">
                                {!! Form::label('sort_order', 'Order:') !!}
                                {!! Form::text('sort_order', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


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
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.product.attrset.index') ?>" }
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
