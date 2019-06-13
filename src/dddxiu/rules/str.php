<?php

namespace Dddxiu\rules;

/**
 * 字符串
 */
class Str extends Rule
{
    // flag
    const F = 's';

    // exec sort
    const S = 2;


    /**
     * 没有值就不继续校验
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        return is_string($input[$field]);
    }
}