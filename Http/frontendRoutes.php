<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group([], function (Router $router) {
    $locale = LaravelLocalization::setLocale() ?: App::getLocale();
    $router->get('/all', [
        'as' => $locale . '.cat',
        'uses' => 'PublicController@index',
        //'middleware' => config('asgard.product.config.middleware'),
    ]);
    $router->get('/c/{slug}', [
        'as' => $locale . '.cat.slug',
        'uses' => 'PublicController@cat',
        //'middleware' => config('asgard.product.config.middleware'),
    ]);
    $router->get('product/{slug}', [
        'as' => $locale . '.product.slug',
        'uses' => 'PublicController@productDetail',
        //'middleware' => config('asgard.product.config.middleware'),
    ]);

    $router->post('{product}/getSku', [
        'uses' => 'CartController@getSku',
        'as' => $locale . '.getPrice',
    ]);

    $router->post('{product}/addToCart', [
        'uses' => 'CartController@addToCart',
        'as' => $locale . '.addToCart',
        'middleware' => 'logged.in'
    ]);
    $router->post('{product}/updateCart', [
        'uses' => 'CartController@updateCart',
        'as' =>  'updateCart',
        'middleware' => 'logged.in'
    ]);
    $router->post('{product}/deleteCartItem', [
        'uses' => 'CartController@deleteCartItem',
        'as' =>   'deleteCartItem',
        'middleware' => 'logged.in'
    ]);
    $router->post('{product}/updateStatus', [
        'uses' => 'CartController@updateStatus',
        'as' =>   'updateStatus',
        'middleware' => 'logged.in'
    ]);

    $router->get('cart',[
        'as' => $locale . '.product.cart',
        'uses' => 'CartController@cart',
        'middleware' => 'logged.in'
    ]);
    $router->get('selectcart',[
        'as' => $locale . '.product.selectcart',
        'uses' => 'CartController@selectcart',
        'middleware' => 'logged.in'
    ]);
    $router->get('checkout',[
        'as' => $locale . '.product.checkout',
        'uses' => 'CartController@checkout',
        'middleware' => 'logged.in'
    ]);

});

$router->group(['prefix'=>'elastic'],function(Router $router){
    $router->get('test',['uses' => 'ClientController@elasticsearchTest']);
    $router->get('query',['uses' => 'ClientController@elasticsearchQueries']);
});

$router->group(['prefix'=>'search'],function(Router $router){
    $locale = LaravelLocalization::setLocale() ?: App::getLocale();
    $router->get('/result',['as' => $locale . '.search.result', 'uses' => 'PublicController@search']);
});
