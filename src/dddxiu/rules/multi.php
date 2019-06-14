<?php

namespace Dddxiu\rules;

/**
 * 多选
 */
class Multi extends Rule
{
    // flag
    const F = 'multi';


    /**
     * 多选
     * 
     * @param  [type] $input [description]
     * @param  [type] $field [description]
     * @param  [type] $args  [description]
     * @param  [type] &$next [description]
     * @return [type]        [description]
     */
    public static function valid($input, $field, $layer, $args)
    {
        $vars = $input[$field];
        if (is_string($vars)) {
            $vars = explode(',', $vars);
        }

        if (!is_array($vars)) {
            return false;
        }

        foreach ($vars as $var) {
            if (!in_array($var, $args)) {
                return false;
            }
        }
        return $layer::then();
    }
}
