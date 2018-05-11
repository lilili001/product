<?php

namespace Modules\Product\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterProductSidebar implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('product::products.title.products'), function (Item $item) {
                $item->icon('fa fa-copy');
                $item->weight(10);
                $item->authorize(
                     /* append */
                );



                $item->item(trans('attribute::attributes.attributes'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.attribute.attribute.create');
                    $item->route('admin.attribute.attribute.index');
                    $item->authorize(
                        $this->auth->hasAccess('attribute.attributes.index')
                    );
                });

                $item->item(trans('product::attrsets.title.attrsets'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.product.attrset.create');
                    $item->route('admin.product.attrset.index');
                    $item->authorize(
                        $this->auth->hasAccess('product.attrsets.index')
                    );
                });
                $item->item(trans('product::products.title.products'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.product.product.create');
                    $item->route('admin.product.product.index');
                    $item->authorize(
                        $this->auth->hasAccess('product.products.index')
                    );
                });
                $item->item(trans('product::categories.title.categories'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.product.category.create');
                    $item->route('admin.product.category.index');
                    $item->authorize(
                        $this->auth->hasAccess('product.categories.index')
                    );
                });

// append

            });
        });

        return $menu;
    }
}
