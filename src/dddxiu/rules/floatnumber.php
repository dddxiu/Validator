<?php

namespace Dddxiu\rules;

use Dddxiu\Funnel;

/**
 * 浮点数
 */
class FloatNumber extends Rule
{
    // flag
    const F = 'f';

    // exec sort
    const S = 2;


    /**
     * 浮点数
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        $prev['type'] = Funnel::FIELD_TYPE_NUM;

        $var = $input[$field];
        if (($var == (string)floatval($var))) {
            return false;
        }
        // 不是整数就是浮点 ....
        return !ctype_digit($input[$field]);
    }
}
