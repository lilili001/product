<?php

namespace Modules\Product\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\Sku;
use Modules\Product\Http\Requests\CreateSkuRequest;
use Modules\Product\Http\Requests\UpdateSkuRequest;
use Modules\Product\Repositories\SkuRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class SkuController extends AdminBaseController
{
    /**
     * @var SkuRepository
     */
    private $sku;

    public function __construct(SkuRepository $sku)
    {
        parent::__construct();

        $this->sku = $sku;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$skus = $this->sku->all();

        return view('product::admin.skus.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('product::admin.skus.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateSkuRequest $request
     * @return Response
     */
    public function store(CreateSkuRequest $request)
    {
        $this->sku->create($request->all());

        return redirect()->route('admin.product.sku.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('product::skus.title.skus')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Sku $sku
     * @return Response
     */
    public function edit(Sku $sku)
    {
        return view('product::admin.skus.edit', compact('sku'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Sku $sku
     * @param  UpdateSkuRequest $request
     * @return Response
     */
    public function update(Sku $sku, UpdateSkuRequest $request)
    {
        $this->sku->update($sku, $request->all());

        return redirect()->route('admin.product.sku.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('product::skus.title.skus')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Sku $sku
     * @return Response
     */
    public function destroy(Sku $sku)
    {
        $this->sku->destroy($sku);

        return redirect()->route('admin.product.sku.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('product::skus.title.skus')]));
    }
}
