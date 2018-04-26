<?php

namespace Modules\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Media\Image\ThumbnailManager;
use Modules\Product\Events\Handlers\RegisterProductSidebar;

class ProductServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->app['events']->listen(BuildingSidebar::class, RegisterProductSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('attrsets', array_dot(trans('product::attrsets')));
            $event->load('products', array_dot(trans('product::products')));
            $event->load('categories', array_dot(trans('product::categories')));
            $event->load('images', array_dot(trans('product::images')));
            $event->load('skus', array_dot(trans('product::skus')));
            $event->load('attrs', array_dot(trans('product::attrs')));
            // append translations
        });

        $this->app[ThumbnailManager::class]->registerThumbnail('productThumb', [
            'resize' => [
                'width' => 600,
                'height' => 600,
                'callback' => function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                },
            ],
        ]);
    }

    public function boot()
    {
        $this->publishConfig('product', 'permissions');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerViewComposers();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Product\Repositories\AttrsetRepository',
            function () {
                $repository = new \Modules\Product\Repositories\Eloquent\EloquentAttrsetRepository(new \Modules\Product\Entities\Attrset());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Product\Repositories\Cache\CacheAttrsetDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Product\Repositories\ProductRepository',
            function () {
                $repository = new \Modules\Product\Repositories\Eloquent\EloquentProductRepository(new \Modules\Product\Entities\Product());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Product\Repositories\Cache\CacheProductDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Product\Repositories\CategoryRepository',
            function () {
                $repository = new \Modules\Product\Repositories\Eloquent\EloquentCategoryRepository(new \Modules\Product\Entities\Category());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Product\Repositories\Cache\CacheCategoryDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Product\Repositories\ImageRepository',
            function () {
                $repository = new \Modules\Product\Repositories\Eloquent\EloquentImageRepository(new \Modules\Product\Entities\Image());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Product\Repositories\Cache\CacheImageDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Product\Repositories\SkuRepository',
            function () {
                $repository = new \Modules\Product\Repositories\Eloquent\EloquentSkuRepository(new \Modules\Product\Entities\Sku());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Product\Repositories\Cache\CacheSkuDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Product\Repositories\AttrRepository',
            function () {
                $repository = new \Modules\Product\Repositories\Eloquent\EloquentAttrRepository(new \Modules\Product\Entities\Attr());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Product\Repositories\Cache\CacheAttrDecorator($repository);
            }
        );
// add bindings
    }
    private function registerViewComposers()
    {
        $this->app['view']->composer(
            'category.side-attrs',
            \Modules\Product\Composers\Frontend\SideAttrs::class
        );
    }
}
