<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['middleware' => 'api.token','prefix'=>'product'], function (Router $router) {
    $router->post('{productid}/sku', [
        'uses' => 'SkuController@sku',
        'as' => 'admin.product.product.edit.sku',
        'middleware' => 'token-can:product.products.edit'
    ]);

    $router->post('getAttrsByAttrsetId', [
        'uses' => 'AttrsetController@attrs',
        'as' => 'admin.product.attrset.attrs',
        'middleware' => 'token-can:product.products.edit'
    ]);

    // $router->post('{productid}/updateSaleAttrs', [
    //     'uses' => 'ProductController@updateSaleAttrs',
    //     'as' => 'admin.product.edit.updateSaleAttrs',
    //     'middleware' => 'token-can:product.products.edit'
    // ]);
});

