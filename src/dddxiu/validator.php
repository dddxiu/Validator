<?php

namespace Dddxiu;

use \Dddxiu\Rule;
use \Dddxiu\Message;

/**
 * 校验器
 */
class Validator
{
    static private $instance;

    public function __construct()
    {
        # code...
    }

    /**
     * 自定义校验规则
     * @param  [type] $key      [description]
     * @param  [type] $callable [description]
     * @return [type]           [description]
     */
    private function make($key, $callable, $msg)
    {
        Rule::register($key, $callable);
        Message::register($key, $msg);
    }

    /**
     * 校对信息
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
    public function input($input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * 规则
     */
    public function rules($rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * 自定义信息
     */
    public function msgs($msgs=[])
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
     * 校验
     */
    public function check($continue=false)
    {
        $this->error_list = [];
        $rule_list_user = $this->rules;
        foreach ($rule_list_user as $field => $rule_str) {
            $rule_list = Rule::explode($rule_str);

            $required = false;
            foreach ($rule_list as $v) {
                if (is_array($v) && is_string($v[0]) && $v[0] === 'r') {
                    $required = true;
                    break;
                }
            }

            // require 先取
            $input_field = $this->input[$field] ?? false;
            if ($input_field === false && !$required) {
                continue;
            }

            if ($rule_list === false) {
                throw new Exception("rule error", 1);
            }

            foreach ($rule_list as $rule_info) {
                // 回调
                if (is_callable($rule_info)) {
                    $ret = $rule_info($this->input[$field]);
                    if ($ret === false) {
                        $this->error_list[] = "{$field} 格式不正确!";
                        if ($continue === false) {
                            return $this;
                        }
                    }
                    continue;
                }

                // 标准回调
                list($type, $rule, $args) = $rule_info;
                if ($rule === false) {
                    throw new \Exception("rule not exists", 1);
                }
                $ret = $rule($this->input, $field);
                if ($ret === false) {
                    $msg = $this->msgs[$field]??[];
                    $err = Message::format($type, [$field, $args], $msg);
                    $this->error_list[] = $err;
                    if ($continue === false) {
                        return $this;
                    }
                }
            }
        }

        // check 校对
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
        if ($all===true) {
            return $this->error_list;
        }
        return array_shift($this->error_list);
    }

    public static function __callStatic($method_name, $args)
    {
        $instance = static::getInstance();
        if (method_exists($instance, $method_name)) {
            return call_user_func_array([$instance, $method_name], $args);
        };
        throw new Exception("{$method_name} not exists", 1);
    }

    public static function getInstance()
    {
        if (NULL == self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}