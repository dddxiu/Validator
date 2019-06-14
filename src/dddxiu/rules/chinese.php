<?php

namespace Dddxiu\rules;

/**
 * 汉字
 */
class Chinese extends Rule
{
    // flag
    const F = 'zh';


    /**
     * 汉字
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $layer [description]
     * @param  [type] $args  [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        // u 模式修正与 中文utf8 \u4e00 冲突
        $pass = (preg_match('/^[\x{4e00}-\x{9fa5}]+/u', $input[$field]) === 1);
        if ($pass) {
            return $layer::then(['type'=>$layer::FIELD_TYPE_STR]);
        }
        return false;
    }
}