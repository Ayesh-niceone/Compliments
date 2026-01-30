<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        $setting = \App\Models\Setting::whereNotNull('id')->first();
        return $setting ? ($setting->$key ?? $default) : $default;
    }
}

function logo()
{
    $logo =\App\Models\Setting::whereNotNull('id')->first();
    return $logo ? asset('storage/' . $logo->logo) : asset('../assets/images/logos/logo.png');
}
