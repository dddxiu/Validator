<?php

namespace Dddxiu;

use Dddxiu\exception\ArgsException;
use Dddxiu\exception\RuleNotExistException;

/**
 * 漏斗
 */
class Funnel extends Singleton
{

    // 输入数据
    private $input = [];

    // 默认的规则列表
    private $default_rules = [];

    // 需要检查的
    private $valid_rules = [];

    // 错误列表
    private $errors= [];

    // 规则全解析
    private $final = false;


    /**
     * load本地规则
     */
    protected function __construct()
    {
        $this->load_rules();
    }


    /**
     * 加载本地的规则
     */
    protected function load_rules()
    {
        $parent = __NAMESPACE__.'\\rules\\Rule';
        $base   = __DIR__.DIRECTORY_SEPARATOR.'rules';
        $handle = opendir($base);
        while (false !== ($file = readdir($handle))) {
            if (strpos($file, '.php') === false) {
                continue;
            }

            $call  = strstr($file, '.php', true);
            $class = __NAMESPACE__.'\\rules\\'.$call;
            if (get_parent_class($class) === $parent) {
                $this->register($class::F, $class);
            }
        }
        closedir($handle);
    }

    
    /**
     * 注册自定义规则
     * 
     * @param  string   $f    标志
     * @param  callable $call 校对器
     * @return [type]         [description]
     */
    protected function register(string $f, $call)
    {
        $this->default_rules[$f] = $call;
    }


    /**
     * 输入数据
     * 
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    protected function input($input)
    {
        $this->input = $input;
    }


    /**
     * 规则
     * 
     * @param  [type] $rule [description]
     * @return [type]       [description]
     */
    protected function rules($rules)
    {
        $this->valid_rules = $rules;
    }


    /**
     * 校对到最后
     * 
     * @param [type] $final [description]
     */
    protected function set_final($final)
    {
        $this->final = $final;
    }


    /**
     * 执行过滤
     * 
     * @return [type] [description]
     */
    protected function standing($input=NULL, $rules=NULL, $final=NULL)
    {
        if ($input !== NULL) {
            $this->input = $input;
        }

        if ($rules !== NULL) {
            $this->valid_rules = $rules;
        }

        if (empty($this->input)) {
            return false;
        }

        if (empty($this->valid_rules)) {
            return true;
        }

        if ($final !== NULL) {
            $this->final = $final;
        }

        Layer::meta($this->input, $this->default_rules, $this, $this->final);
        foreach ($this->valid_rules as $field => $exps) {
            $abort = Layer::filter($field, $exps);
            if ($abort && !$this->final) {
                return false;
            }
        }
        return true;
    }



    /**
     * 记录错误
     * 
     * @param  [type] $field      [description]
     * @param  [type] $value      [description]
     * @param  [type] $rule       [description]
     * @param  [type] $rule_value [description]
     * @return [type]             [description]
     */
    protected function error_record($field, $value, $rule, $rule_value)
    {
        $this->errors[$field][$rule] = [$value, $rule_value];
    }


    /**
     * 错误结果
     * @return [type] [description]
     */
    protected function get_errors()
    {
        return $this->errors;
    }

}