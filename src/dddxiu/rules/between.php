<?php

namespace Dddxiu\rules;

/**
 * 区间
 */
class Between extends Rule
{
    use \Dddxiu\common\Size;
    
    // flag
    const F = 'bt';


    /**
     * 区间
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $type = $layer->extra('type', $layer::FIELD_TYPE_STR);
        $min  = $args[0] ?? NULL;
        $max  = $args[1] ?? NULL;
        switch ($type) {
            case $layer::FIELD_TYPE_STR:
                $pass = static::str_len($input[$field], $min, $max);
                break;
            case $layer::FIELD_TYPE_NUM:
                $pass = static::num_size($input[$field], $min, $max);
                break;
            case $layer::FIELD_TYPE_DATE:
                $pass = static::date_span($input[$field], $min, $max);
                break;
        }
        if ($pass) {
            return $layer::then();
        }
        return false;
    }
}