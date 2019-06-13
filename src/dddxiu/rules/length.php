<?php

namespace Dddxiu\rules;

use Dddxiu\Funnel;

/**
 * 长度
 */
class Length extends Rule
{
    use \Dddxiu\common\Size;

    // flag
    const F = 'len';

    // 处理顺序
    const S = 5;


    /**
     * 长度
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
        switch ($type) {
            case Funnel::FIELD_TYPE_STR:
                return mb_strlen($input[$field]) == $args[0];
            case Funnel::FIELD_TYPE_NUM:
                return strlen((string)$input[$field]) == $args[0];
            case Funnel::FIELD_TYPE_DATE:
                return false;
        }
        return false;
    }
}