<?php

namespace Dddxiu\rules;

/**
 * 汉字
 */
class Chinese extends Rule
{
    // flag
    const F = 'zh';

    // exec sort
    const S = 3;


    /**
     * 汉字
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        return (preg_match('/^[\x4e00-\x9fa5]+$/', $input[$field]) === 1);
    }
}