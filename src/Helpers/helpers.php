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