<?php
namespace core\middleware;

use \core\Container;
use \core\leader\config\Config;
use \core\leader\http\Request;
use \core\leader\ServiceInfo;


class Middleware implements \core\leader\middleware\Middleware{

    private array $middlegroup = [];
    private $globalmiddle = [];
    private ServiceInfo $info;

    private array $middlechain = [];

    public function init(Request $request , Config $config) : void{
        $this->middlegroup = $config->get('core.middlewareGroup',[]);
        $this->globalmiddle = $config->get('core.globalMiddleware',[]);

        $this->info = $request->serviceInfo();

        //解析全局中间件
        if (!is_array($this->globalmiddle)){
            $this->globalmiddle = [$this->globalmiddle];
        }
        foreach ($this->globalmiddle as $middle){
            $this->middlechain = array_merge(
                    $this->middlechain,
                    $this->resolve($middle)
            );
        }

        //解析用户路由中间件
        $appmiddle = $this->info->middleware();
        if ($appmiddle != null){
            if (!is_array($appmiddle)){
                $appmiddle = [$appmiddle];
            }
            foreach ($appmiddle as $middle){
                $this->middlechain = array_merge(
                    $this->middlechain,
                    $this->resolve($middle)
                );
            }
        }
        return;
    }

    private array $middleinst = [];

    public function pre(Container $app) : bool{
        foreach ($this->middlechain as $middle){
            $inst = new $middle();
            if (!$app->call([$inst,'preHandle'])){
                return false;
            }
            $this->middleinst[] = $inst;
        }
        return true;
    }

    public function post(Container $app) : bool{
        $this->middleinst = array_reverse($this->middleinst);
        
        foreach ($this->middleinst as $middle){
            if (!$app->call([$middle,'postHandle'])){
                return false;
            }
        }
        return true;
    }


    #将中间组转换为中间件
    private function resolve($middleware){
        return $this->middlegroup[$middleware] ?? [$middleware];
    }

}