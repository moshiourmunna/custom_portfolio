<?php

use App\Support\Mill;

if (! function_exists('mill_url')) {
    function mill_url(?string $path): ?string
    {
        return Mill::url($path);
    }
}

if (! function_exists('mill_filled')) {
    function mill_filled(?string $value): bool
    {
        return Mill::filled($value);
    }
}
