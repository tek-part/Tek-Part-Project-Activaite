<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings', ['only' => ['index', 'edit', 'update']]);
    }

    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings.index', compact('setting'));
    }

    public function edit()
    {
        $setting = Setting::first();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'address'     => 'required|string|max:255',
            'phone1'      => 'required|string|max:20',
            'phone2'      => 'required|string|max:20',
            'description' => 'required|string',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Find the setting by its ID (assuming you're passing the setting as an object to the view)
        $setting = Setting::first();

        // Update the setting fields
        $setting->email = $request->email;
        $setting->phone1 = $request->phone1;
        $setting->phone2 = $request->phone2;

        // Check if a new logo is uploaded
        if ($request->hasFile('logo')) {
            // Delete the old logo from storage if exists
            if ($setting->logo && Storage::exists($setting->logo)) {
                Storage::delete($setting->logo);
            }

            $logoPath = $request->file('logo')->store('settings_logos', 'public');
            $setting->logo = $logoPath;
        }

        $setting->save();

        $setting->name = $request->name;
        $setting->address = $request->address;
        $setting->description = $request->description;
        $setting->save();

        return redirect()->route('admin.settings.index', ['setting' => $setting->id])->with('success', __('messages.update'));
    }
}
