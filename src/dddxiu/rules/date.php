<?php

namespace Dddxiu\rules;

use Dddxiu\Funnel;

/**
 * 日期
 */
class Date extends Rule
{
    // flag
    const F = 'date';

    // exec sort
    const S = 2;


    /**
     * 日期
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        $prev['type'] = Funnel::FIELD_TYPE_DATE;

        if (count($args) == 0) {
            return strtotime($input[$field])>0;
        }

        // 格式化 Y-m-d
        return (date($args[0], strtotime($input[$field])) === $input[$field]);
    }
}