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
                    <a href="{{ route('admin.product.product.selectAttrset') }}" class="btn btn-primary btn-flat mar-r4" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('product::products.button.create product') }}
                    </a>

                    <a href="javascript:;" data-toggle="modal" data-target="#bulk-import-modal" class="bulk-import btn btn-primary btn-flat mar-r4"  style="padding: 4px 10px;">
                        <i class="fa fa-upload"></i> {{ trans('product::products.button.bulk import') }}
                    </a>

                    <a href="javascript:;" class="bulk-delete btn btn-danger btn-flat" data-delete_url="{{route('admin.product.bulk-delete')}}" style="padding: 4px 10px;">
                        <i class="fa fa-remove"></i> {{ trans('product::products.button.bulk delete') }}
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
                                <th><input type="checkbox" id="all_checked"></th>
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

                            <tr data-id="{{$product->id}}">
                                <td><input name="row-check" type="checkbox" class="row-check"></td>
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
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('core::partials.delete-modal')
    @include('product::admin.products.partials.bulk-import-modal')
    @include('product::admin.products.partials.bulk-export-modal')
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
    <script src="/js/bulk-delete.js"></script>
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


            var str = '<div class="loading-mask"></div>';
            $(str).hide().appendTo('body')

            $('#bulk-import-modal button[type="submit"]').click(function(){
                bulkImport();
                $('#bulk-import-modal').modal('hide');
                return false;
            });
            function bulkImport(){

                var formData = new FormData($('#bulk-import-modal form')[0]);
                $.ajax({
                    type:'post',
                    url:route('admin.product.bulk-import'),
                    data:formData,

                    //使jq不处理数据类型和不设置Content-Type请求头
                    cache:false,
                    contentType:false,
                    processData:false,
                    beforeSend :function () {
                        $('.loading-mask').show();
                        console.log('发送ajax前');

                    },

                    success :function (data) {
                        if(data.code = '0'){
                            $('.loading-mask').hide();
                            //$('.loading-mask').remove();
                           fresh();
                            /*setTimeout(function () {
                                var success_url = "http://" + location.host + "/report/";

                            },2000)*/
                        }else{
                            alert("上传异常,请重新上传!");
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert("服务器错误,请重新上传");
                        fresh();
                    }
                });

            }

            function progressHandlingFunction(event) {
                if (event.lengthComputable) {
                    var value = (event.loaded / event.total * 100 | 0);
                    console.log(event.loaded);
                    $("#progress-bar").css('width', (value + '%'));
                }
            }

            function fresh() {
                window.location.reload();
            }
        });
    </script>
@endpush
