<?php

namespace Modules\Product\Events;

use Modules\Media\Contracts\StoringMedia;
use Modules\Product\Entities\Product;

class ProductWasCreated implements StoringMedia
{
    /**
     * @var product
     */
    private $product;
    /**
     * @var array
     */
    private $data;

    public function __construct(Product $product, array $data)
    {
        $this->product = $product;
        $this->data = $data;
    }

    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity()
    {
        return $this->product;
    }

    /**
     * Return the ALL data sent
     * @return array
     */
    public function getSubmissionData()
    {
        return $this->data;
    }
}