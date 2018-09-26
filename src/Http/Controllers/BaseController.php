<?php

namespace Versatile\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Versatile\Core\Components\Filters\QueryBuilder;
use Versatile\Core\Events\BreadDataAdded;
use Versatile\Core\Events\BreadDataDeleted;
use Versatile\Core\Events\BreadDataUpdated;
use Versatile\Core\Events\BreadImagesDeleted;
use Versatile\Core\Facades\Versatile;
use Versatile\Core\Http\Controllers\Traits\BreadRelationshipParserTrait;
use Versatile\Core\Models\DataType;

class BaseController extends Controller
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|string|null
     */
    protected $model = null;

    /**
     * @var DataType|null
     */
    protected $dataType = null;

    /**
     * Data Type name
     *
     * @var null|string
     */
    protected $dataTypeSlug = null;

    use BreadRelationshipParserTrait;

    /**
     * Browse our Data Type (B)READ
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        // Get the slug, ex. 'posts', 'pages', etc.
        $dataTypeSlug = $this->getDataTypeSlug($request);
        $dataType = $this->getDataType($dataTypeSlug);
        $model = $this->getModel($dataType);

        $filters = $this->getFilters();
        $actions = $this->getActions();

        // Check permission
        $this->authorize('browse', $model);

        $getter = 'paginate';

        $orderBy = $request->get('order_by');
        $sortOrder = $request->get('sort_order', null);

        // @TODO Refactor: Method apparently of no relevance
        // $relationships = $this->getRelationships($dataType);
        // $model = $model::select('*')->with($relationships);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'browse');

        $dataTypeContent = QueryBuilder::for($model->query(), $request)
            ->apply($filters);

        // If there is a search
        if ($request->has('q')) {
            $dataTypeContent = $dataTypeContent->search($request->q);
        }

        $dataTypeContent = $dataTypeContent->$getter();

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);

        // Check if BREAD is Translatable
        if (($isModelTranslatable = is_bread_translatable($model))) {
            $dataTypeContent->load('translations');
        }

        $view = 'versatile::bread.browse';

        if (view()->exists("versatile::{$dataTypeSlug}.browse")) {
            $view = "versatile::{$dataTypeSlug}.browse";
        }

        return Versatile::view($view, [
            'filters' => $filters,
            'actions' => $actions,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'isModelTranslatable' => $isModelTranslatable,
            'orderBy' => $orderBy,
            'sortOrder' => $sortOrder
        ]);
    }

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
        $dataTypeSlug = $this->getDataTypeSlug($request);
        $dataType = $this->getDataType($dataTypeSlug);
        $model = $this->getModel($dataType);

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

        if (view()->exists("versatile::{$dataTypeSlug}.read")) {
            $view = "versatile::{$dataTypeSlug}.read";
        }

        return Versatile::view($view, [
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'isModelTranslatable' => $isModelTranslatable
        ]);
    }

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
        $dataTypeSlug = $this->getDataTypeSlug($request);
        $dataType = $this->getDataType($dataTypeSlug);
        $model = $this->getModel($dataType);

        $relationships = $this->getRelationships($dataType);
        $dataTypeContent = $model->with($relationships)->findOrFail($id);

        foreach ($dataType->editRows as $key => $row) {
            $details = json_decode($row->details);
            $dataType->editRows[$key]['col_width'] = isset($details->width) ? $details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'versatile::bread.edit-add';

        if (view()->exists("versatile::{$dataTypeSlug}.edit-add")) {
            $view = "versatile::{$dataTypeSlug}.edit-add";
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
        $dataTypeSlug = $this->getDataTypeSlug($request);
        $dataType = $this->getDataType($dataTypeSlug);
        $model = $this->getModel($dataType);

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
            $this->insertUpdateData($request, $dataTypeSlug, $dataType->editRows, $data);

            event(new BreadDataUpdated($dataType, $data));

            return redirect()
                ->route("versatile.{$dataType->slug}.index")
                ->with([
                    'message' => __('versatile::generic.successfully_updated') . " {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }
    }

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
        $dataTypeSlug = $this->getDataTypeSlug($request);
        $dataType = $this->getDataType($dataTypeSlug);
        $model = $this->getModel($dataType);

        // Check permission
        $this->authorize('add', $model);

        foreach ($dataType->addRows as $key => $row) {
            $details = json_decode($row->details);
            $dataType->addRows[$key]['col_width'] = isset($details->width) ? $details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        $view = 'versatile::bread.edit-add';

        if (view()->exists("versatile::{$dataTypeSlug}.edit-add")) {
            $view = "versatile::{$dataTypeSlug}.edit-add";
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
        $dataTypeSlug = $this->getDataTypeSlug($request);
        $dataType = $this->getDataType($dataTypeSlug);
        $model = $this->getModel($dataType);

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

        $data = $this->insertUpdateData($request, $dataTypeSlug, $dataType->addRows, $model);

        event(new BreadDataAdded($dataType, $data));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $data]);
        }

        return redirect()
            ->route("versatile.{$dataType->slug}.index")
            ->with([
                'message' => __('versatile::generic.successfully_added_new') . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }


    /**
     * Delete an item BREA(D)
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, $id)
    {
        // Get the slug, ex. 'posts', 'pages', etc.
        $dataTypeSlug = $this->getDataTypeSlug($request);
        $dataType = $this->getDataType($dataTypeSlug);
        $model = $this->getModel($dataType);

        // Check permission
        $this->authorize('delete', $model);

        // Init array of IDs
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }
        foreach ($ids as $id) {
            $data = call_user_func([$model, 'findOrFail'], $id);
            $this->cleanup($dataType, $data);
        }

        $displayName = count($ids) > 1 ? $dataType->display_name_plural : $dataType->display_name_singular;

        $res = $data->destroy($ids);

        $data = [
            'message' => __('versatile::generic.error_deleting') . " ({$displayName})",
            'alert-type' => 'error',
        ];

        if ($res) {
            $data = [
                'message' => __('versatile::generic.successfully_deleted') . " ({$displayName})",
                'alert-type' => 'success',
            ];

            event(new BreadDataDeleted($dataType, $data));
        }

        return redirect()->route("versatile.{$dataType->slug}.index")->with($data);
    }

    /**
     * Remove translations, images and files related to a BREAD item.
     *
     * @param DataType $dataType
     * @param \Illuminate\Database\Eloquent\Model $data
     *
     * @return void
     */
    protected function cleanup($dataType, $data)
    {
        // Delete Translations, if present
        if (is_bread_translatable($data)) {
            $data->deleteAttributeTranslations($data->getTranslatableAttributes());
        }

        // Delete Images
        $this->deleteBreadImages($data, $dataType->deleteRows->where('type', 'image'));

        // Delete Files
        foreach ($dataType->deleteRows->where('type', 'file') as $row) {
            if (isset($data->{$row->field})) {
                foreach (json_decode($data->{$row->field}) as $file) {
                    $this->deleteFileIfExists($file->download_link);
                }
            }
        }
    }

    /**
     * Delete all images related to a BREAD item.
     *
     * @param \Illuminate\Database\Eloquent\Model $data
     * @param \Illuminate\Database\Eloquent\Model $rows
     *
     * @return void
     */
    public function deleteBreadImages($data, $rows)
    {
        foreach ($rows as $row) {
            if ($data->{$row->field} != config('versatile.user.default_avatar')) {
                $this->deleteFileIfExists($data->{$row->field});
            }

            $options = json_decode($row->details);

            if (isset($options->thumbnails)) {
                foreach ($options->thumbnails as $thumbnail) {
                    $ext = explode('.', $data->{$row->field});
                    $extension = '.' . $ext[count($ext) - 1];

                    $path = str_replace($extension, '', $data->{$row->field});

                    $thumb_name = $thumbnail->name;

                    $this->deleteFileIfExists($path . '-' . $thumb_name . $extension);
                }
            }
        }

        if ($rows->count() > 0) {
            event(new BreadImagesDeleted($data, $rows));
        }
    }


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
        $dataTypeSlug = $this->getDataTypeSlug($request);
        $dataType = $this->getDataType($dataTypeSlug);
        $model = $this->getModel($dataType);

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
        $dataTypeSlug = $this->getDataTypeSlug($request);
        $dataType = $this->getDataType($dataTypeSlug);
        $model = $this->getModel($dataType);

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
