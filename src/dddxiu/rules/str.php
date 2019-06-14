<?php

namespace Dddxiu\rules;

/**
 * 字符串
 */
class Str extends Rule
{
    // flag
    const F = 's';


    /**
     * 没有值就不继续校验
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $pass = is_string($input[$field]);
        if ($pass) {
            return $layer::then($layer::FIELD_TYPE_STR);
        }
        return false;
    }
}