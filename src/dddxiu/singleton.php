<?php

namespace Dddxiu;

use Dddxiu\exception\ArgsException;

/**
 * 单例
 */
class Singleton
{
    // 继承单例
    public static $instance;

    /**
     * 限制引用传值
     * 
     * @param  [type] $method [description]
     * @param  [type] $args   [description]
     * @return [type]         [description]
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getInstance();
        if (method_exists($instance, $method)) {
            return call_user_func_array([$instance, $method], $args);
        };
        throw new ArgsException("{$method} not exists", 1);
    }


    /**
     * 限制了使用引用传值
     * 
     * @param  [type] $method [description]
     * @param  [type] $args   [description]
     * @return [type]         [description]
     */
    public function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        };
        throw new ArgsException("{$method} not exists", 1);
    }

    
    /**
     * 类静态常量保持单例
     * 意味着只能静态调用改方法
     * @return object 调用class
     */
    public static function getInstance()
    {
        $cls = get_called_class();
        $ins = $cls::$instance[$cls] ?? NULL;

        if ($ins == NULL) {
            $ins = new static();
            $cls::$instance[$cls] = $ins;
        }
        return $ins;
    }
}