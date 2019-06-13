<?php

namespace Dddxiu\rules;

/**
 * 不必要的值
 */
class Nullable extends Rule
{
    // flag
    const F = 'n';

    // exec sort
    const S = 1;


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
        $next = array_key_exists($field, $input);
        return true;
    }
}