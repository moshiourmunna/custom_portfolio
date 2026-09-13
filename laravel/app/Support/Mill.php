<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Mill
{
    public static function filled(?string $value): bool
    {
        return $value !== null && trim($value) !== '';
    }

    public static function url(?string $path): ?string
    {
        if (! self::filled($path)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($path));

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        if (Str::startsWith($path, 'assets/')) {
            return asset($path);
        }

        if (Str::startsWith($path, 'media/')) {
            return asset('storage/'.$path);
        }

        return asset($path);
    }

    public static function storagePath(?string $path): ?string
    {
        if (! self::filled($path)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($path));
        $path = preg_replace('#^assets/images/#', 'media/', $path) ?? $path;

        return ltrim($path, '/');
    }

    public static function publicHref(?string $href): ?string
    {
        if (! self::filled($href)) {
            return null;
        }

        $map = [
            'index.html' => '/',
            'about.html' => '/about',
            'process.html' => '/process',
            'facilities.html' => '/facilities',
            'quality.html' => '/quality',
            'sustainability.html' => '/sustainability',
            'contact.html' => '/contact',
            'privacy.html' => '/privacy',
            'terms.html' => '/terms',
            'quote-success.html' => '/quote-success',
            'products/index.html' => '/products',
            'products/woven-fabric.html' => '/products/woven-fabric',
            'gallery/index.html' => '/gallery',
            'news/index.html' => '/news',
            'careers/index.html' => '/careers',
        ];

        $hash = '';
        if (str_contains($href, '#')) {
            [$href, $fragment] = explode('#', $href, 2);
            $hash = '#'.$fragment;
        }

        $href = ltrim(str_replace('\\', '/', $href), './');

        return ($map[$href] ?? (Str::startsWith($href, '/') ? $href : '/'.$href)).$hash;
    }

    public static function filterCount(?string $count): ?string
    {
        if (! self::filled($count) || ! preg_match('/(\d+)/', $count, $match)) {
            return null;
        }

        $value = (int) $match[1];

        return match (true) {
            $value >= 50 => 'fine',
            $value >= 30 => 'medium',
            default => 'coarse',
        };
    }

    public static function themeCss(?Setting $setting): string
    {
        $primary = $setting?->theme_primary ?: '#004d40';
        $deep = $setting?->theme_deep ?: '#003d33';
        $accent = $setting?->theme_accent ?: '#548c84';
        $surface = $setting?->theme_surface ?: '#f4f7f6';

        return ":root{--color-primary:{$primary};--color-primary-deep:{$deep};--color-accent:{$accent};--color-surface:{$surface};}";
    }

    public static function copyPublicImage(string $sourceRelative): ?string
    {
        $sourceRelative = ltrim(str_replace('\\', '/', $sourceRelative), '/');
        $relative = preg_replace('#^assets/images/#', '', $sourceRelative) ?? $sourceRelative;
        $source = public_path('assets/images/'.$relative);
        $dest = 'media/'.$relative;

        if (! is_file($source)) {
            return null;
        }

        if (! Storage::disk('public')->exists($dest)) {
            Storage::disk('public')->put($dest, file_get_contents($source));
        }

        return $dest;
    }
}
