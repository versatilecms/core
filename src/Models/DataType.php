<?php

namespace Versatile\Core\Models;

use Versatile\Core\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use Versatile\Core\Database\Schema\SchemaManager;
use Versatile\Core\Facades\Versatile;
use Versatile\Core\Traits\Translatable;

class DataType extends BaseModel
{
    use Translatable;

    protected $translatable = [
        'display_name_singular',
        'display_name_plural'
    ];

    protected $table = 'data_types';

    protected $fillable = [
        'name',
        'slug',
        'display_name_singular',
        'display_name_plural',
        'icon',
        'model_name',
        'policy_name',
        'controller',
        'description',
        'generate_permissions',
        'order_column',
        'order_display_column',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Checks if Model is searchable
     *
     * @return bool
     */
    public function getIsSearchableAttribute()
    {
        return property_exists($this->model_name, 'searchable');
    }

    public function rows()
    {
        return $this->hasMany(Versatile::modelClass('DataRow'))->orderBy('order');
    }

    public function browseRows()
    {
        return $this->rows()->where('browse', 1);
    }

    public function readRows()
    {
        return $this->rows()->where('read', 1);
    }

    public function editRows()
    {
        return $this->rows()->where('edit', 1);
    }

    public function addRows()
    {
        return $this->rows()->where('add', 1);
    }

    public function deleteRows()
    {
        return $this->rows()->where('delete', 1);
    }

    public function filterRows()
    {
        return $this->rows()->where('filter', 1);
    }

    public function lastRow()
    {
        return $this->hasMany(Versatile::modelClass('DataRow'))
            ->orderBy('order', 'DESC')
            ->first();
    }

    public function setGeneratePermissionsAttribute($value)
    {
        $this->attributes['generate_permissions'] = $value ? 1 : 0;
    }

    public function updateDataType($requestData, $throw = false)
    {
        try {
            DB::beginTransaction();

            // Prepare data
            if (!isset($requestData['generate_permissions'])) {
                $requestData['generate_permissions'] = 0;
            }

            if ($this->fill($requestData)->save()) {
                $fields = $this->fields(array_get($requestData, 'name'));

                $requestData = $this->getRelationships($requestData, $fields);

                foreach ($fields as $field) {
                    $dataRow = $this->rows()->firstOrNew(['field' => $field]);

                    foreach (['browse', 'read', 'edit', 'add', 'delete', 'filter'] as $check) {
                        $dataRow->{$check} = isset($requestData["field_{$check}_{$field}"]);
                    }

                    $dataRow->required = $requestData['field_required_'.$field];
                    $dataRow->field = $requestData['field_'.$field];
                    $dataRow->type = $requestData['field_input_type_'.$field];
                    $dataRow->details = $requestData['field_details_'.$field];
                    $dataRow->display_name = $requestData['field_display_name_'.$field];
                    $dataRow->order = intval($requestData['field_order_'.$field]);

                    if (!$dataRow->save()) {
                        throw new \Exception(__('versatile::database.field_safe_failed', ['field' => $field]));
                    }
                }

                // Clean data_rows that don't have an associated field
                // TODO: need a way to identify deleted and renamed fields.
                //   maybe warn the user and let him decide to either rename or delete?
                $this->rows()->whereNotIn('field', $fields)->delete();

                // It seems everything was fine. Let's check if we need to generate permissions
                if ($this->generate_permissions) {
                    Versatile::model('Permission')->generateFor($this->name);
                }

                DB::commit();

                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if ($throw) {
                throw $e;
            }
        }

        return false;
    }

    public function fields($name = null)
    {
        if (is_null($name)) {
            $name = $this->name;
        }

        $fields = SchemaManager::listTableColumnNames($name);

        if ($extraFields = $this->extraFields()) {
            foreach ($extraFields as $field) {
                $fields[] = $field['Field'];
            }
        }

        return $fields;
    }

    public function getRelationships($requestData, &$fields)
    {
        if (isset($requestData['relationships'])) {
            $relationships = $requestData['relationships'];
            if (count($relationships) > 0) {
                foreach ($relationships as $index => $relationship) {
                    // Push the relationship on the allowed fields
                    array_push($fields, $relationship);

                    $relationship_column = $requestData['relationship_column_belongs_to_'.$relationship];
                    if ($requestData['relationship_type_'.$relationship] == 'hasOne' || $requestData['relationship_type_'.$relationship] == 'hasMany') {
                        $relationship_column = $requestData['relationship_column_'.$relationship];
                    }

                    // Build the relationship details
                    $relationshipDetails = [
                        'model'       => $requestData['relationship_model_'.$relationship],
                        'table'       => $requestData['relationship_table_'.$relationship],
                        'type'        => $requestData['relationship_type_'.$relationship],
                        'column'      => $relationship_column,
                        'key'         => $requestData['relationship_key_'.$relationship],
                        'label'       => $requestData['relationship_label_'.$relationship],
                        'pivot_table' => $requestData['relationship_pivot_table_'.$relationship],
                        'pivot'       => ($requestData['relationship_type_'.$relationship] == 'belongsToMany') ? '1' : '0',
                        'taggable'    => isset($requestData['relationship_taggable_'.$relationship]) ? $requestData['relationship_taggable_'.$relationship] : '0',
                    ];

                    $requestData['field_details_'.$relationship] = json_encode($relationshipDetails);
                }
            }
        }

        return $requestData;
    }

    public function fieldOptions()
    {
        $table = $this->name;

        // Get ordered BREAD fields
        $orderedFields = $this->rows()->pluck('field')->toArray();

        $_fieldOptions = SchemaManager::describeTable($table)->toArray();

        $fieldOptions = [];
        $f_size = count($orderedFields);
        for ($i = 0; $i < $f_size; $i++) {
            $fieldOptions[$orderedFields[$i]] = $_fieldOptions[$orderedFields[$i]];
        }
        $fieldOptions = collect($fieldOptions);

        if ($extraFields = $this->extraFields()) {
            foreach ($extraFields as $field) {
                $fieldOptions[] = (object) $field;
            }
        }

        return $fieldOptions;
    }

    public function extraFields()
    {
        if (empty(trim($this->model_name))) {
            return [];
        }

        $model = app($this->model_name);
        if (method_exists($model, 'adminFields')) {
            return $model->adminFields();
        }
    }

    public function getOrderColumnAttribute()
    {
        return $this->details['order_column'];
    }

    public function setOrderColumnAttribute($value)
    {
        $this->attributes['details'] = collect($this->details)->merge(['order_column' => $value]);
    }

    public function getOrderDisplayColumnAttribute()
    {
        return $this->details['order_display_column'];
    }

    public function setOrderDisplayColumnAttribute($value)
    {
        $this->attributes['details'] = collect($this->details)->merge(['order_display_column' => $value]);
    }
}