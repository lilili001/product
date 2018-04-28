<?php

namespace Modules\Product\Repositories\Eloquent;

use Modules\Product\Repositories\AttrsetRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentAttrsetRepository extends EloquentBaseRepository implements AttrsetRepository
{
    public function all()
    {
        if (method_exists($this->model, 'translations')) {
            return $this->model->with( ['attrs' ,'translations' ])->orderBy('created_at', 'DESC')->get();
        }

        return $this->model->orderBy('created_at', 'DESC')->get();
    }

    public function getAttrsBysetId($id)
    {
        return $this->model->where([
            'id'=>$id
        ])->with('attrs','translations')
            ->get();
    }
}
