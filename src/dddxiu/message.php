<?php

namespace Dddxiu;

/**
 * 消息处理
 */
class Message
{

    public function __construct($lang='zh_cn')
    {
        $this->lang = $lang;
        $this->load();
    }


    /**
     * 加载语言文件
     * @param  string $path 文件路径
     * @return [type]       [description]
     */
    public function load($path='')
    {
        if (empty($path)) {
            $path = dirname(__DIR__)."/lang/{$this->lang}.php";
        }
        $this->msgs = include_once $path;
    }


    /**
     * 校对器注册错误提示
     * @param  string $key [description]
     * @param  string $msg [description]
     * @return [type]      [description]
     */
    private function register($key='', $msg='error')
    {
        $this->msgs[$key] = $msg;
    }


    /**
     * 格式化错误
     * name => r|w
     *     1. name.r
     *     2. name.w
     * @param  string $t 错误类型 
     * @param  string $p 参数[$field, [$min, $max]]
     * @param  string $c 自定义消息
     * @return array
     */
    private function format($t='', $p=[], $c=[])
    {
        if (array_key_exists($t, $c)) {
            $msg = $c[$t];
        } else {
            $msg = $this->msgs[$t];
        }

        $msg = str_replace(':field', $p[0], $msg);
        switch (count($p[1])) {
            case 1:
                $msg = str_replace(':val', $p[1][0], $msg);
                break;
            case 2:
                $msg = str_replace(':min', $p[1][0], $msg);
                $msg = str_replace(':max', $p[1][1], $msg);
                break;
        }
        return $msg;
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


    public function __call($method_name, $args)
    {
        if (method_exists($this, $method_name)) {
            return call_user_func_array([$this, $method_name], $args);
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