<?php

namespace Modules\Product\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\Attrset;
use Modules\Product\Http\Requests\CreateAttrsetRequest;
use Modules\Product\Http\Requests\UpdateAttrsetRequest;
use Modules\Product\Repositories\AttrsetRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class AttrsetController extends AdminBaseController
{
    /**
     * @var AttrsetRepository
     */
    private $attrset;

    public function __construct(AttrsetRepository $attrset)
    {
        parent::__construct();

        $this->attrset = $attrset;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $attrsets = $this->attrset->all();

        return view('product::admin.attrsets.index', compact('attrsets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $attrsets = $this->attrset->all();

        return view('product::admin.attrsets.create',compact('attrsets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateAttrsetRequest $request
     * @return Response
     */
    public function store(CreateAttrsetRequest $request)
    {
        $this->attrset->create($request->all());

        return redirect()->route('admin.product.attrset.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('product::attrsets.title.attrsets')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Attrset $attrset
     * @return Response
     */
    public function edit(Attrset $attrset)
    {
        $attrsets = $this->attrset->all();
        return view('product::admin.attrsets.edit', compact('attrset','attrsets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Attrset $attrset
     * @param  UpdateAttrsetRequest $request
     * @return Response
     */
    public function update(Attrset $attrset, UpdateAttrsetRequest $request)
    {
        $this->attrset->update($attrset, $request->all());

        return redirect()->route('admin.product.attrset.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('product::attrsets.title.attrsets')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Attrset $attrset
     * @return Response
     */
    public function destroy(Attrset $attrset)
    {
        $this->attrset->destroy($attrset);

        return redirect()->route('admin.product.attrset.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('product::attrsets.title.attrsets')]));
    }
}
