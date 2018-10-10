<?php

namespace Versatile\Core\Http\Controllers\Operations;

use Illuminate\Http\Request;
use Versatile\Core\Components\Filters\QueryBuilder;
use Versatile\Core\Facades\Versatile;

trait Browse
{
    /**
     * Browse our Data Type (B)READ
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $model = $this->bread->getModel();
        $filters = $this->bread->getFilters();
        $actions = $this->bread->getActions();

        // Check permission
        $this->authorize('browse', $model);

        $getter = $this->bread->get_method;

        $orderBy = $request->get('order_by');
        $sortOrder = $request->get('sort_order', null);

        // @TODO Refactor: Method apparently of no relevance
        // $relationships = $this->getRelationships($this->bread);
        // $model = $model::select('*')->with($relationships);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($this->bread, 'browse');

        $dataTypeContent = QueryBuilder::for($model->query(), $request)
            ->apply($filters);

        if (!$request->sorts()->count()) {
            foreach ($this->bread->order_by as $column => $direction) {
                $dataTypeContent = $dataTypeContent->orderBy($column, $direction);
            }
        }

        // If there is a search
        if ($request->has('q')) {
            $dataTypeContent = $dataTypeContent->search($request->q);
        }

        $dataTypeContent = $dataTypeContent->$getter();

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $this->bread);

        // Check if BREAD is Translatable
        if (($isModelTranslatable = is_bread_translatable($model))) {
            $dataTypeContent->load('translations');
        }

        $view = $this->bread->getBrowseView();
        if (view()->exists("versatile::{$this->bread->slug}.browse")) {
            $view = "versatile::{$this->bread->slug}.browse";
        }

        return Versatile::view($view, [
            'filters' => $filters,
            'actions' => $actions,
            'dataType' => $this->bread,
            'dataTypeContent' => $this->bread->process($dataTypeContent),
            'isModelTranslatable' => $isModelTranslatable,
            'orderBy' => $orderBy,
            'sortOrder' => $sortOrder
        ]);
    }
}
