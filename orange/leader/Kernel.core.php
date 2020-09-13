<?php
namespace core\leader;
use \core\Container;
use \core\leader\config\Config;

interface Kernel{

    /**
     * 框架核心启动入口
     * @return void
     */
    public function start() : void;

    
}