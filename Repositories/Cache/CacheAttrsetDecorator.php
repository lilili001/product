<?php

namespace Modules\Product\Repositories\Cache;

use Modules\Product\Repositories\AttrsetRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheAttrsetDecorator extends BaseCacheDecorator implements AttrsetRepository
{
    public function __construct(AttrsetRepository $attrset)
    {
        parent::__construct();
        $this->entityName = 'product.attrsets';
        $this->repository = $attrset;
    }
}
