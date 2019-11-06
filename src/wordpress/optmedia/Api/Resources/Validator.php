<?php

namespace OptMedia\Api\Resources;

class Validator
{
    /**
     * Check if a variable is empty
     *
     * @param mixed $value The variable
     * @param string $key The variable key if it's an array
     * @return boolean If the variable is empty
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function isEmpty($value, $key = "")
    {
        if ($key) {
            return !isset($value[$key]) || empty($value[$key]);
        } else {
            return empty($value);
        }
    }
}
