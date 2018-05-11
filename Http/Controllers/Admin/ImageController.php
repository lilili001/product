<?php

namespace Modules\Product\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\Image;
use Modules\Product\Http\Requests\CreateImageRequest;
use Modules\Product\Http\Requests\UpdateImageRequest;
use Modules\Product\Repositories\ImageRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class ImageController extends AdminBaseController
{
    /**
     * @var ImageRepository
     */
    private $image;

    public function __construct(ImageRepository $image)
    {
        parent::__construct();

        $this->image = $image;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$images = $this->image->all();

        return view('product::admin.images.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('product::admin.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateImageRequest $request
     * @return Response
     */
    public function store(CreateImageRequest $request)
    {
        $this->image->create($request->all());

        return redirect()->route('admin.product.image.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('product::images.title.images')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Image $image
     * @return Response
     */
    public function edit(Image $image)
    {
        return view('product::admin.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Image $image
     * @param  UpdateImageRequest $request
     * @return Response
     */
    public function update(Image $image, UpdateImageRequest $request)
    {
        $this->image->update($image, $request->all());

        return redirect()->route('admin.product.image.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('product::images.title.images')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Image $image
     * @return Response
     */
    public function destroy(Image $image)
    {
        $this->image->destroy($image);

        return redirect()->route('admin.product.image.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('product::images.title.images')]));
    }
}
