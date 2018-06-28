<?php

namespace Modules\Product\Repositories\Cache;

use Modules\Product\Repositories\ShoppingCartRepository;
use Modules\Product\Repositories\SkuRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheShoppingCartDecorator extends BaseCacheDecorator implements SkuRepository
{
    public function __construct(ShoppingCartRepository $shoppingCart)
    {
        parent::__construct();
        $this->entityName = 'product.shoppingCarts';
        $this->repository = $shoppingCart;
    }
}
