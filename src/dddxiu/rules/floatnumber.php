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


    /**
     * 浮点数
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        // 是不是数字
        $var = $input[$field];
        if (($var == (string)floatval($var))) {
            return false;
        }

        // 不是整数就是浮点 ....
        $pass = !ctype_digit($input[$field]);
        if ($pass) {
            return $layer::then(['type' => Funnel::FIELD_TYPE_NUM]);
        }
        return false;

    }
}
