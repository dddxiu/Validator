<?php

namespace Dddxiu\rules;

/**
 * 手机
 */
class phoneCN extends Rule
{
    // flag
    const F = 'phoneCN';


    /**
     * 手机
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $pass = (preg_match("/^1[3456789]\d{9}$/", $input[$field]) === 1);
        if ($pass) {
            return $layer::then($layer::FIELD_TYPE_STR);
        }
        return false;
    }
}