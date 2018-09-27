<?php

if (!function_exists('is_bread_slug_auto_generator')) {
    /**
     * Check if a field slug can be auto generated.
     *
     * @param json $options
     *
     * @return string HTML output.
     */
    function is_bread_slug_auto_generator($options)
    {
        if (isset($options->slugify)) {
            return ' data-slug-origin='.$options->slugify->origin
                   .((isset($options->slugify->forceUpdate))
                      ? ' data-slug-forceupdate=true'
                      : '');
        }
    }
}

if (!function_exists('is_field_slug_auto_generator')) {
    /**
     * Determine the details field, for a given dataTypeContent.
     *
     * @param Illuminate\Database\Eloquent\Collection $dType    Data type
     * @param Illuminate\Database\Eloquent\Collection $dContent Data type Content
     * @param string                                  $field    Field name
     *
     * @return string HTML output.
     */
    function is_field_slug_auto_generator($dType, $dContent, $field)
    {
        $_row = (isset($dContent->id))
                    ? $dType->editRows
                    : $dType->addRows;

        $_row = $_row->where('field', $field)->first();

        if (!$_row) {
            return;
        }

        return is_bread_slug_auto_generator($_row->details);
    }
}
