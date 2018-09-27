<?php

namespace Versatile\Core\Bread\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Versatile\Core\Bread\DataType;

trait BreadRelationship
{
    protected $relation_field = [];

    protected function removeRelationshipField(DataType $dataType, $breadType = 'browse')
    {
        $forgetKeys = [];
        foreach ($dataType->{$breadType.'Rows'} as $key => $row) {
            if ($row->type == 'relationship') {

                $options = $row->details;
                if (is_string($options)) {
                    $options = json_decode($options);
                }

                if ($options->type == 'belongsTo') {
                    $relationshipField = $options->column;
                    $keyInCollection = key($dataType->{$breadType.'Rows'}->where('field', '=', $relationshipField)->toArray());
                    array_push($forgetKeys, $keyInCollection);
                }
            }
        }

        foreach ($forgetKeys as $forgetKey) {
            $dataType->{$breadType.'Rows'}->forget($forgetKey);
        }
    }

    /**
     * Build the relationships array for the model's eager load.
     *
     * @param DataType $dataType
     *
     * @return array
     */
    protected function getRelationships(DataType $dataType)
    {
        $relationships = [];

        $dataType->browseRows->each(function ($item) use (&$relationships) {

            $details = $item->details;
            if (is_string($details)) {
                $details = json_decode($details);
            }

            if (isset($details->relationship) && isset($item->field)) {
                $relation = $details->relationship;
                if (isset($relation->method)) {
                    $method = $relation->method;
                    $this->relation_field[$method] = $item->field;
                } else {
                    $method = camel_case($item->field);
                }

                $relationships[$method] = function ($query) use ($relation) {
                    // select only what we need
                    if (isset($relation->method)) {
                        return $query;
                    } else {
                        $query->select($relation->key, $relation->label);
                    }
                };
            }
        });

        return $relationships;
    }

    /**
     * Replace relationships' keys for labels and create READ links if a slug is provided.
     *
     * @param  $dataTypeContent     Can be either an eloquent Model, Collection or LengthAwarePaginator instance.
     * @param DataType $dataType
     *
     * @return $dataTypeContent
     */
    protected function resolveRelations($dataTypeContent, DataType $dataType)
    {
        // In case of using server-side pagination, we need to work on the Collection (BROWSE)
        if ($dataTypeContent instanceof LengthAwarePaginator) {
            $dataTypeCollection = $dataTypeContent->getCollection();
        }
        // If it's a model just make the changes directly on it (READ / EDIT)
        elseif ($dataTypeContent instanceof Model) {
            return $this->relationToLink($dataTypeContent, $dataType);
        }
        // Or we assume it's a Collection
        else {
            $dataTypeCollection = $dataTypeContent;
        }

        $dataTypeCollection->transform(function ($item) use ($dataType) {
            return $this->relationToLink($item, $dataType);
        });

        return $dataTypeContent instanceof LengthAwarePaginator ? $dataTypeContent->setCollection($dataTypeCollection) : $dataTypeCollection;
    }

    /**
     * Create the URL for relationship's anchors in BROWSE and READ views.
     *
     * @param Model    $item     Object to modify
     * @param DataType $dataType
     *
     * @return Model $item
     */
    protected function relationToLink(Model $item, DataType $dataType)
    {
        $relations = $item->getRelations();

        if (!empty($relations) && array_filter($relations)) {
            foreach ($relations as $field => $relation) {
                if (isset($this->relation_field[$field])) {
                    $field = $this->relation_field[$field];
                } else {
                    $field = snake_case($field);
                }

                $bread_data = $dataType->browseRows->where('field', $field)->first();
                
                $relationData = $bread_data->details;
                $relationData = $relationData->relationship;

                if ($bread_data->type == 'select_multiple') {
                    $relationItems = [];
                    foreach ($relation as $model) {
                        $relationItem = new \stdClass();
                        $relationItem->{$field} = $model[$relationData->label];
                        if (isset($relationData->page_slug)) {
                            $id = $model->id;
                            $relationItem->{$field.'_page_slug'} = url($relationData->page_slug, $id);
                        }
                        $relationItems[] = $relationItem;
                    }
                    $item[$field] = $relationItems;
                    continue; // Go to the next relation
                }

                if (!is_object($item[$field])) {
                    $item[$field] = $relation[$relationData->label];
                } else {
                    $tmp = $item[$field];
                    $item[$field] = $tmp;
                }
                if (isset($relationData->page_slug) && $relation) {
                    $id = $relation->id;
                    $item[$field.'_page_slug'] = url($relationData->page_slug, $id);
                }
            }
        }

        return $item;
    }
}
