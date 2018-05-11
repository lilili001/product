<?php

namespace Modules\Product\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\Attr;
use Modules\Product\Http\Requests\CreateAttrRequest;
use Modules\Product\Http\Requests\UpdateAttrRequest;
use Modules\Product\Repositories\AttrRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class AttrController extends AdminBaseController
{
    /**
     * @var AttrRepository
     */
    private $attr;

    public function __construct(AttrRepository $attr)
    {
        parent::__construct();

        $this->attr = $attr;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$attrs = $this->attr->all();

        return view('product::admin.attrs.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('product::admin.attrs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateAttrRequest $request
     * @return Response
     */
    public function store(CreateAttrRequest $request)
    {
        $this->attr->create($request->all());

        return redirect()->route('admin.product.attr.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('product::attrs.title.attrs')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Attr $attr
     * @return Response
     */
    public function edit(Attr $attr)
    {
        return view('product::admin.attrs.edit', compact('attr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Attr $attr
     * @param  UpdateAttrRequest $request
     * @return Response
     */
    public function update(Attr $attr, UpdateAttrRequest $request)
    {
        $this->attr->update($attr, $request->all());

        return redirect()->route('admin.product.attr.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('product::attrs.title.attrs')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Attr $attr
     * @return Response
     */
    public function destroy(Attr $attr)
    {
        $this->attr->destroy($attr);

        return redirect()->route('admin.product.attr.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('product::attrs.title.attrs')]));
    }
}
