<?php

namespace Dddxiu\rules;

/**
 * 长度
 */
class Length extends Rule
{
    use \Dddxiu\common\Size;

    // flag
    const F = 'len';


    /**
     * 长度
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
        switch ($type) {
            case $layer::FIELD_TYPE_STR:
                $pass = mb_strlen($input[$field]) == $args[0];
                break;
            case $layer::FIELD_TYPE_NUM:
                $pass = strlen((string)$input[$field]) == $args[0];
                break;
            case $layer::FIELD_TYPE_DATE:
                $pass = false;
                break;
        }
        if ($pass) {
            return $layer::then();
        }
        return false;
    }
}