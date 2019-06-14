<?php

namespace Dddxiu;

use Dddxiu\exception\ArgsException;
use Dddxiu\exception\RuleNotExistException;

/**
 * 层处理
 */
class Layer extends Singleton
{
    // 处理的字段
    private $field = NULL;

    // 内置规则列表
    private $rules = [];

    // 输入
    private $input = [];

    // 待处理规则列表
    private $exec_rules = [];

    // 额外参数
    private $extra = [];

    // 元素基础类型
    const FIELD_TYPE_STR  = 'str';
    const FIELD_TYPE_NUM  = 'num';
    const FIELD_TYPE_DATE = 'date';


    /**
     * 元数据
     * 
     * @param  [type] &$input  [description]
     * @param  [type] &$rules  [description]
     * @param  [type] &$funnel [description]
     * @param  [type] &$final  [description]
     * @return [type]          [description]
     */
    protected function meta($input, $rules, $funnel, $final)
    {
        $this->input = $input;
        $this->rules = $rules;
        $this->funnel= $funnel;
        $this->final = $final;
    }

    /**
     * 执行过滤
     * 
     * @param  [type] $input 输入
     * @param  [type] $rules 校验的规则列表
     * @return [type]         [description]
     */
    protected function filter($field, $exps)
    {
        $this->field = $field;
        $this->exps  = $exps;

        if (is_string($exps)) {
            return $this->exps_field($exps);
        }

        if (is_array($exps) && is_callable($exps[0])) {
            throw new Exception("回调还没有实现", 1);
            return true;
        }

        if (is_callable($exps)) {
            throw new Exception("回调还没处理", 1);
            return true;
        }

        throw new ArgsException("Error:".__METHOD__, 1);
    }


    /**
     * 表达式字段处理
     */
    protected function exps_field($exps='')
    {
        $this->exec_rules = [];
        foreach (explode('|', $exps) as $exp) {
            $args = NULL;
            if (strpos($exp, ':')) {
                $args = explode(',', substr(strstr($exp, ':'), 1));
                $exp  = strstr($exp, ':', true);
            }
            $this->exec_rules[] = [$exp, $args];
        }

        // r和n必须放到第一个位置
        if (!in_array($exps[0] ?? NULL, ['r', 'n'])) {
            array_unshift($this->exec_rules, ['n', NULL]);
        }

        // 处理字段
        return $this->then();
    }


    /**
     * 层处理
     * 
     * @param  [type] $funnel [description]
     * @return [type]         [description]
     */
    protected function then($extra=NULL)
    {
        if ($this->empty()) {
            return true;
        }

        list($exp, $exp_args) = $this->pop_item();
        $exec = $this->rules[$exp] ?? false;
        if ($exec === false) {
            throw new RuleNotExistException("FLAG {$exp} not exists", 1);
        }

        // 1. Closure
        if (is_a($exec, 'Closure')) {
            $pass = $exec($this->input, $this->field, $this, $exp_args);

        // 2. local rule
        } elseif (get_parent_class($exec) === 'Dddxiu\\rules\\Rule') {
            $pass = $exec::valid($this->input, $this->field, $this, $exp_args);

        // 3. other
        } else {
            throw new ArgsException("not support validator:{$exec}", 1);
        }

        if ($pass === true 
            && is_array($extra)
            && count($extra)) {
            $this->extra += $extra;
        }

        if (empty($pass)) {
            $value = $this->input[$this->field] ?? NULL;
            $this->funnel->error_record($this->field, $value, $exp, $exp_args);
        }

        return true;
    }


    /**
     * 是否有下一个规则
     * 
     */
    protected function empty()
    {
        return empty($this->exec_rules);
    }


    /**
     * 弹出一个规则
     * 
     */
    protected function pop_item()
    {
        return array_shift($this->exec_rules);
    }

    /**
     * 传递的额外参数
     * 
     * @param  [type] $key     [description]
     * @param  string $default [description]
     * @return [type]          [description]
     */
    protected function extra($key, $default=NULL)
    {
        return $this->extra[$key] ?? $default;
    }

    public static $instance;
}