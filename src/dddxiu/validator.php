<?php

namespace Dddxiu;

use \Dddxiu\Funnel;
use \Dddxiu\Message;

use Dddxiu\exception\ArgsException;

/**
 * 校验器
 */
class Validator
{
    /**
     * 自定义校验规则
     * 
     * @param  [type] $key      [description]
     * @param  [type] $callable [description]
     * @return [type]           [description]
     */
    private function make($key, $callable, $msg)
    {
        Funnel::register($key, $callable);
        Message::register($key, $msg);
    }


    /**
     * 校对信息
     * 
     * @param  array  $input 输入参数
     * @param  array  $rules 校对规则
     * @param  array|boolean  $msgs  自定义消息|立即返回,传入boolean会忽略$ret
     * @param  boolean $ret   立即返回
     * @return bool|array     校对结果或则错误信息
     */
    private function validate($input, $rules, $msgs=[], $ret=false)
    {
        $this->input($input)->rules($rules);
        if (is_bool($msgs)) {
            return $this->check($msgs);
        }
        return $this->msgs($msgs)->check($ret);
    }


    /**
     * 输入
     */
    private function input($input)
    {
        $this->input = $input;
        return $this;
    }


    /**
     * 规则
     */
    private function rules($rules)
    {
        $this->rules = $rules;
        return $this;
    }


    /**
     * 自定义信息
     */
    private function msgs($msgs=[])
    {
        $this->msgs = [];
        foreach ($msgs as $field_rule => $msg) {
            $field_rule = explode('.', $field_rule);
            if (count($field_rule) !== 2) {
                throw new \Exception("{$field_rule} {$msg} format error", 1);
            }
            list($field, $rule) = $field_rule;
            $this->msgs[$field][$rule] = $msg;
        }
        return $this;
    }


    /**
     * 检查字段
     */
    private function check($final=false)
    {
        Funnel::standing($this->input, $this->rules, $final);
        $this->error_list = Funnel::get_errors();
        return $this;
    }


    /**
     * 校验通过
     */
    private function pass()
    {
        return (count($this->error_list) === 0);
    }


    /**
     * 错误列表
     */
    private function errors($all=false)
    {
        $temp_errors = [];
        foreach ($this->error_list as $field => $rule_list) {
            $msg = $this->msgs[$field]??[];

            foreach ($rule_list as $rule => $value) {
                list($input_val, $rule_val) = $value;
                $err = Message::format($rule, [$field, $rule_val], $msg);
                $temp_errors[] = $err;
                if ($all === false) {
                    return $temp_errors;
                }
            }
        }

        return $temp_errors;
    }


    /**
     * 清理错误
     */
    private function clean()
    {
        $this->rules = [];
        $this->input = [];
        $this->msgs  = [];
        $this->error_list = [];
    }
    

    static private $instance;


    public static function __callStatic($method_name, $args)
    {
        $instance = static::getInstance();
        if (method_exists($instance, $method_name)) {
            return call_user_func_array([$instance, $method_name], $args);
        };
        throw new Exception("{$method_name} not exists", 1);
    }


    public function __call($name, $args)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $args);
        };
        throw new ArgsException("{$name} not exists", 1);
    }

    
    public static function getInstance()
    {
        if (NULL == self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}

/**
 * validator::check($input, $rule, $msg, $ret)
 */
function vc($input, $rule, $msg=[], $ret=false)
{
    return Validator::validate($input, $rule, $msg, $ret);
}

/**
 * validator::pass()
 */
function vp()
{
    return Validator::pass();
}

/**
 * validator::error()
 */
function ve($all=false)
{
    return Validator::errors($all);
}
