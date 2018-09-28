<?php

namespace Versatile\Core\Http\Controllers\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Versatile\Core\Events\BreadDataUpdated;
use Versatile\Core\Facades\Versatile;

trait Edit
{
    /**
     * Edit an item of our Data Type BR(E)AD
     *
     * @param $id
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $model = $this->bread->getModel();

        $relationships = $this->getRelationships($this->bread);
        $dataTypeContent = $model->with($relationships)->findOrFail($id);

        foreach ($this->bread->editRows as $key => &$row) {
            $details = $row->details;
            $row->col_width = isset($details->width) ? $details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($this->bread, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = $this->bread->getEditView();
        if (view()->exists("versatile::{$this->bread->slug}.edit-add")) {
            $view = "versatile::{$this->bread->slug}.edit-add";
        }

        return Versatile::view($view, [
            'dataType' => $this->bread,
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
        $model = $this->bread->getModel();

        // Compatibility with Model binding.
        if ($id instanceof Model) {
            $id = $id->{$id->getKeyName()};
        }

        $data = call_user_func([$model, 'findOrFail'], $id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $this->bread->editRows, $this->bread->name, $id);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $this->insertUpdateData($request, $this->bread->slug, $this->bread->editRows, $data);

            event(new BreadDataUpdated($this->bread, $data));

            return redirect()
                ->route("versatile.{$this->bread->slug}.index")
                ->with([
                    'message' => __('versatile::generic.successfully_updated') . " {$this->bread->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }
    }
}
