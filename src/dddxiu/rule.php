<?php

namespace Dddxiu;

/**
 * 规则处理
 */
class Rule
{
    static private $instance;

    public function __construct()
    {
        $this->load();
    }

    /**
     * 加载本地的规则
     */
    public function load()
    {
        $this->rules = [];
    }

    /**
     * 自定义校对器
     * 覆盖本地校对器的问题
     * @param  [type] $rule     [description]
     * @param  [type] $callable [description]
     * @return [type]           [description]
     */
    private function register($rule, $callable)
    {
        if (is_string($rule) && is_callable($callable)) {
            return $this->rules[$rule] = $callable;
        }
        throw new \Exception("class:{$rule} method:{$callable} args error", 1);
    }

    /**
     * 分组规则
     * @param  [type] $rules [description]
     * @return [type]        [description]
     */
    private function explode($rules)
    {
        if (is_string($rules)) {
            $rule_arr = [];
            foreach (explode('|', $rules) as $rule) {
                $rule_arr[] = $this->parse_rule($rule);
            }
            return $rule_arr;
        }

        if (is_callable($rules)) {
            return [$rules];
        }

        return false;
    }

    /**
     * 解析规则
     * @param  [type] $rule_str [description]
     * @return [type]           [description]
     */
    private function parse_rule($rule_str)
    {
        list($r, $p) = $this->separate($rule_str);
        if ($r === false) {
            return false;
        }

        $args= [];
        $ret = false;
        if (strlen($r) === 1) {
            switch ($r) {
                // required
                case 'r': $ret= $this->_r($p); break;
                // nullable(默认值)
                case 'n': $ret= $this->_n($p); break;
                // string,长度 
                // 解析参数 10,[],(),[),(]
                case 's':
                    list($sub_r, $ret, $args) = $this->_s($p);
                    $r = "{$r}{$sub_r}";
                    break;
                // digit,数字大小
                // 解析参数 10,[],(),[),(]
                case 'd':
                    list($sub_r, $ret, $args) = $this->_d($p);
                    $r = "{$r}{$sub_r}";
                    break;
                // [A-Za-z0-9_]
                // 解析参数 10,[],(),[),(]
                case 'w':
                    list($sub_r, $ret, $args) = $this->_w($p);
                    $r = "{$r}{$sub_r}";
                    break;
                // enum
                // 解析参数 e[2,4,6]
                case 'e':
                    list($sub_r, $ret, $args) = $this->_e($p);
                    break;
            }
            return [$r, $ret, $args];
        }

        switch($r) {
            case 'em': // 枚举多选 em[1,3,5]
                $ret = $this->_em($p);
                break;
            case 'mail': // 邮件
                $ret = $this->_mail($p);
                break;
            case 'date': // 日期
                $ret = $this->_date($p);
                break;
            case 'ts': // 时间戳
                $ret = $this->_ts($p);
                break;
            case 'url': // url
                $ret = $this->_url($p);
                break;
            case 'json': // json
                $ret = $this->_json($p);
                break;
            case 'ip': // ip
                $ret = $this->_ip($p);
                break;
            case 'regx': // 正则
                $ret = $this->_regx($p);
                break;
            case 'alpha': // 希腊字母
                $ret = $this->_alpha($p);
                break;
            case 'zh': // 汉字
                $ret = $this->_zh($p);
                break;
            case 'phoneCn': // 中国区电话号码
                $ret = $this->_phoneCn($p);
                break;
            default:
                // 用户定义解析
                if (array_key_exists($r, $this->rules)) {
                    $ret = $this->rules[$r];
                }
        }
        return [$r, $ret, $args];
    }

    private function separate($rule_str)
    {
        $l = strlen($rule_str);
        for ($i=0; $i < $l; $i++) {
            if (ctype_alpha($rule_str[$i])) {
                continue;
            }
            break;
        }
        $r = substr($rule_str, 0, $i);
        $p = false;
        switch ($rule_str[$i]??NULL) {
            case ':': $p = strstr($rule_str, ':'); break;
            case '[': $p = $this->bracket(substr($rule_str, $i)); break;
            case '(': $p = $this->bracket(substr($rule_str, $i)); break;
            case NULL: $p= false; break;
            default:
                // 约定格式 sn= 的格式s10,d
                if (is_numeric(substr($rule_str, $i))) {
                    $p = ['n', '', [(int)substr($rule_str, $i)]];
                }
            break;
            
        }
        return [$r, $p];
    }


    /**
     * 检查括号
     * [1,2,3,4,5],(1,4),[2,7),(3,8]
     * @param  string $str [description]
     * @return [type]      [description]
     */
    private function bracket(string $str)
    {
        // 检查第一个字母
        $first = $str[0] ?? false;
        if (!in_array($first, ['[','('])) {
            return false;
        }

        // 检查最后一个字母
        $last = $str[strlen($str)-1] ?? false;
        if (!in_array($last, [']',')'])) {
            return false;
        }

        // 分割参数
        return [
            $first,
            $last,
            explode(',', substr($str, 1, -1))
        ];
    }

