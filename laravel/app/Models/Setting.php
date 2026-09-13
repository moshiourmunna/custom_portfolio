<?php

namespace App\Models;

use App\Support\Mill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'site_name', 'tagline', 'website', 'canonical_base', 'footer_blurb', 'hours',
        'office_address', 'mill_address', 'phone', 'email', 'office_pin', 'mill_pin',
        'meta_title', 'meta_description', 'og_image', 'logo', 'logo_dark', 'favicon',
        'theme_primary', 'theme_deep', 'theme_accent', 'theme_surface',
        'ga', 'gtm', 'meta_pixel', 'meta_domain', 'gsc', 'bing', 'maintenance',
    ];

    protected function casts(): array
    {
        return ['maintenance' => 'boolean'];
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(SettingContact::class)->orderBy('sort');
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('sort');
    }

    public static function current(): self
    {
        $id = Cache::get('settings.current_id');
        $setting = $id
            ? static::query()->with(['contacts', 'socialLinks'])->find($id)
            : null;

        if (! $setting) {
            $setting = static::query()->with(['contacts', 'socialLinks'])->first() ?? new static([
                'site_name' => 'Islam Textile',
                'tagline' => 'Weaving Tradition, Ensuring Quality',
                'theme_primary' => '#004d40',
                'theme_deep' => '#003d33',
                'theme_accent' => '#548c84',
                'theme_surface' => '#f4f7f6',
            ]);
            if ($setting->exists) {
                Cache::put('settings.current_id', $setting->id, 3600);
            }
        }

        return $setting;
    }

    public static function forgetCurrent(): void
    {
        Cache::forget('settings.current_id');
        Cache::forget('settings.current');
    }

    public function logoUrl(): string
    {
        return Mill::url($this->logo) ?: asset('assets/images/brand/logo-01-monogram-it-ribbon.png');
    }

    public function faviconUrl(): string
    {
        return Mill::url($this->favicon) ?: asset('assets/images/brand/favicon.png');
    }

    public function telHref(): ?string
    {
        if (! Mill::filled($this->phone)) {
            return null;
        }

        return 'tel:'.preg_replace('/[^\d+]/', '', $this->phone);
    }
}
