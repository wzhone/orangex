<?php
namespace core\leader;

use \core\Container;

interface Liquidator{


    /**
     * 注册回调事件
     * @param mixed          $fun   可被IOC调用的类型
     * @param array          $parm  调用时传递的参数
     * @return void
     */
    public function registerEvent($fun,array $parm = []) : void;


    /**
     * 调用回调事件
     * @param Container          $app  IOC容器
     * @return void
     */
    public function callEvent(Container $app) : void;
    

}