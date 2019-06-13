<?php

namespace Dddxiu\rules;

/**
 * 电子邮箱
 */
class Mail extends Rule
{
    // flag
    const F = 'mail';

    // exec sort
    const S = 3;


    /**
     * 电子邮箱
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $args, &$next, &$prev)
    {
        return filter_var($input[$field], FILTER_VALIDATE_EMAIL) !== false;
    }
}