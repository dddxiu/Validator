<?php

namespace Dddxiu\rules;

/**
 * 时间戳
 */
class Timestamp extends Rule
{
    // flag
    const F = 'ts';


    /**
     * 时间戳
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $v = $input[$field];
        if (count($args) == 0) {
            $pass = $v>0;
        } else {
            // 1970+p年有效
            $pass = ($v<3600*24*365*intval($args[0]) && $v>0);
        }

        if ($pass) {
            return $layer::then();
        }
        return false;
    }
}