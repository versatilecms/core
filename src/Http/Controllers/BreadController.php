<?php

namespace Versatile\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Versatile\Core\Database\Schema\SchemaManager;
use Versatile\Core\Events\BreadAdded;
use Versatile\Core\Events\BreadDeleted;
use Versatile\Core\Events\BreadUpdated;
use Versatile\Core\Facades\Versatile;
use Versatile\Core\Models\DataRow;

class BreadController extends Controller
{
    public function index()
    {
        Versatile::canOrFail('browse_bread');

        $dataTypes = Versatile::model('DataType')
            ->select('id', 'name', 'slug')
            ->get()->keyBy('name')
            ->toArray();

        $tables = array_map(function ($table) use ($dataTypes) {
            $table = [
                'name' => $table,
                'slug' => isset($dataTypes[$table]['slug']) ? $dataTypes[$table]['slug'] : null,
                'dataTypeId' => isset($dataTypes[$table]['id']) ? $dataTypes[$table]['id'] : null,
            ];

            return (object)$table;
        }, SchemaManager::listTableNames());

        return Versatile::view('versatile::tools.bread.index')->with(compact('dataTypes', 'tables'));
    }

    /**
     * Create BREAD.
     *
     * @param Request $request
     * @param string $table Table name.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, $table)
    {
        Versatile::canOrFail('browse_bread');

        $data = $this->prepopulateBreadInfo($table);
        $data['fieldOptions'] = SchemaManager::describeTable($table);

        return Versatile::view('versatile::tools.bread.edit-add', $data);
    }

    private function prepopulateBreadInfo($table)
    {
        $displayName = Str::singular(implode(' ', explode('_', Str::title($table))));
        $modelNamespace = config('versatile.models.namespace', app()->getNamespace());
        if (empty($modelNamespace)) {
            $modelNamespace = app()->getNamespace();
        }

        return [
            'isModelTranslatable' => true,
            'table' => $table,
            'slug' => Str::slug($table),
            'display_name' => $displayName,
            'display_name_plural' => Str::plural($displayName),
            'model_name' => $modelNamespace . Str::studly(Str::singular($table)),
            'generate_permissions' => true
        ];
    }

    /**
     * Store BREAD.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $dataType = Versatile::model('DataType');
            $res = $dataType->updateDataType($request->all(), true);
            $data = $res
                ? $this->alertSuccess(__('versatile::bread.success_created_bread'))
                : $this->alertError(__('versatile::bread.error_creating_bread'));
            if ($res) {
                event(new BreadAdded($dataType, $data));
            }

            return redirect()->route('versatile.bread.index')->with($data);
        } catch (Exception $e) {
            return redirect()->route('versatile.bread.index')->with($this->alertException($e, 'Saving Failed'));
        }
    }

    /**
     * Edit BREAD.
     *
     * @param string $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($table)
    {
        Versatile::canOrFail('browse_bread');

        $dataType = Versatile::model('DataType')->whereName($table)->first();

        $fieldOptions = SchemaManager::describeTable($dataType->name);

        $isModelTranslatable = is_bread_translatable($dataType);
        $tables = SchemaManager::listTableNames();
        $dataTypeRelationships = Versatile::model('DataRow')->where('data_type_id', '=', $dataType->id)->where('type', '=', 'relationship')->get();

        return Versatile::view('versatile::tools.bread.edit-add', compact('dataType', 'fieldOptions', 'isModelTranslatable', 'tables', 'dataTypeRelationships'));
    }

    /**
     * Update BREAD.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        Versatile::canOrFail('browse_bread');

        /* @var \Versatile\Core\Models\DataType $dataType */
        try {
            $dataType = Versatile::model('DataType')->find($id);

            // Prepare Translations and Transform data
            $translations = is_bread_translatable($dataType)
                ? $dataType->prepareTranslations($request)
                : [];

            $res = $dataType->updateDataType($request->all(), true);
            $data = $res
                ? $this->alertSuccess(__('versatile::bread.success_update_bread', ['datatype' => $dataType->name]))
                : $this->alertError(__('versatile::bread.error_updating_bread'));
            if ($res) {
                event(new BreadUpdated($dataType, $data));
            }

            // Save translations if applied
            $dataType->saveTranslations($translations);

            return redirect()->route('versatile.bread.index')->with($data);
        } catch (Exception $e) {
            return back()->with($this->alertException($e, __('versatile::generic.update_failed')));
        }
    }

    /**
     * Delete BREAD.
     *
     * @param integer $id BREAD data_type id.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Versatile::canOrFail('browse_bread');

        /* @var \Versatile\Core\Models\DataType $dataType */
        $dataType = Versatile::model('DataType')->find($id);

        // Delete Translations, if present
        if (is_bread_translatable($dataType)) {
            $dataType->deleteAttributeTranslations($dataType->getTranslatableAttributes());
        }

        $res = Versatile::model('DataType')->destroy($id);
        $data = $res
            ? $this->alertSuccess(__('versatile::bread.success_remove_bread', ['datatype' => $dataType->name]))
            : $this->alertError(__('versatile::bread.error_updating_bread'));
        if ($res) {
            event(new BreadDeleted($dataType, $data));
        }

        if (!is_null($dataType)) {
            Versatile::model('Permission')->removeFrom($dataType->name);
        }

        return redirect()->route('versatile.bread.index')->with($data);
    }

    /**
     * Add Relationship.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addRelationship(Request $request)
    {
        $relationshipField = $this->getRelationshipField($request);

        if (!class_exists($request->relationship_model)) {
            return back()->with([
                'message' => 'Model Class ' . $request->relationship_model . ' does not exist. Please create Model before creating relationship.',
                'alert-type' => 'error',
            ]);
        }

        try {
            DB::beginTransaction();

            $relationship_column = $request->relationship_column_belongs_to;
            if ($request->relationship_type == 'hasOne' || $request->relationship_type == 'hasMany') {
                $relationship_column = $request->relationship_column;
            }

            // Build the relationship details
            $relationshipDetails = json_encode([
                'model' => $request->relationship_model,
                'table' => $request->relationship_table,
                'type' => $request->relationship_type,
                'column' => $relationship_column,
                'key' => $request->relationship_key,
                'label' => $request->relationship_label,
                'pivot_table' => $request->relationship_pivot,
                'pivot' => ($request->relationship_type == 'belongsToMany') ? '1' : '0',
                'taggable' => $request->relationship_taggable,
            ]);

            $newRow = new DataRow();

            $newRow->data_type_id = $request->data_type_id;
            $newRow->field = $relationshipField;
            $newRow->type = 'relationship';
            $newRow->display_name = $request->relationship_table;
            $newRow->required = 0;

            foreach (['browse', 'read', 'edit', 'add', 'delete'] as $check) {
                $newRow->{$check} = 1;
            }

            $newRow->details = $relationshipDetails;
            $newRow->order = intval(Versatile::model('DataType')->find($request->data_type_id)->lastRow()->order) + 1;

            if (!$newRow->save()) {
                return back()->with([
                    'message' => 'Error saving new relationship row for ' . $request->relationship_table,
                    'alert-type' => 'error',
                ]);
            }

            DB::commit();

            return back()->with([
                'message' => 'Successfully created new relationship for ' . $request->relationship_table,
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with([
                'message' => 'Error creating new relationship: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * Get Relationship Field.
     *
     * @param Request $request
     * @return string
     */
    private function getRelationshipField($request)
    {
        // We need to make sure that we aren't creating an already existing field

        $dataType = Versatile::model('DataType')->find($request->data_type_id);

        $field = str_singular($dataType->name) . '_' . $request->relationship_type . '_' . str_singular($request->relationship_table) . '_relationship';

        $relationshipFieldOriginal = $relationshipField = strtolower($field);

        $existingRow = Versatile::model('DataRow')->where('field', '=', $relationshipField)->first();
        $index = 1;

        while (isset($existingRow->id)) {
            $relationshipField = $relationshipFieldOriginal . '_' . $index;
            $existingRow = Versatile::model('DataRow')->where('field', '=', $relationshipField)->first();
            $index += 1;
        }

        return $relationshipField;
    }

    /**
     * Delete Relationship.
     *
     * @param Number $id Record id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteRelationship($id)
    {
        Versatile::model('DataRow')->destroy($id);

        return back()->with([
            'message' => 'Successfully deleted relationship.',
            'alert-type' => 'success',
        ]);
    }
}
