<?php

namespace Dddxiu\rules;

/**
 * json
 */
class JSON extends Rule
{
    // flag
    const F = 'json';


    /**
     * JSON
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $pass = (json_decode($input[$field], true) !== NULL);
        if ($pass) {
            return $layer::then(['type' => $layer::FIELD_TYPE_STR]);
        }
        return false;
    }
}