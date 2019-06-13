<?php

namespace Dddxiu;

use Dddxiu\exception\ArgsException;
use Dddxiu\exception\RuleNotExistException;

/**
 * 漏斗
 */
class Funnel
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

    // 元素基础类型
    const FIELD_TYPE_STR  = 'str';
    const FIELD_TYPE_NUM  = 'num';
    const FIELD_TYPE_DATE = 'date';


    /**
     * 输入数据
     * 
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    private function input($input)
    {
        $this->input = $input;
    }


    /**
     * 规则
     * 
     * @param  [type] $rule [description]
     * @return [type]       [description]
     */
    private function rules($rules)
    {
        $this->valid_rules = $rules;
    }


    /**
     * 校对到最后
     * 
     * @param [type] $final [description]
     */
    private function set_final($final)
    {
        $this->final = $final;
    }


    /**
     * 执行过滤
     * 
     * @return [type] [description]
     */
    private function standing($input=NULL, $rules=NULL, $final=NULL)
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

        foreach ($this->valid_rules as $field => $rule_str) {
            $abort = $this->check_item($field, $rule_str);
            if ($abort && !$this->final) {
                return false;
            }
        }
        return true;
    }


    /**
     * 检查单个结果
     * 
     * @param  [type] $input     [description]
     * @param  [type] $field     [description]
     * @param  [type] $rule_item [description]
     * @return [type]            [description]
     */
    private function check_item($field, $rule_item)
    {
        if (is_string($rule_item)) {
            return $this->rule_item_str($field, $rule_item);
        }

        if (is_array($rule_item) && is_callable($rule_item[0])) {
            throw new Exception("回调还没有实现", 1);
            return true;
        }

        if (is_callable($rule_item)) {
            throw new Exception("回调还没处理", 1);
            return true;
        }

        throw new ArgsException("Error:".__METHOD__, 1);
    }

    /**
     * rule_item 的str规则
     * 
     * @param  string $field     检查的字段
     * @param  string $rule_item 要检查的规则列表
     * @return [type]            [description]
     */
    private function rule_item_str($field='', $rule_item='')
    {
        $rules    = [];
        $nullable = true;
        foreach (explode('|', $rule_item) as $flag) {
            
            if ($flag == 'r') {
                $rules[]  = ['r', NULL];
                $nullable = false;
                continue;
            }

            if ($flag == 'n') {
                continue;
            }
            
            $args = NULL;
            if (strpos($flag, ':')) {
                $args = explode(',', substr(strstr($flag, ':'), 1));
                $flag = strstr($flag, ':', true);
            }
            $rules[] = [$flag, $args];
        }

        if ($nullable) {
            $rules[] = ['n', NULL];
        }

        return $this->check_field_rule($field, $rules);
    }


    /**
     * 校对单个字段
     * 
     * @param  [type] $field [description]
     * @param  [type] $rules [description]
     * @return [type]        [description]
     */
    private function check_field_rule($field, $rules)
    {
        // 1.对应规则
        $temp_urls = [];
        foreach ($rules as $rule_info) {
            list($flag, $args) = $rule_info;

            if (!array_key_exists($flag, $this->default_rules)) {
                throw new RuleNotExistException("rule '{$flag}' not exists", 1);
            }

            $temp_urls[] = [$flag, $args, $this->default_rules[$flag]];
        }

        // 2.规则排序
        usort($temp_urls, function($r1, $r2)
        {
            if (is_string($r1[2]) && is_string($r2[2])) {
                return ($r1[2]::S - $r2[2]::S);
            }

            if (is_string($r1[2])) {
                $r1 = $r1[2]::S;
                $r2 = 6;
            }

            if (is_string($r2[2])) {
                $r2 = $r2[2]::S;
                $r1 = 6;
            }
            return $r1 - $r2;
        });

        // 3.处理
        $abort= false;
        $prev = [];
        foreach ($temp_urls as $rule_info) {
            $next = $this->final;
            list($flag, $args, $exec) = $rule_info;

            if (is_a($exec, 'Closure')) {
                $ret = $exec($this->input, $field, $args, $next, $prev);
            } else {
                $ret = $exec::valid($this->input, $field, $args, $next, $prev);
            }

            if ($ret !== true) {
                $value = $this->input[$field] ?? NULL;
                $this->error_record($field, $value, $flag, $args);
            }

            // 是否继续
            if ($next !== true) {
                $abort = true;
                break;
            }
        }

        return $abort;
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
    private function error_record($field, $value, $rule, $rule_value)
    {
        $this->errors[$field][$rule] = [$value, $rule_value];
    }


    /**
     * 错误结果
     * @return [type] [description]
     */
    private function get_errors()
    {
        return $this->errors;
    }


    static private $instance;


    public static function __callStatic($name, $args)
    {
        $instance = static::getInstance();
        if (method_exists($instance, $name)) {
            return call_user_func_array([$instance, $name], $args);
        };
        throw new ArgsException("{$name} not exists", 1);
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
        if (self::$instance === NULL) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    public function __construct()
    {
        $this->load_rules();
    }


    /**
     * 加载本地的规则
     */
    private function load_rules()
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
     * 注册调用链
     * 
     * @param  string   $f    标志
     * @param  callable $call 校对器
     * @return [type]         [description]
     */
    private function register(string $f, $call)
    {
        $this->default_rules[$f] = $call;
    }
}