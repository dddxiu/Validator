<?php

namespace Dddxiu\rules;

/**
 * 最小值
 */
class Min extends Rule
{
    use \Dddxiu\common\Size;

    // flag
    const F = 'min';


    /**
     * 最小值
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
        switch ($type) {
            case $layer::FIELD_TYPE_STR:
                $pass = static::str_len($input[$field], $min, NULL);
                break;
            case $layer::FIELD_TYPE_NUM:
                $pass = static::num_size($input[$field], $min, NULL);
                break;
            case $layer::FIELD_TYPE_DATE:
                $pass = static::date_span($input[$field], $min, NULL);
                break;
        }
        if ($pass) {
            return $layer::then();
        }
        return false;
    }
}