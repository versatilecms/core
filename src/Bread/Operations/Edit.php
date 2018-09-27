<?php

namespace Versatile\Core\Bread\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Versatile\Core\Events\BreadDataUpdated;
use Versatile\Core\Facades\Versatile;

trait Edit
{
    /**
     * Edit an item of our Data Type BR(E)AD
     *
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Request $request, $id)
    {
        // Get the slug, ex. 'posts', 'pages', etc.
        $dataType = $this->bread;
        $slug = $this->bread->slug;
        $model = $this->bread->getModel();

        $relationships = $this->getRelationships($dataType);
        $dataTypeContent = $model->with($relationships)->findOrFail($id);

        foreach ($dataType->editRows as $key => &$row) {
            $details = $row->details;
            $row->col_width = isset($details->width) ? $details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'versatile::bread.edit-add';

        if (view()->exists("versatile::{$slug}.edit-add")) {
            $view = "versatile::{$slug}.edit-add";
        }

        return Versatile::view($view, [
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'isModelTranslatable' => $isModelTranslatable
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
        // Get the slug, ex. 'posts', 'pages', etc.
        $dataType = $this->bread;
        $slug = $this->bread->slug;
        $model = $this->bread->getModel();

        // Compatibility with Model binding.
        if ($id instanceof Model) {
            $id = $id->{$id->getKeyName()};
        }

        $data = call_user_func([$model, 'findOrFail'], $id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            event(new BreadDataUpdated($dataType, $data));

            return redirect()
                ->route("versatile.{$dataType->slug}.index")
                ->with([
                    'message' => __('versatile::generic.successfully_updated') . " {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }
    }
}
