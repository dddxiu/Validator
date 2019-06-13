<?php

namespace Dddxiu\rules;

/**
 * 正则
 */
class REGX extends Rule
{
    // flag
    const F = 'regx';

    // exec sort
    const S = 3;


    /**
     * 正则
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        return (preg_match($p, $input[$field]) === 1);
    }
}