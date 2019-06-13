<?php

namespace Dddxiu\rules;

/**
 * 数字字母
 */
class Alnum extends Rule
{
    // flag
    const F = 'w';

    // exec sort
    const S = 2;


    /**
     * 必须校验
     * 
     * @param  [type] $input 输入参数
     * @param  [type] $field 要校验的字段
     * @param  [type] $args  rule_args 规则参数
     * @param  [type] &$next 继续,仅[r,n]使用
     * @return [type]        结果
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        // 处理字符问题
        $len = strlen($input[$field]);
        for ($i=0; $i < $len; $i++) { 
            if (!ctype_alnum($input[$field][$i])) {
                return false;
            }
        }
        return true;
    }
}