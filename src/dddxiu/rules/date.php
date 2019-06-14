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


    /**
     * 日期
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        if (count($args) == 0) {
            $pass = strtotime($input[$field])>0;
        } else {
            $pass = (date($args[0], strtotime($input[$field])) === $input[$field]);
        }

        if ($pass) {
            return $layer::then(['type'=>$layer::FIELD_TYPE_DATE]);
        }
        return false;
    }
}