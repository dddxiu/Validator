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

            if (is_array($rule_str) && is_callable($rule_str[0])) {
                $msg = $rule_str[1] ?? "{$field} format error!";
                $ret = $rule_str[0]($this->input, $field);
                if ($ret === false) {
                    $this->error_list[] = $msg;
                    if ($continue === false) {
                        return $this;
                    }
                }
                continue;
            }

            $rule_array = explode('|', $rule_str);

            // $required
            if (in_array('r', $rule_array)
                && !array_key_exists($field, $this->input)) {
                $this->msg('r', $field, NULL);
                if ($continue === false) {
                    return $this;
                }
                continue;
            } elseif (!in_array('n', $rule_array)) {
                $rule_array[] = 'n';
            }

            // nullable:
            // 1. not n && input not exists
            // 2. n && input not exists
            if (in_array('n', $rule_array)
                && !array_key_exists($field, $this->input)) {
                continue;
            }

            $rule_list = Rule::explode($rule_array);
            if ($rule_list === false) {
                throw new Exception("rule error", 1);
            }
            foreach ($rule_list as $rule_info) {

                // 标准回调
                list($type, $rule, $args) = $rule_info;
                if ($rule === false) {
                    throw new \Exception("rule not exists", 1);
                }

                $ret = true;
                if (is_callable($rule)) {
                    $ret = $rule($this->input, $field);
                }
                if ($ret === false) {
                    $this->msg($type, $field, $args);
                    if ($continue === false) {
                        return $this;
                    }
                }
            }
        }

        // check 校对
        return $this;
    }

    private function msg($t='', $f='', $p='')
    {
        $msg = $this->msgs[$f]??[];
        $err = Message::format($t, [$f, $p], $msg);
        $this->error_list[] = $err;
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
        if ($all === true) {
            return $this->error_list;
        }
        return array_shift($this->error_list);
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