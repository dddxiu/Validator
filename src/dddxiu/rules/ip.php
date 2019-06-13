<?php

namespace Dddxiu\rules;

/**
 * ip
 */
class IP extends Rule
{
    // flag
    const F = 'ip';

    // exec sort
    const S = 3;


    /**
     * ip
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        $pat = "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/";
        return (preg_match($pat, $input[$field]) === 1);
    }
}