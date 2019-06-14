<?php

namespace Dddxiu\rules;

/**
 * 必须有的值
 */
class Required extends Rule
{
    // flag
    const F = 'r';

    /**
     * 必须校验
     * 两个返回值:
     *     1.校对结果   pass
     *     2.是否打翻漏斗 overturn
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $funnel[description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $pass = array_key_exists($field, $input);
        if ($pass) {
            return $layer::then();
        }
        return false;
    }
}