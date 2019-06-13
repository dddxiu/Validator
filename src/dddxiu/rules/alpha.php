<?php

namespace Dddxiu\rules;

/**
 * 英文字母
 */
class Alpha extends Rule
{
    // flag
    const F = 'a';

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
        return ctype_alpha($input[$field]);
    }
}