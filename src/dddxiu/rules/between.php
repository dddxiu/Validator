<?php

namespace Dddxiu\rules;

use Dddxiu\Funnel;

/**
 * 区间
 */
class Between extends Rule
{
    use \Dddxiu\common\Size;
    
    // flag
    const F = 'bt';

    // exec sort
    const S = 5;


    /**
     * 区间
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
        $max  = $args[1] ?? NULL;
        switch ($type) {
            case Funnel::FIELD_TYPE_STR:
                return static::str_len($input[$field], $min, $max);
            case Funnel::FIELD_TYPE_NUM:
                return static::num_size($input[$field], $min, $max);
            case Funnel::FIELD_TYPE_DATE:
                return static::date_span($input[$field], $min, $max);
        }
        return false;
    }
}