<?php

namespace Dddxiu\rules;

/**
 * 规则校验
 */
abstract class Rule
{
    /**
     * 验证规则
     * 
     * @param  array   $input 输入数据
     * @param  string  $field 要校验的字段
     * @param  boolean &$next 是否继续
     * @return boolean 是否成功
     */
    abstract public static function valid($input, $field, $layer, $args);
}