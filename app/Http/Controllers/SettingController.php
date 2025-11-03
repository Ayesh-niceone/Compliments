<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::first() ?? new Setting();
        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'system_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $setting = Setting::first() ?? new Setting();

        $data = $request->only(['system_name', 'email', 'phone', 'address']);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        $setting->fill($data)->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}


