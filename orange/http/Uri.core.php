<?php
namespace core\http;

class Uri implements \core\leader\http\Uri {

    public function __construct(array $server){
        #request_url
        $this->request_url = $server['REQUEST_URI'] ?? '/';

        #query_string
        $this->query_string = $server['QUERY_STRING'] ?? '';

        #route_url
        $url = $server['REQUEST_URI'] ?? '/';  //只有apache支持REQUEST_URI
        $pos = strpos($url,'?');
        if ($pos != FALSE)
            $url = substr($url,0,$pos);
        if (strlen($url)>1)
            $url = rtrim($url,'/');
        $this->route_url = $url;

        #domain
        $domain = $server['HTTP_HOST'] ?? null;        
        if ($domain === null){
            $domain = $server['SERVER_NAME'] ?? $server['REMOTE_ADDR'];
        }
        $pos = strpos($domain,':');
        if ($pos != false)
            $domain = substr($domain,0,$pos);
        $this->req_domain = $domain;
    }


    public string $route_url;      //不含get的请求url
    public string $request_url;    //原生请求url
    public string $query_string;   //原生get参数
    public string $req_domain;      //请求的域名
    

    public function url() : string{
        return $this->route_url;
    }

    public function queryString() : string{
        return $this->query_string;
    }

    public function requestUrl() : string{
        return $this->request_url;
    }

    public function domain() : string{
        return $this->req_domain;
    }

}