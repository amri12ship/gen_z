<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public const KEYS = [
        'site_name',
        'tagline',
        'description',
        'contact_email',
        'instagram_url',
        'tiktok_url',
    ];

    public function edit()
    {
        $settings = collect(self::KEYS)
            ->mapWithKeys(fn ($key) => [$key => Setting::get($key)]);

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1000'],
            'contact_email' => ['nullable', 'email', 'max:180'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            Setting::put($key, $value);
        }

        return back()->with('status', 'Pengaturan berhasil disimpan.');
    }
}
