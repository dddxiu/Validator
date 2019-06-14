<?php

namespace Dddxiu\rules;

use Dddxiu\Funnel;

/**
 * 数字,包括浮点数
 */
class Digit extends Rule
{
    // flag
    const F = 'd';


    /**
     * 没有值就不继续校验
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $var  = $input[$field];
        $pass = $var == (string)floatval($var);
        if ($pass) {
            return $layer::then(['type'=>$layer::FIELD_TYPE_NUM]);
        }
        return false;
    }
}