<?php

namespace Versatile\Core\Bread\Operations;

use Illuminate\Http\Request;
use Versatile\Core\Events\BreadDataAdded;
use Versatile\Core\Facades\Versatile;

trait Add
{
    /**
     * Add a new item of our Data Type BRE(A)D
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request)
    {
        // Get the slug, ex. 'posts', 'pages', etc.
        $dataType = $this->bread;
        $slug = $this->bread->slug;
        $model = $this->bread->getModel();

        // Check permission
        $this->authorize('add', $model);

        foreach ($dataType->addRows as $key => &$row) {
            $details = $row->details;
            $row->col_width = isset($details->width) ? $details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        $view = 'versatile::bread.edit-add';

        if (view()->exists("versatile::{$slug}.edit-add")) {
            $view = "versatile::{$slug}.edit-add";
        }

        return Versatile::view($view, [
            'dataType' => $dataType,
            'dataTypeContent' => $model,
            'isModelTranslatable' => $isModelTranslatable
        ]);
    }


    /**
     * POST BRE(A)D - Store data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        // Get the slug, ex. 'posts', 'pages', etc.
        $dataType = $this->bread;
        $slug = $this->bread->slug;
        $model = $this->bread->getModel();

        // Check permission
        $this->authorize('add', $model);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if ($request->has('_validate')) {
            return;
        }

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, $model);

        event(new BreadDataAdded($dataType, $data));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $data]);
        }

        return redirect()
            ->route("versatile.{$slug}.index")
            ->with([
                'message' => __('versatile::generic.successfully_added_new') . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }
}
