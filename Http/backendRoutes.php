<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/product'], function (Router $router) {
    $router->bind('attrset', function ($id) {
        return app('Modules\Product\Repositories\AttrsetRepository')->find($id);
    });
    $router->get('attrsets', [
        'as' => 'admin.product.attrset.index',
        'uses' => 'AttrsetController@index',
        'middleware' => 'can:product.attrsets.index'
    ]);
    $router->get('attrsets/create', [
        'as' => 'admin.product.attrset.create',
        'uses' => 'AttrsetController@create',
        'middleware' => 'can:product.attrsets.create'
    ]);
    $router->post('attrsets', [
        'as' => 'admin.product.attrset.store',
        'uses' => 'AttrsetController@store',
        'middleware' => 'can:product.attrsets.create'
    ]);
    $router->get('attrsets/{attrset}/edit', [
        'as' => 'admin.product.attrset.edit',
        'uses' => 'AttrsetController@edit',
        'middleware' => 'can:product.attrsets.edit'
    ]);
    $router->put('attrsets/{attrset}', [
        'as' => 'admin.product.attrset.update',
        'uses' => 'AttrsetController@update',
        'middleware' => 'can:product.attrsets.edit'
    ]);
    $router->delete('attrsets/{attrset}', [
        'as' => 'admin.product.attrset.destroy',
        'uses' => 'AttrsetController@destroy',
        'middleware' => 'can:product.attrsets.destroy'
    ]);
    $router->bind('product', function ($id) {
        return app('Modules\Product\Repositories\ProductRepository')->find($id);
    });
    $router->get('/', [
        'as' => 'admin.product.product.index',
        'uses' => 'ProductController@index',
        'middleware' => 'can:product.products.index'
    ]);
    $router->get('/create', [
        'as' => 'admin.product.product.create',
        'uses' => 'ProductController@create',
        'middleware' => 'can:product.products.create'
    ]);
    $router->post('products', [
        'as' => 'admin.product.product.store',
        'uses' => 'ProductController@store',
        'middleware' => 'can:product.products.create'
    ]);
    $router->get('/{product}/edit', [
        'as' => 'admin.product.product.edit',
        'uses' => 'ProductController@edit',
        'middleware' => 'can:product.products.edit'
    ]);
    $router->put('/{product}', [
        'as' => 'admin.product.product.update',
        'uses' => 'ProductController@update',
        'middleware' => 'can:product.products.edit'
    ]);

    $router->delete('/{product}', [
        'as' => 'admin.product.product.destroy',
        'uses' => 'ProductController@destroy',
        'middleware' => 'can:product.products.destroy'
    ]);
    $router->bind('category', function ($id) {
        return app('Modules\Product\Repositories\CategoryRepository')->find($id);
    });
    $router->get('categories', [
        'as' => 'admin.product.category.index',
        'uses' => 'CategoryController@index',
        'middleware' => 'can:product.categories.index'
    ]);
    $router->get('categories/create', [
        'as' => 'admin.product.category.create',
        'uses' => 'CategoryController@create',
        'middleware' => 'can:product.categories.create'
    ]);
    $router->post('categories', [
        'as' => 'admin.product.category.store',
        'uses' => 'CategoryController@store',
        'middleware' => 'can:product.categories.create'
    ]);
    $router->get('categories/{category}/edit', [
        'as' => 'admin.product.category.edit',
        'uses' => 'CategoryController@edit',
        'middleware' => 'can:product.categories.edit'
    ]);
    $router->put('categories/{category}', [
        'as' => 'admin.product.category.update',
        'uses' => 'CategoryController@update',
        'middleware' => 'can:product.categories.edit'
    ]);
    $router->delete('categories/{category}', [
        'as' => 'admin.product.category.destroy',
        'uses' => 'CategoryController@destroy',
        'middleware' => 'can:product.categories.destroy'
    ]);
    $router->bind('image', function ($id) {
        return app('Modules\Product\Repositories\ImageRepository')->find($id);
    });
    $router->get('images', [
        'as' => 'admin.product.image.index',
        'uses' => 'ImageController@index',
        'middleware' => 'can:product.images.index'
    ]);
    $router->get('images/create', [
        'as' => 'admin.product.image.create',
        'uses' => 'ImageController@create',
        'middleware' => 'can:product.images.create'
    ]);
    $router->post('images', [
        'as' => 'admin.product.image.store',
        'uses' => 'ImageController@store',
        'middleware' => 'can:product.images.create'
    ]);
    $router->get('images/{image}/edit', [
        'as' => 'admin.product.image.edit',
        'uses' => 'ImageController@edit',
        'middleware' => 'can:product.images.edit'
    ]);
    $router->put('images/{image}', [
        'as' => 'admin.product.image.update',
        'uses' => 'ImageController@update',
        'middleware' => 'can:product.images.edit'
    ]);
    $router->delete('images/{image}', [
        'as' => 'admin.product.image.destroy',
        'uses' => 'ImageController@destroy',
        'middleware' => 'can:product.images.destroy'
    ]);
    $router->bind('sku', function ($id) {
        return app('Modules\Product\Repositories\SkuRepository')->find($id);
    });
    $router->get('skus', [
        'as' => 'admin.product.sku.index',
        'uses' => 'SkuController@index',
        'middleware' => 'can:product.skus.index'
    ]);
    $router->get('skus/create', [
        'as' => 'admin.product.sku.create',
        'uses' => 'SkuController@create',
        'middleware' => 'can:product.skus.create'
    ]);
    $router->post('skus', [
        'as' => 'admin.product.sku.store',
        'uses' => 'SkuController@store',
        'middleware' => 'can:product.skus.create'
    ]);
    $router->get('skus/{sku}/edit', [
        'as' => 'admin.product.sku.edit',
        'uses' => 'SkuController@edit',
        'middleware' => 'can:product.skus.edit'
    ]);
    $router->put('skus/{sku}', [
        'as' => 'admin.product.sku.update',
        'uses' => 'SkuController@update',
        'middleware' => 'can:product.skus.edit'
    ]);
    $router->delete('skus/{sku}', [
        'as' => 'admin.product.sku.destroy',
        'uses' => 'SkuController@destroy',
        'middleware' => 'can:product.skus.destroy'
    ]);
    $router->bind('attr', function ($id) {
        return app('Modules\Product\Repositories\AttrRepository')->find($id);
    });
    $router->get('attrs', [
        'as' => 'admin.product.attr.index',
        'uses' => 'AttrController@index',
        'middleware' => 'can:product.attrs.index'
    ]);
    $router->get('attrs/create', [
        'as' => 'admin.product.attr.create',
        'uses' => 'AttrController@create',
        'middleware' => 'can:product.attrs.create'
    ]);
    $router->post('attrs', [
        'as' => 'admin.product.attr.store',
        'uses' => 'AttrController@store',
        'middleware' => 'can:product.attrs.create'
    ]);
    $router->get('attrs/{attr}/edit', [
        'as' => 'admin.product.attr.edit',
        'uses' => 'AttrController@edit',
        'middleware' => 'can:product.attrs.edit'
    ]);
    $router->put('attrs/{attr}', [
        'as' => 'admin.product.attr.update',
        'uses' => 'AttrController@update',
        'middleware' => 'can:product.attrs.edit'
    ]);
    $router->delete('attrs/{attr}', [
        'as' => 'admin.product.attr.destroy',
        'uses' => 'AttrController@destroy',
        'middleware' => 'can:product.attrs.destroy'
    ]);
    // append
    Route::get('/search', function (\Modules\Product\Repositories\ProductRepository $repository) {
        $data = $repository->search(  (string) request('q')  );

        return $data;
    });
});
