<?php

namespace Dddxiu\rules;

/**
 * 正则
 */
class REGX extends Rule
{
    // flag
    const F = 'regx';


    /**
     * 正则
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $pass = (preg_match($args[0], $input[$field]) === 1);
        if ($pass) {
            return $layer::then($layer::FIELD_TYPE_STR);
        }
        return false;
    }
}