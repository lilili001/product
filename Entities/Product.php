<?php

namespace Modules\Product\Entities;

use App\Libraries\EsSearchable;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use MiyaYeh\Trans\BdTrans;
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
    protected $fillable = ['attrset_id', 'is_featured', 'status','sort_order','price','stock','slug','supplier_product_url',
        'title','keywords','meta_description','description','swatch_colors' ,'supplier_id' , 'supplier_price',
        'size_obj','special_price','date_special_price','special_from','special_to'
        ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (! $this->exists) {
            $this->setUniqueSlug($value, '');
        }
    }

    /**
     * Recursive routine to set a unique slug
     *
     * @param string $title
     * @param mixed $extra
     */
    protected function setUniqueSlug($title, $extra)
    {
        $slug = BdTrans::slug($title);

        if (static::whereSlug($slug)->exists()) {
            $this->setUniqueSlug($title, $extra + 1);
            return;
        }

        $this->attributes['slug'] = $slug;
    }

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
