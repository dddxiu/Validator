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
        $prev['type'] = Funnel::FIELD_TYPE_NUM;
        $var = $input[$field];
        return $var == (string)floatval($var);
    }
}