<?php

namespace Innoractive\PushCow\Support;

class Str
{
    /**
     * Convert a string to snake case.
     */
    public static function snake(string $value, string $delimiter = '_'): string
    {
        if (! ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);
            $value = preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value);
            $value = mb_strtolower($value, 'UTF-8');
        }

        return $value;
    }
}
