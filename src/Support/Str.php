<?php

namespace Innoractive\PushCow\Support;

class Str
{
    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        if (! ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);
            $value = preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value);
            $value = mb_strtolower($value, 'UTF-8');
        }

        return $value;
    }
}
