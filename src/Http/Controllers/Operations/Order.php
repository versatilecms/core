<?php

namespace Versatile\Core\Http\Controllers\Operations;

use Illuminate\Http\Request;
use Versatile\Core\Facades\Versatile;

trait Order
{
    /**
     * Order BREAD items.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function order()
    {
        $model = $this->bread->getModel();

        // Check permission
        $this->authorize('edit', $model);

        if (!isset($this->bread->order_column) || !isset($this->bread->order_display_column)) {
            return redirect()
                ->route("versatile.{$this->bread->slug}.index")
                ->with([
                    'message' => __('versatile::bread.ordering_not_set'),
                    'alert-type' => 'error',
                ]);
        }

        $results = $model->orderBy($this->bread->order_column)->get();

        $display_column = $this->bread->order_display_column;

        $view = $this->bread->getOrderView();
        if (view()->exists("versatile::{$this->bread->slug}.order")) {
            $view = "versatile::{$this->bread->slug}.order";
        }

        return Versatile::view($view, [
            'dataType' => $this->bread,
            'display_column' => $display_column,
            'results' => $results
        ]);
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateOrder(Request $request)
    {
        $model = $this->bread->getModel();

        // Check permission
        $this->authorize('edit', $model);

        $order = json_decode($request->input('order'));
        $column = $this->bread->order_column;
        foreach ($order as $key => $item) {
            $i = $model->findOrFail($item->id);
            $i->$column = ($key + 1);
            $i->save();
        }
    }
}
