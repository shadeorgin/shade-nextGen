<?php
/**
* Safe array get with default value (version-compatible replacement for null coalescing)
* @param array $array The array to check
* @param string|int $key The key to look for
* @param mixed $default Default value if key doesn't exist
* @return mixed The value or default
*/
function array_get($array, $key, $default = null) {
    if (!is_array($array)) {
        return $default;
    }
    return isset($array[$key]) ? $array[$key] : $default;
}

