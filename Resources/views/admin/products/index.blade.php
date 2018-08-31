@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('product::products.title.products') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('product::products.title.products') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.product.product.selectAttrset') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('product::products.button.create product') }}
                    </a>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">

                        <table class="data-table table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>  序号  </th>
                                <th>{{ trans('product::products.table.attrset') }}</th>
                                <th>{{ trans('product::products.table.productname') }}</th>
                                <th>{{ trans('product::products.table.price') }}</th>
                                <th>{{ trans('product::products.table.stock') }}</th>
                                <th>{{ trans('supplier::suppliers.form.supplier name') }}</th>
                                <th>{{ trans('product::products.table.status') }}</th>
                                <th>{{ trans('core::core.table.created at') }}</th>
                                <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($products)): ?>
                            <?php foreach ($products as $key=> $product): ?>

                            <tr>
                                <td>{{ $key+1   }}</td>
                                <td>{{ $product->attrset_id }}</td>
                                <td>{{ $product->title }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock }}</td>
                                <td><a href="{{route('admin.supplier.supplier.index')}}"> {{ !empty($product->supplier) ? $product->supplier->supplier_name : '' }}</a> </td>
                                <td>{{ $product->status }}</td>
                                <td>
                                    <a href="{{ route('admin.product.product.edit', [$product->id]) }}">
                                        {{ $product->created_at }}
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.product.product.edit', [$product->id]) }}"  >编辑</a>
                                        <a  data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.product.product.destroy', [$product->id]) }}">删除</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>{{ trans('core::core.table.created at') }}</th>
                                <th>{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('core::partials.delete-modal')
    <div id="app"></div>
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('product::products.title.create product') }}</dd>
    </dl>
@stop

@push('js-stack')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.product.product.selectAttrset') ?>" }
                ]
            });
        });
    </script>
    <?php $locale = locale(); ?>
    <script type="text/javascript">
        $(function () {
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[ 6, "desc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>
@endpush
