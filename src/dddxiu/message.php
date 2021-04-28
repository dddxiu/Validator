<?php

namespace Dddxiu;

/**
 * 消息处理
 */
class Message extends Singleton
{

    protected function __construct($lang='zh_cn')
    {
        $this->lang = $lang;
        $this->load();
    }


    /**
     * 加载语言文件
     * @param  string $path 文件路径
     * @return [type]       [description]
     */
    protected function load($path='')
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
    protected function register($key='', $msg='error')
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
    protected function format($t='', $p=[], $c=[])
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
}