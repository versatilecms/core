<?php

namespace Versatile\Core\Bread\Operations;

use Illuminate\Http\Request;
use Versatile\Core\Facades\Versatile;

trait Order
{
    /**
     * Order BREAD items.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function order(Request $request)
    {
        // Get the slug, ex. 'posts', 'pages', etc.
        $dataTypeSlug = $this->bread->slug;
        $dataType = $this->bread;
        $model = $this->bread->getModel();

        // Check permission
        $this->authorize('edit', $model);

        if (!isset($dataType->order_column) || !isset($dataType->order_display_column)) {
            return redirect()
                ->route("versatile.{$dataType->slug}.index")
                ->with([
                    'message' => __('versatile::bread.ordering_not_set'),
                    'alert-type' => 'error',
                ]);
        }

        $results = $model->orderBy($dataType->order_column)->get();

        $display_column = $dataType->order_display_column;

        $view = 'versatile::bread.order';
        if (view()->exists("versatile::{$dataTypeSlug}.order")) {
            $view = "versatile::{$dataTypeSlug}.order";
        }

        return Versatile::view($view, compact(
            'dataType',
            'display_column',
            'results'
        ));
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateOrder(Request $request)
    {
        // Get the slug, ex. 'posts', 'pages', etc.
        $dataTypeSlug = $this->bread->slug;
        $dataType = $this->bread;
        $model = $this->bread->getModel();

        // Check permission
        $this->authorize('edit', $model);

        $order = json_decode($request->input('order'));
        $column = $dataType->order_column;
        foreach ($order as $key => $item) {
            $i = $model->findOrFail($item->id);
            $i->$column = ($key + 1);
            $i->save();
        }
    }
}
