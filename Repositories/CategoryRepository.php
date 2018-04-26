<?php

namespace Modules\Product\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface CategoryRepository extends BaseRepository
{
    public function getAllCats();
}
