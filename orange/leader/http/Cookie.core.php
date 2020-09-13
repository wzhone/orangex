<?php
namespace core\leader\http;

interface Cookie{
    public function init();
    

    /**
     * 设置一个cookie值，注意:下一会话才可读取
     * @param string          $key     键名
     * @param string          $value   值
     * @param mixed           $other   其他参数
     * @return void
     */
    public function put(string $key,string $value,...$other) : void;

    /**
     * 获取并删除一个cookie值
     * @param string          $key     键名
     * @param string          $default 默认值
     * @return string
     */
    public function pull(string $key,string $default = null) : string;

    /**
     * 删除所有cookie值
     * @return void
     */
    public function delete() : void;

    /**
     * 获取某一个cookie值
     * @param string          $key     键名
     * @param string          $default 默认值
     * @return string
     */
    public function get(string $key,string $default = null) : string;
        
    /**
     * 判断某一个cookie值是否存在
     * @param string          $key     键名
     * @return bool
     */
    public function has(string $key) : bool;

    /**
     * 获取所有cookie数据
     * @return array
     */
    public function all() : array;

    /**
     * 将请求发送到客户端
     * @return void
     */
    public function save() : void;
    
}