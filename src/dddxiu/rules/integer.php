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

        return ctype_digit($input[$field]);
    }
}