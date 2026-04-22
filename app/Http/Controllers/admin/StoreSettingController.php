<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class StoreSettingController extends Controller
{
    /**
     * Display the store settings form.
     */
    public function index()
    {
        $settings = StoreSetting::first();
        return view('pages.settings.index', compact('settings'));
    }

    /**
     * Update the store settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_contact' => 'nullable|string|max:20',
            'store_address' => 'nullable|string',
        ]);

        $settings = StoreSetting::first();

        if ($settings) {
            $settings->update($validated);
        } else {
            StoreSetting::create($validated);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Store settings updated successfully!');
    }
}
