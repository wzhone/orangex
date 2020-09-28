<?php
namespace core;


class ServiceInfo implements \core\leader\ServiceInfo{
    
    //匹配到的服务的服务名
    private string $servicename;

    //匹配到的路由项信息
    private array  $routeitemdata;

    //匹配到的url参数
    private array  $urlparam;

    //带前缀的的路由url
    private string $wholerouteurl;

    public function __construct(
        string $servicename,
        array $urlparam,
        string $wholerouteurl,
        array $routeitemdata
    ){
        $this->servicename = $servicename;
        $this->urlparam = $urlparam;
        $this->wholerouteurl = $wholerouteurl;
        $this->routeitemdata = $routeitemdata;
    }

    public function name() : string{
        return $this->servicename;
    }
    public function urlparam() : array{
        return $this->urlparam;
    }

    public function __call(string $name,$notuse){
        return $this->routeitemdata[$name] ?? null;
    }

    public function getAppPath() : string{
        return app_path($this->servicename);
    }

}