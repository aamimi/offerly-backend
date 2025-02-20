<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Str;

final readonly class Make
{
    /**
     * Make a key from a string or an array.
     *
     * @param  string|array<string>  $key
     */
    public static function makeKey(string|array $key): string
    {
        if (is_array($key)) {
            $key = implode('-', $key);
        }

        if (mb_strlen($key) > 64) {
            $key = hash('sha256', $key);
        }

        return Str::slug($key);
    }
}
