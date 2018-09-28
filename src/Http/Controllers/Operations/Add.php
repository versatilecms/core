<?php

namespace Versatile\Core\Http\Controllers\Operations;

use Illuminate\Http\Request;
use Versatile\Core\Events\BreadDataAdded;
use Versatile\Core\Facades\Versatile;

trait Add
{
    /**
     * Add a new item of our Data Type BRE(A)D
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $model = $this->bread->getModel();

        // Check permission
        $this->authorize('add', $model);

        foreach ($this->bread->addRows as $key => &$row) {
            $details = $row->details;
            $row->col_width = isset($details->width) ? $details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($this->bread, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        $view = $this->bread->getAddView();
        if (view()->exists("versatile::{$this->bread->slug}.edit-add")) {
            $view = "versatile::{$this->bread->slug}.edit-add";
        }

        return Versatile::view($view, [
            'dataType' => $this->bread,
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
        $model = $this->bread->getModel();

        // Check permission
        $this->authorize('add', $model);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $this->bread->addRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if ($request->has('_validate')) {
            return;
        }

        $data = $this->insertUpdateData($request, $this->bread->slug, $this->bread->addRows, $model);

        event(new BreadDataAdded($this->bread, $data));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $data]);
        }

        return redirect()
            ->route("versatile.{$this->bread->slug}.index")
            ->with([
                'message' => __('versatile::generic.successfully_added_new') . " {$this->bread->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }
}
