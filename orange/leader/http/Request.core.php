<?php
namespace core\leader\http;

use \core\leader\http\Uri;
use \core\leader\ServiceInfo;

interface Request{

    public function init(array $server) : void;

    /**
     * 获取http输入的数据
     * @param string          $name     键名
     * @param string          $default  默认值
     * @return string
     */
    public function input(string $name,string $default = null) : string;


    /**
     * 判断值是否存在
     * @param string          $name     键名或多个键名
     * @return bool
     */
    public function has(...$name) : bool;


    /**
     * 获取Http请求头
     * @param string          $name     请求头名
     * @param string          $default  默认值
     * @return string
     */
    public function header(string $name,string $default = null) : string;


    /**
     * 获取当前请求的方法，返回一个小写的字符串
     * @return string
     */
    public function method() : string;


    /**
     * 获取当前请求的域名，返回一个全小写的字符串
     * @return string
     */
    public function domain() : string;
    
    /**
     * 获取Uri类
     * @return Uri
     */
    public function uri() : Uri;


    /**
     * 获取成功匹配的路由信息，也就是ServiceInfo类
     * @return ServiceInfo
     */
    public function serviceInfo() : ServiceInfo;


    /**
     * 设置匹配的路由信息
     * @param ServiceInfo      $info  路由信息类
     * @return void
     */
    public function setServiceInfo(ServiceInfo $info) : void;

}