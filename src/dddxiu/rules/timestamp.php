<?php

namespace Dddxiu\rules;

/**
 * 时间戳
 */
class Timestamp extends Rule
{
    // flag
    const F = 'ts';

    // exec sort
    const S = 3;


    /**
     * 时间戳
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        $v = $input[$field];
        if (count($args) == 0) {
            return $v>0;
        }

        // 1970+p年有效
        return ($v<3600*24*365*intval($args[0]) && $v>0);
    }
}