<?php

if (!function_exists('__')) {
    function __($key, array $par = [])
    {
        return trans($key, $par);
    }
}

if (!function_exists('trans_permission')) {
    function trans_permission($perm_key)
    {
        $data = explode('_', $perm_key);
        $action = $data[0];
        $module = $data[1];

        if (count($data) > 2) {
            $module_name = $data;
            unset($module_name[0]);
            $data[1] = $module = implode('_', $module_name);
        }

        $action = 'versatile::generic.' . $action;
        $module = 'versatile::generic.' . $module;

        $action_str = $data[0];
        if (\Illuminate\Support\Facades\Lang::has($action)) {
            $action_str = __($action);
        }

        $module_str = $data[1];
        if (\Illuminate\Support\Facades\Lang::has($module)) {
            $module_str = __($module);
        }

        $module_str = "<span class='badge'>" . str_replace('_',' ', $module_str) . "</span>";

        return title_case("{$action_str} {$module_str}");
    }
}

if (!function_exists('trans_module_name')) {
    function trans_module_name($module_name)
    {
        if (empty($module_name)) {
            return null;
        }

        $translate = 'versatile::generic.' . $module_name;

        if (\Illuminate\Support\Facades\Lang::has($translate)) {
            return __($translate);
        }

        return title_case(str_replace('_',' ', $module_name));
    }
}