<?php
namespace core\leader\http;

interface Response{

    public function init() : void;
    public function response() : void;

    /**
     * 设置HTTP响应头
     * @param mixed          $name     请求头，可以为字符串或一个数组
     * @param string         $value    值，当name为数组时则留空
     * @return void
     */
    public function header($name,string $value = null) : void;
    

    /**
     * 设置HTTP响应码
     * @param int          $status     HTTP响应码
     * @return void
     */
    public function status(int $status) : void;


    /**
     * 发送一个http重定向请求
     * @param int          $status     HTTP响应码
     * @return void
     */
    public function redirect(string $url) : void;
        
    
    

}