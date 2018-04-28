<?php

namespace Modules\Product\Composers\Frontend;

use Illuminate\Contracts\View\View;
use Modules\Attribute\Repositories\AttributeRepository;
use Modules\Blog\Repositories\PostRepository;
use Modules\Setting\Contracts\Setting;


class SideAttrs
{
    /**
     * @var PostRepository
     */
    private $attribute;
    /**
     * @var Setting
     */
    private $setting;

    public function __construct( AttributeRepository $attribute, Setting $setting)
    {
        $this->attribute = $attribute;
        $this->setting = $setting;
    }

    public function compose(View $view)
    {
        $attrs = $this->attribute->findByCondition('is_filterable',1);
        $view->with('filterableAttrs', $attrs);
    }
}
