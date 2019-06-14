<?php

namespace Dddxiu\rules;

use Dddxiu\Funnel;

/**
 * 整数
 */
class Integer extends Rule
{
    // flag
    const F = 'i';


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
        $pass = ctype_digit($input[$field]);
        if ($pass) {
            return $layer::then(['type' => Funnel::FIELD_TYPE_NUM]);
        }
        return false;
    }
}