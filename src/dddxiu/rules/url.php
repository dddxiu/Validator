<?php

namespace Dddxiu\rules;

/**
 * URL
 */
class URL extends Rule
{
    // flag
    const F = 'url';

    // exec sort
    const S = 3;


    /**
     * URL
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        return filter_var($input[$field], FILTER_SANITIZE_URL) !== false;
    }
}