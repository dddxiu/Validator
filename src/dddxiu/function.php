<?php

/**
 * validator::validate($input, $rule, $msg, $ret)
 */
function vv($input, $rule, $msg=[], $ret=false)
{
    return \Dddxiu\Validator::validate($input, $rule, $msg, $ret);
}

/**
 * validator::pass()
 */
function vp()
{
    return \Dddxiu\Validator::pass();
}

/**
 * validator::error()
 */
function ve($all=false)
{
    return \Dddxiu\Validator::errors($all);
}
