<?php

namespace Modules\Product\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface ShoppingCartRepository extends BaseRepository
{
    public function getSelectedTotal();
    public function compareSessionVsDb();
    public function getCartFromDb();
    public function getCurrentUserCart($type = false);
    public function remove($rawId);
    public  function StrOrderOne();
    public function getSelectedAmount();
}
