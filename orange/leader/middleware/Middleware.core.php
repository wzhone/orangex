<?php
namespace core\leader\middleware;

use \core\Container;
use \core\leader\config\Config;
use \core\leader\http\Request;

interface Middleware{


    /**
     * 初始化中间件模块
     * @param Request      $request    HTTP请求类
     * @param Config       $config     配置文件类
     * @return bool
     */
    public function init(Request $request , Config $config) : void;


    /**
     * 调用的所有前置中间件
     * @param Container       $app     IOC容器
     * @return bool
     */
    public function pre(Container $app) : bool;


    /**
     * 调用的所有后置中间件
     * @param Container       $app     IOC容器
     * @return bool
     */
    public function post(Container $app) : bool;

}