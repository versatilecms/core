<?php

namespace Versatile\Core\Http\Controllers;

use Illuminate\Http\Request;
use Versatile\Core\Facades\Versatile;

class RoleController extends BaseController
{
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
        $slug = $this->getDataTypeSlug($request);

        $dataType = Versatile::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('edit', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
            ->route("versatile.{$dataType->slug}.index")
            ->with([
                'message'    => __('versatile::generic.successfully_updated')." {$dataType->display_name_singular}",
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
        $slug = $this->getDataTypeSlug($request);

        $dataType = Versatile::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        //Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $data = new $dataType->model_name();
            $this->insertUpdateData($request, $slug, $dataType->addRows, $data);

            $data->permissions()->sync($request->input('permissions', []));

            return redirect()
            ->route("versatile.{$dataType->slug}.index")
            ->with([
                'message'    => __('versatile::generic.successfully_added_new')." {$dataType->display_name_singular}",
                'alert-type' => 'success',
                ]);
        }
    }
}
