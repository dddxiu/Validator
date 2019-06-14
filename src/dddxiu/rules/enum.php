<?php

namespace Dddxiu\rules;

/**
 * 单选
 */
class Enum extends Rule
{
    // flag
    const F = 'e';


    /**
     * 单选
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $pass = in_array($input[$field], $args);
        if ($pass) {
            return $layer::then();
        }
        return false;
    }
}
