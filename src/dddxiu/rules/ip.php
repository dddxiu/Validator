<?php

namespace Dddxiu\rules;

/**
 * ip
 */
class IP extends Rule
{
    // flag
    const F = 'ip';


    /**
     * ip
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $pat = "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/";
        $pass= (preg_match($pat, $input[$field]) === 1);
        if ($pass) {
            return $layer::then(['type' => $layer::FIELD_TYPE_STR]);
        }
        return false;
    }
}