<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return \App\Models\Setting::select($key)->value($key) ?? $default;
    }
}

function logo()
{
    $logo =\App\Models\Setting::whereNotNull('id')->first();
    return $logo ? asset('storage/' . $logo->logo) : asset('../assets/images/logos/logo.png');
}
