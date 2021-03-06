<?php

namespace Versatile\Core\Http\Controllers\Tools;

use Illuminate\Http\Request;

use Versatile\Core\Http\Controllers\BaseController;
use Versatile\Core\Models\Menu;
use Versatile\Core\Models\MenuItem;
use Versatile\Core\Facades\Versatile;

class MenusController extends BaseController
{
    /**
     * Informs if DataType will be loaded from the database or setup
     *
     * @var bool
     */
    protected $dataTypeFromDatabase = false;

    public function setup()
    {
        $this->bread->setName('menus');
        $this->bread->setSlug('menus');

        $this->bread->setDisplayNameSingular(__('versatile::seeders.data_types.menu.singular'));
        $this->bread->setDisplayNamePlural(__('versatile::seeders.data_types.menu.plural'));

        $this->bread->setIcon('versatile-list');
        $this->bread->setModel(Menu::class);

        $this->bread->addDataRows([
            [
                'field' => 'id',
                'type' => 'number',
                'display_name' => __('versatile::seeders.data_rows.id'),
                'required' => true,
                'browse' => true,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '',
                'order' => true,
                'sortable' => true,
            ],

            [
                'field' => 'name',
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.name'),
                'required' => true,
                'browse' => true,
                'read' => true,
                'edit' => true,
                'add' => true,
                'delete' => true,
                'sortable' => true,
            ],

            [
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => __('versatile::seeders.data_rows.created_at'),
                'required' => false,
                'browse' => true,
                'read' => true,
                'edit' => false,
                'add' => false,
                'delete' => false
            ],

            [
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => __('versatile::seeders.data_rows.updated_at'),
                'browse' => false,
                'read' => true,
                'edit' => false,
                'add' => false,
                'delete' => false
            ]
        ]);
    }

    public function builder($id)
    {
        $menu = Menu::findOrFail($id);

        $this->authorize('edit', $menu);

        $isModelTranslatable = is_bread_translatable(app(MenuItem::class));

        return Versatile::view('versatile::menus.builder', compact('menu', 'isModelTranslatable'));
    }

    public function delete_menu($menu, $id)
    {
        $item = MenuItem::findOrFail($id);

        $this->authorize('delete', $item);

        $item->deleteAttributeTranslation('title');

        $item->destroy($id);

        return redirect()
            ->route('versatile.menus.builder', [$menu])
            ->with([
                'message'    => __('versatile::menu_builder.successfully_deleted'),
                'alert-type' => 'success',
            ]);
    }

    public function add_item(Request $request)
    {
        $menu = app(Menu::class);

        $this->authorize('add', $menu);

        $data = $this->prepareParameters(
            $request->all()
        );

        unset($data['id']);
        $data['order'] = app(MenuItem::class)->highestOrderMenuItem();

        // Check if is translatable
        $_isTranslatable = is_bread_translatable(app(MenuItem::class));
        if ($_isTranslatable) {
            // Prepare data before saving the menu
            $trans = $this->prepareMenuTranslations($data);
        }

        $menuItem = MenuItem::create($data);

        // Save menu translations
        if ($_isTranslatable) {
            $menuItem->setAttributeTranslations('title', $trans, true);
        }

        return redirect()
            ->route('versatile.menus.builder', [$data['menu_id']])
            ->with([
                'message'    => __('versatile::menu_builder.successfully_created'),
                'alert-type' => 'success',
            ]);
    }

    public function update_item(Request $request)
    {
        $id = $request->input('id');
        $data = $this->prepareParameters(
            $request->except(['id'])
        );

        $menuItem = MenuItem::findOrFail($id);

        $this->authorize('edit', $menuItem->menu);

        if (is_bread_translatable($menuItem)) {
            $trans = $this->prepareMenuTranslations($data);

            // Save menu translations
            $menuItem->setAttributeTranslations('title', $trans, true);
        }

        $menuItem->update($data);

        return redirect()
            ->route('versatile.menus.builder', [$menuItem->menu_id])
            ->with([
                'message'    => __('versatile::menu_builder.successfully_updated'),
                'alert-type' => 'success',
            ]);
    }

    public function order_item(Request $request)
    {
        $menuItemOrder = json_decode($request->input('order'));

        $this->orderMenu($menuItemOrder, null);
    }

    private function orderMenu(array $menuItems, $parentId)
    {
        foreach ($menuItems as $index => $menuItem) {
            $item = MenuItem::findOrFail($menuItem->id);
            $item->order = $index + 1;
            $item->parent_id = $parentId;
            $item->save();

            if (isset($menuItem->children)) {
                $this->orderMenu($menuItem->children, $item->id);
            }
        }
    }

    protected function prepareParameters($parameters)
    {
        switch (array_get($parameters, 'type')) {
            case 'route':
                $parameters['url'] = null;
                break;
            default:
                $parameters['route'] = null;
                $parameters['parameters'] = '';
                break;
        }

        if (isset($parameters['type'])) {
            unset($parameters['type']);
        }

        return $parameters;
    }

    /**
     * * Prepare menu translations.
     *
     * @param $data array $data menu data
     * @return mixed JSON translated item
     */
    protected function prepareMenuTranslations(&$data)
    {
        $trans = json_decode($data['title_i18n'], true);

        // Set field value with the default locale
        $data['title'] = $trans[config('versatile.multilingual.default', 'en')];

        unset($data['title_i18n']);     // Remove hidden input holding translations
        unset($data['i18n_selector']);  // Remove language selector input radio

        return $trans;
    }
}
