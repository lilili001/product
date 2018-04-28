<?php

namespace Modules\Product\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Product\Entities\Product;

class ProductIsUpdating extends AbstractEntityHook implements EntityIsChanging
{
    /**
     * @var product
     */
    private $product;

    public function __construct(Product $product, array $data)
    {
        parent::__construct($data);

        $this->product = $product;
    }

    /**
     * @return product
     */
    public function getPost()
    {
        return $this->product;
    }
}
