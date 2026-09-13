<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingAdminController extends Controller
{
    public function edit(): Response
    {
        $setting = Setting::query()->with('socialLinks')->firstOrFail();

        return Inertia::render('Settings', ['setting' => $setting]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = Setting::query()->firstOrFail();
        $setting->update($request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:180'],
            'website' => ['nullable', 'string', 'max:180'],
            'canonical_base' => ['nullable', 'string', 'max:180'],
            'footer_blurb' => ['nullable', 'string'],
            'hours' => ['nullable', 'string', 'max:180'],
            'office_address' => ['nullable', 'string', 'max:255'],
            'mill_address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:190'],
            'office_pin' => ['nullable', 'string', 'max:255'],
            'mill_pin' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],
            'favicon' => ['nullable', 'string', 'max:255'],
            'theme_primary' => ['nullable', 'string', 'max:16'],
            'theme_deep' => ['nullable', 'string', 'max:16'],
            'theme_accent' => ['nullable', 'string', 'max:16'],
            'theme_surface' => ['nullable', 'string', 'max:16'],
            'ga' => ['nullable', 'string', 'max:64'],
            'gtm' => ['nullable', 'string', 'max:64'],
            'meta_pixel' => ['nullable', 'string', 'max:64'],
            'meta_domain' => ['nullable', 'string', 'max:190'],
            'gsc' => ['nullable', 'string', 'max:255'],
            'bing' => ['nullable', 'string', 'max:255'],
            'maintenance' => ['boolean'],
        ]));

        foreach ($request->input('social_links', []) as $link) {
            if (empty($link['id'])) {
                continue;
            }
            SocialLink::query()->where('setting_id', $setting->id)->whereKey($link['id'])->update([
                'url' => $link['url'] ?? '',
            ]);
        }

        Setting::forgetCurrent();

        return back()->with('status', 'Settings saved.');
    }
}
