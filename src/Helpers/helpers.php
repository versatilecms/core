<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return Versatile\Core\Facades\Versatile::setting($key, $default);
    }
}

if (!function_exists('menu')) {
    function menu($menuName, $type = null, array $options = [])
    {
        return Versatile\Core\Facades\Versatile::model('Menu')->display($menuName, $type, $options);
    }
}

if (!function_exists('versatile_asset')) {
    function versatile_asset($path, $secure = null)
    {
        return asset(config('versatile.assets_path').'/'.$path, $secure);
    }
}

if (!function_exists('form_field')) {
    function form_field($row, $dataType, $dataTypeContent)
    {
        return app('fields')->formField($row, $dataType, $dataTypeContent);
    }
}

if (!function_exists('after_form_fields')) {
    function after_form_fields($row, $dataType, $dataTypeContent)
    {
        return app('fields')->afterFields($row, $dataType, $dataTypeContent);
    }
}

if (!function_exists('form_fields')) {

    /**
     * Show only form fields allowed
     *
     * @param $dataTypeRows
     * @param $dataTypeContent
     * @param array $only
     * @param array $exclude
     * @return string
     * @throws Throwable
     */
    function form_fields($dataTypeRows, $dataTypeContent, $only = [], $exclude = [])
    {
        return view('versatile::bread.partials.fields', [
            'dataTypeRows' => $dataTypeRows,
            'dataTypeContent' => $dataTypeContent,
            'only' => $only,
            'exclude' => $exclude
        ])->render();
    }
}

if (!function_exists('delete_bread')) {
    function delete_bread($name)
    {
        $dataType = \Versatile\Core\Models\DataType::where('name', $name)->first();
        if (is_null($dataType)) {
            return;
        }

        // Delete Translations, if present
        if (is_bread_translatable($dataType)) {
            $dataType->deleteAttributeTranslations($dataType->getTranslatableAttributes());
        }

        if (!is_null($dataType)) {
            \Versatile\Core\Models\Permission::removeFrom($dataType->name);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($dataType->name)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $menu = \Versatile\Core\Models\MenuItem::where('title', $dataType->display_name_plural)->delete();

        \Versatile\Core\Models\DataType::destroy($dataType->id);
    }
}

if (!function_exists('convert_date_format')) {
    function convert_date_format($date, $inputFormat = 'd/m/Y H:i:s', $outputFormat = 'Y-m-d H:i:s')
    {
        $date = \Carbon\Carbon::createFromFormat($inputFormat, $date);
        return $date->format($outputFormat);
    }
}