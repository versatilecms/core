<?php

namespace Versatile\Core\Http\Controllers\Operations;

use Versatile\Core\Facades\Versatile;

trait Read
{
    /**
     * Read an item of our Data Type B(R)EAD
     *
     * @param $id
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $model = $this->bread->getModel();

        $relationships = $this->getRelationships($this->bread);
        $dataTypeContent = call_user_func([$model->with($relationships), 'findOrFail'], $id);

        // Replace relationships' keys for labels and create READ links if a slug__ is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $this->bread, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($this->bread, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = $this->bread->getReadView();
        if (view()->exists("versatile::{$this->bread->slug}.read")) {
            $view = "versatile::{$this->bread->slug}.read";
        }

        return Versatile::view($view, [
            'dataType' => $this->bread,
            'dataTypeContent' => $dataTypeContent,
            'isModelTranslatable' => $isModelTranslatable
        ]);
    }
}
