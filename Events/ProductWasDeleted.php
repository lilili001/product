<?php

namespace Modules\Product\Events;

use Modules\Media\Contracts\DeletingMedia;
use Modules\Product\Entities\Product;

class ProductWasDeleted implements DeletingMedia
{
    /**
     * @var product
     */
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the entity ID
     * @return int
     */
    public function getEntityId()
    {
        return $this->product->id;
    }

    /**
     * Get the class name the imageables
     * @return string
     */
    public function getClassName()
    {
        return get_class($this->product);
    }
}
