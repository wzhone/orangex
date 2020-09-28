<?php
namespace core\http;

use \core\leader\http\Uri;
use \core\leader\ServiceInfo;

class Request implements \core\leader\http\Request {
    
    private $server = [];
    private Uri $uri;
    private array $inputdata = [];
    private array $headers = [];
    private ServiceInfo $serviceinfo;

    public function init(array $server) : void{
        $this->server = $server;
        $this->uri = app()->make(Uri::class,[$server]);
        $this->inputdata = array_merge($_GET,$_POST);

        foreach ($server as $key => $value) {
            if ('HTTP_' == substr($key, 0, 5)) {
                $this->headers[str_replace('_', '-', substr($key, 5))] = $value;
            }
        }
    }

    public function input(string $name,string $default = null){
        return $this->inputdata[$name] ?? $default;
    }

    public function has(...$name) : bool{
        if (is_array($name)){
            foreach($name as $v){
                if (!array_key_exists($v, $this->inputdata))
                    return false;
            }
            return true;
        }else{
            return array_key_exists($name, $this->inputdata);
        }
    }

    public function header(string $name,string $default = null) : string{
        return $this->headers[strtoupper($name)] ?? $default;
    }

    public function method() : string{
        return strtolower($this->server['REQUEST_METHOD'] ?? 'get');
    }

    public function domain() : string{
        return strtolower($this->server['HTTP_HOST'] ?? '');
    }

    public function uri() : Uri{
        return $this->uri;
    }

    public function serviceInfo() : ServiceInfo{
        return $this->serviceinfo;
    }

    public function setServiceInfo(ServiceInfo $info) : void{
        $this->serviceinfo = $info;
    }
}