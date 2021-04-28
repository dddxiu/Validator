<?php

namespace Dddxiu;

use \Dddxiu\Funnel;
use \Dddxiu\Message;

use Dddxiu\exception\ArgsException;

/**
 * 校验器
 */
class Validator extends Singleton
{
    /**
     * 自定义校验规则
     * 
     * @param  [type] $key  [description]
     * @param  [type] $call [description]
     * @return [type]       [description]
     */
    protected function make($key, $call, $msg)
    {
        Funnel::register($key, $call);
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
    protected function validate($input, $rules, $msgs=[], $ret=false)
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
    protected function input($input)
    {
        $this->input = $input;
        return $this;
    }


    /**
     * 规则
     */
    protected function rules($rules)
    {
        $this->rules = $rules;
        return $this;
    }


    /**
     * 自定义信息
     */
    protected function msgs($msgs=[])
    {
        $this->msgs = [];
        foreach ($msgs as $field_rule => $msg) {
            $field_rule = explode('.', $field_rule);
            if (count($field_rule) !== 2) {
                throw new ArgsException("{$field_rule} {$msg} format error", 1);
            }
            list($field, $rule) = $field_rule;
            $this->msgs[$field][$rule] = $msg;
        }
        return $this;
    }


    /**
     * 检查字段
     */
    protected function check($final=false)
    {
        Funnel::standing($this->input, $this->rules, $final);
        $this->error_list = Funnel::get_errors();
        return $this;
    }


    /**
     * 校验通过
     */
    protected function pass()
    {
        return (count($this->error_list) === 0);
    }


    /**
     * 错误列表
     */
    protected function errors($all=false)
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
    protected function clean()
    {
        $this->rules = [];
        $this->input = [];
        $this->msgs  = [];
        $this->error_list = [];
    }

}