<?php

namespace Modules\Product\Repositories\Cache;

use Modules\Product\Repositories\AttrRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheAttrDecorator extends BaseCacheDecorator implements AttrRepository
{
    public function __construct(AttrRepository $attr)
    {
        parent::__construct();
        $this->entityName = 'product.attrs';
        $this->repository = $attr;
    }
}
