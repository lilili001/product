<?php

namespace Modules\Product\Entities;

use App\Libraries\EsSearchable;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Sale\Entities\OrderRefund;
use Modules\Supplier\Entities\Supplier;
use Overtrue\LaravelFollow\Traits\CanBeFavorited;
use Overtrue\LaravelFollow\Traits\CanBeLiked;

class Product extends Model
{
    use Translatable,MediaRelation,Searchable,EsSearchable,CanBeFavorited,CanBeLiked;

    protected $table = 'product__products';
    public $translatedAttributes = ['title','keywords','meta_description','description','slug'];
    protected $fillable = ['attrset_id', 'is_featured', 'status','sort_order','price','stock','slug',
        'title','keywords','meta_description','description','swatch_colors' ,'supplier_id' , 'supplier_price' ];

    public function sku()
    {
        return $this->hasMany(Sku::class);
    }

    public function attr()
    {
        return $this->hasMany(Attr::class);
    }

    public function cats()
    {
        return $this->belongsToMany(Category::class,'product__products_cats');
    }
    public function getId()
    {
        return $this->id;
    }

    public function getFeaturedImagesAttribute()
    {
        return $this->filesByZone('gallery')->get();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function toSearchableArray()
    {
        return array_only($this->toArray(), [ 'translations'   ]);
    }
}
