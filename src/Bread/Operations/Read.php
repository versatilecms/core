<?php

namespace Versatile\Core\Bread\Operations;

use Illuminate\Http\Request;
use Versatile\Core\Facades\Versatile;

trait Read
{
    /**
     * Read an item of our Data Type B(R)EAD
     *
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, $id)
    {
        // Get the slug, ex. 'posts', 'pages', etc.
        $dataType = $this->bread;
        $slug = $this->bread->slug;
        $model = $this->bread->getModel();

        $relationships = $this->getRelationships($dataType);
        $dataTypeContent = call_user_func([$model->with($relationships), 'findOrFail'], $id);

        // Replace relationships' keys for labels and create READ links if a slug__ is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'versatile::bread.read';

        if (view()->exists("versatile::{$slug}.read")) {
            $view = "versatile::{$slug}.read";
        }

        return Versatile::view($view, [
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'isModelTranslatable' => $isModelTranslatable
        ]);
    }
}
