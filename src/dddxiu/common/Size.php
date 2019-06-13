<?php

namespace Dddxiu\common;

/**
 * 处理数字大小,字符长度问题
 */
trait Size {

    /**
     * 字段长度区间
     * 
     * @param  [type] $str [description]
     * @param  [type] $min [description]
     * @param  [type] $max [description]
     * @return [type]      [description]
     */
    public static function str_len($str, $min=NULL, $max=NULL)
    {
        $num = mb_strlen($str);
        if ($min !== NULL && $max !== NULL) {
            return ($num>=$min) && ($num<=$max);
        }

        if ($min !== NULL) {
            return $num >= $min;
        }

        if ($max !== NULL) {
            return $num <= $max;
        }

        throw new ArgsException("{$str} args error", 1);
    }


    /**
     * 数字区间
     * 
     * @param  [type] $num [description]
     * @param  [type] $min [description]
     * @param  [type] $max [description]
     * @return [type]      [description]
     */
    public static function num_size($num, $min=NULL, $max=NULL)
    {
        if ($min !== NULL && $max !== NULL) {
            return ($num>=$min) && ($num<=$max);
        }

        if ($min !== NULL) {
            return $num >= $min;
        }

        if ($max !== NULL) {
            return $num <= $max;
        }

        throw new ArgsException("{$str} args error", 1);
    }


    /**
     * 时间区间
     * 
     * @param  [type] $date [description]
     * @param  [type] $min  [description]
     * @param  [type] $max  [description]
     * @return [type]       [description]
     */
    public static function date_span($date, $min=NULL, $max=NULL)
    {
        $num = strtotime($date);
        if ($min !== NULL && $max !== NULL) {
            return ($num>=strtotime($min)) && ($num<=strtotime($max));
        }

        if ($min !== NULL) {
            return $num >= strtotime($min);
        }

        if ($max !== NULL) {
            return $num <= strtotime($max);
        }

        throw new ArgsException("{$str} args error", 1);
    }

}