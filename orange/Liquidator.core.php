<?php
namespace core;

use \core\Container;

class Liquidator implements \core\leader\Liquidator{

    private array $callback = [];

    public function registerEvent($fun,array $parm = []) : void{
        $this->callback[] = [$fun,$parm];
    }


    public function callEvent(Container $app) : void{
        foreach ($this->callback as $cb){
            $app->call($cb[0],$cb[1]);
        }
    }

}