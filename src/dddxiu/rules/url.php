<?php

namespace Dddxiu\rules;

/**
 * URL
 */
class URL extends Rule
{
    // flag
    const F = 'url';


    /**
     * URL
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $pass = filter_var($input[$field], FILTER_SANITIZE_URL) !== false;
        if ($pass) {
            return $layer::then($layer::FIELD_TYPE_STR);
        }
        return false;
    }
}