    /**
     * 解析参数中的区间问题
     */
    private function _args_bracket($p, $t='str')
    {
        if ($t === 'enum') {
            $fun = function($input, $field) use ($p) {
                return in_array($input[$field], $p[2]);
            };
            return ['', $fun, NULL];

        }

        // 数字 大小
        if ($t === 'num') {
            $min = $p[2][0];
            $max = $p[2][1] ?? NULL;
        }

        // 字符 长度
        if ($t == 'str') {
            $min = intval($p[2][0]);
            $max = intval($p[2][1] ?? NULL);
        }

        // 区间
        $fun = [];
        $sub_r = "{$p[0]}{$p[1]}"; 
        switch ($sub_r) {
            case '[]':
                $fun = function($input, $field) use ($p, $t, $min, $max) {
                    $v = $input[$field] ?? NULL;
                    if ($t == 'str') {
                        $v = strlen($v);
                    }
                    return ($v>=$min && $v<=$max);
                };
                break;
            case '()':
                $fun = function($input, $field) use ($p, $t, $min, $max) {
                    $v = $input[$field] ?? NULL;
                    if ($t == 'str') {
                        $v = strlen($v);
                    }
                    return ($v>$min && $v<$max);
                };
                break;
            case '[)':
                $fun = function($input, $field) use ($p, $t, $min, $max) {
                    $v = $input[$field] ?? NULL;
                    if ($t == 'str') {
                        $v = strlen($v);
                    }
                    return ($v>=$min && $v<$max);
                };
                break;
            case '(]':
                $fun = function($input, $field) use ($p, $t, $min, $max) {
                    $v = $input[$field] ?? NULL;
                    if ($t == 'str') {
                        $v = strlen($v);
                    }
                    return ($v>$min && $v<=$max);
                };
                break;
            case 'n': // 固定长度
                $fun = function($input, $field) use ($p, $t, $min, $max) {
                    $v = $input[$field] ?? NULL;
                    if ($t == 'str') {
                        $v = strlen($v);
                    }
                    return ($v == $min);
                };
                break;
            default:
                $sub_r = '';
                break;
        }
        return [$sub_r, $fun, $p[2]];
    }

    /**
     * required
     */
    private function _r()
    {
        return function($input, $field){
            return array_key_exists($field, $input);
        };
    }

    /**
     * nullable
     */
    private function _n()
    {
        return true;
    }

    /**
     * [A-Za-z0-9_]
     */
    private function _w($p)
    {
        return $this->_args_bracket($p, 'str');
    }

    /**
     * string
     */
    private function _s($p)
    {
        return $this->_args_bracket($p, 'str');
    }

    /**
     * digit
     */
    private function _d($p)
    {
        return $this->_args_bracket($p, 'num');
    }

    /**
     * enum
     */
    private function _e($p)
    {
        return $this->_args_bracket($p, 'enum');
    }
    
    /**
     * 枚举多选
     */
    private function _em($p) 
    {
        return function($input, $field) use ($p){
            foreach (explode(',', $input[$field]) as $v) {
                if (!in_array($v, $p[2])) {
                    return false;
                }
            }
            return true;
        };
    }
    
    /**
     * 邮件
     */
    private function _mail($p) 
    {
        return function($input, $field){
            return filter_var($input[$field], FILTER_VALIDATE_EMAIL);
        };
    }

    /**
     * 日期
     */
    private function _date($p) 
    {
        return function($input, $field) use ($p){
            if (empty($p)) {
                return strtotime($input[$field])>0;
            }
            // 格式化 Y-m-d
            return (date($p, strtotime($input[$field])) === $input[$field]);
        };
    }

    /**
     * 时间戳
     */
    private function _ts($p)
    {
        return function($input, $field) use ($p){
            $v = $input[$field];
            if (empty($p)) {
                return $v>0;
            }
            // 1970+p年有效
            return ($v<3600*24*365*intval($p) && $v>0);
        };
    }

    /**
     * url
     */
    private function _url($p)
    {
        return function($input, $field){
            return filter_var($input[$field], FILTER_SANITIZE_URL);
        };
    }

    /**
     * json
     */
    private function _json($p)
    {
        return function($input, $field){
            return (json_decode($input[$field], true) !== NULL);
        };
    }

    /**
     * ip
     */
    private function _ip($p)
    {
        return function($input, $field){
            $pat = "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/";
            return (preg_match($pat, $input[$field]) === 1);
        };
    }

    /**
     * 正则
     */
    private function _regx($p)
    {
        return function($input, $field) use ($p){
            return (preg_match($p, $input[$field]) === 1);
        };
    }

    /**
     * 希腊字母
     */
    private function _alpha($p)
    {
        return function($input, $field){
            return ctype_alpha($input[$field]);
        };
    }

    /**
     * 汉字
     */
    private function _zh($p) 
    {
        return function($input, $field){
            return (preg_match('/^[\x4e00-\x9fa5]+$/', $input[$field]) === 1);
        };
    }

    /**
     * 中国手机号
     */
    private function _phoneCn($p)
    {
        return function($input, $field) {
            return (preg_match("/^1[3456789]\d{9}$/", $input[$field]) === 1);
        };
    }

    public static function __callStatic($method_name, $args)
    {
        $instance = static::getInstance();
        if (method_exists($instance, $method_name)) {
            return call_user_func_array([$instance, $method_name], $args);
        };
        throw new Exception("{$method_name} not exists", 1);
    }

    public function __call($method_name, $args)
    {
        if (method_exists($this, $method_name)) {
            return call_user_func_array([$this, $method_name], $args);
        };
        throw new Exception("{$method_name} not exists", 1);
    }

    /**
     * 单例
     */
    public static function getInstance()
    {
        if (NULL == self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}