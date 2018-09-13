<?php

namespace Modules\Product\Repositories\Cache;

use Modules\Product\Repositories\SkuRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheSkuDecorator extends BaseCacheDecorator implements SkuRepository
{
    public function __construct(SkuRepository $sku)
    {
        parent::__construct();
        $this->entityName = 'product.skus';
        $this->repository = $sku;
    }
}
