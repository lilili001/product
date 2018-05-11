<?php

namespace Modules\Product\Repositories\Cache;

use Modules\Product\Repositories\ImageRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheImageDecorator extends BaseCacheDecorator implements ImageRepository
{
    public function __construct(ImageRepository $image)
    {
        parent::__construct();
        $this->entityName = 'product.images';
        $this->repository = $image;
    }
}
