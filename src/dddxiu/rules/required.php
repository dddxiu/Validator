<?php

namespace Dddxiu\rules;

/**
 * 必须有的值
 */
class Required extends Rule
{
    // flag
    const F = 'r';

    // exec sort
    const S = 0;


    /**
     * 必须校验
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        $next = array_key_exists($field, $input);
        return array_key_exists($field, $input);
    }
}