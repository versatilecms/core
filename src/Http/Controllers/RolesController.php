<?php

namespace Versatile\Core\Http\Controllers;

use Illuminate\Http\Request;
use Versatile\Core\Models\Role;

class RolesController extends BaseController
{
    /**
     * Informs if DataType will be loaded from the database or setup
     *
     * @var bool
     */
    protected $dataTypeFromDatabase = false;

    public function setup()
    {
        $this->bread->setName('roles');
        $this->bread->setSlug('roles');

        $this->bread->setDisplayNameSingular(__('versatile::seeders.data_types.role.singular'));
        $this->bread->setDisplayNamePlural(__('versatile::seeders.data_types.role.plural'));

        $this->bread->setIcon('versatile-lock');
        $this->bread->setModel(Role::class);

        $this->bread->addDataRows([
            [
                'field' => 'id',
                'type' => 'number',
                'display_name' => __('versatile::seeders.data_rows.id'),
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => []
            ],

            [
                'field' => 'name',
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.name'),
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => []
            ],

            [
                'field' => 'display_name',
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.display_name'),
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => []
            ],

            [
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => __('versatile::seeders.data_rows.created_at'),
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => []
            ],

            [
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => __('versatile::seeders.data_rows.updated_at'),
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => []
            ]
        ]);
    }

    /**
     * POST BR(E)AD
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        // Check permission
        $this->authorize('edit', $this->bread->getModel());

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $this->bread->editRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $data = call_user_func([$this->bread->model_name, 'findOrFail'], $id);
            $this->insertUpdateData($request, $this->bread->slug, $this->bread->editRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
            ->route("versatile.{$this->bread->slug}.index")
            ->with([
                'message'    => __('versatile::generic.successfully_updated')." {$this->bread->display_name_singular}",
                'alert-type' => 'success',
                ]);
        }
    }

    /**
     * POST BRE(A)D
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        // Check permission
        $this->authorize('add', $this->bread->getModel());

        //Validate fields with ajax
        $val = $this->validateBread($request->all(), $this->bread->addRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $data = new $this->bread->model_name();
            $this->insertUpdateData($request, $this->bread->getModel(), $this->bread->addRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
            ->route("versatile.{$this->bread->slug}.index")
            ->with([
                'message'    => __('versatile::generic.successfully_added_new')." {$this->bread->display_name_singular}",
                'alert-type' => 'success',
                ]);
        }
    }
}
