<?php

namespace Dddxiu\rules;

/**
 * json
 */
class JSON extends Rule
{
    // flag
    const F = 'json';

    // exec sort
    const S = 3;


    /**
     * JSON
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        return (json_decode($input[$field], true) !== NULL);
    }
}