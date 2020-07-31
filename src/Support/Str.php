<?php


namespace Daalvand\CsvGenerator\Support;


class Str
{

    /**
     * @param string $string
     * @return string|string[]|null
     */
    public static function plural(string $string)
    {
        return Inflect::pluralize($string);
    }

    /**
     * @param string $string
     * @return string|string[]|null
     */
    public static function singular(string $string)
    {
        return Inflect::singularize($string);
    }

    /**
     * Parse a Class@method callback into class and method.
     *
     * @param  string  $callback
     * @param  string|null  $default
     * @return array
     */
    public static function parseCallback($callback, $default = null)
    {
        return static::contains($callback, '@') ? explode('@', $callback, 2) : [$callback, $default];
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}