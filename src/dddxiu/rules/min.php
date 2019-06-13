<?php

namespace Dddxiu\rules;

use Dddxiu\Funnel;

/**
 * 最小值
 */
class Min extends Rule
{
    use \Dddxiu\common\Size;

    // flag
    const F = 'min';

    // exec sort
    const S = 5;


    /**
     * 最小值
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        $type = $prev['type'] ?? Funnel::FIELD_TYPE_STR;
        $min  = $args[0] ?? NULL;
        switch ($type) {
            case Funnel::FIELD_TYPE_STR:
                return static::str_len($input[$field], $min, NULL);
            case Funnel::FIELD_TYPE_NUM:
                return static::num_size($input[$field], $min, NULL);
            case Funnel::FIELD_TYPE_DATE:
                return static::date_span($input[$field], $min, NULL);
        }
        return false;
    }
}