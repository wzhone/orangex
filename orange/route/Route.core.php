<?php
namespace core\route;

use \core\leader\http\Request;
use \core\leader\ServiceInfo;

class Route implements \core\leader\route\Route{

    //存放服务名
    private array $services;

    //服务名对应的路由配置
    private array $routes;

    public function __construct(){
        //查找app目录下的所有合法目录
        $this->findservices();

        //读取服务的路由配置文件
        $this->analysisservices();

        return true;
    }

    public function match(string $facturl,string $method,Request $request){
        
        foreach($this->routes as $service => $datas){ #遍历每个服务的路由文件
            $info = $datas['info'] ?? [];

            # 域名匹配
            $domain = $info["domain"] ?? null;
            if ($domain != null){
                if (is_array($domain))
                    foreach ($domain as $value){
                        if (!$this->matchDomain($value,$request->domain())) 
                            continue 2;
                }else{
                    if (!$this->matchDomain($domain,$request->domain())) continue;
                }
            }

            # 前缀处理
            $urlprefix = $info['prefix'] ?? '';
            if ($urlprefix != ''){
                if ($urlprefix == "/") $urlprefix = "";
                if ($urlprefix[0] != '/')
                    $urlprefix = '/' . $urlprefix;
                if ($urlprefix[strlen($urlprefix)-1] == '/')
                    trimLastChar($urlprefix,'/');
            }

            foreach($datas['routes'] as $data){ # 遍历路由文件的每条记录
                $param = [];

                # 匹配方法
                if (!$this->matchmethod($data['method'],$method)){
                    continue;
                }

                # 匹配Url
                if (is_array($data['url'])){
                    $murl = null;
                    foreach ($data['url'] as $url){
                        if (!$this->matchurl($urlprefix.$url,$facturl,$param)){
                            continue; //匹配此条url失败
                        }else{
                            $murl = $url; //保存匹配的Url
                            break;
                        }
                    }
                    if ($murl != null)
                        $data['url'] = $murl;
                    else
                        continue ;//匹配失败
                }else{
                    if (!$this->matchurl($urlprefix.$data['url'],$facturl,$param)){
                        continue;//匹配失败
                    }
                }
                
                # 初始化匹配的服务
                $info = app()->make(ServiceInfo::class,
                    [$service,$param,$urlprefix.$data['url'],$data]
                );
                return $info;
            }
        }
        return null;
    }


    private function analysisservices(){
        foreach($this->services as $s){
            $file= app_path($s,"/route.php");
            $this->routes[$s] = require $file;
        }
    }

    private function findservices(){
        $services=scandir(app_path());
        foreach($services as $s){
            $file=app_path($s);
            if(is_dir($file)){
                if($file=='.' || $file=='..') continue;
                if (file_exists($file.'/route.php')){
                    $this->services[] = $s;
                }
            }
        }
    }

    private function matchmethod($configmethod,$factmethod){
        $configmethod = strtolower($configmethod);
        $factmethod = strtolower($factmethod);

        if ($configmethod == 'all')return true;
        if ($configmethod == $factmethod)return true;
        return false;
    }

    private function matchurl($configurl,$facturl,&$param){

        $param = [];
        //去除最后面可能携带的 / 
        trimlastchar($configurl,'/');
        trimlastchar($facturl,'/');

        //检测是不是带参url或者通配符url
        if (strpos($configurl,'{')===false &&
            strpos($configurl,'*')===false
            ){
            //非带参url，直接匹配就好
            if ($configurl == $facturl)
                return true;
            else
                return false; 
        }else{
            //根据参数进行匹配
            $conurl = preg_quote($configurl,'/');
            $conurl = str_replace("\*","[^\/]+",$conurl);
            $conurl = preg_replace("/\\\{[A-Za-z0-9_]+\\\}/i","([^\/]+)",$conurl);
            $matches = [];
            if (0==preg_match('/^'.$conurl.'$/i',$facturl,$matches)){
                return false;
            }else{
                //提取参数名
                array_shift($matches);
                $paramname = [];
                preg_match('/^'.$conurl.'$/i',$configurl,$paramname);
                array_shift($paramname);
                for($i=0;$i<count($paramname);$i++){
                    //去除匹配出来的 { }
                    $paramname[$i] = str_replace(['{','}'],'',$paramname[$i]);
                }
                //html转义
                for($i=0;$i<count($matches);$i++){
                    $matches[$i] = urldecode($matches[$i]);
                }
                $param = array_combine($paramname,$matches);
                
                return true;
            }
        }
    }

    private function matchDomain($configdo,$factdo){
        $configdo = explode(".",$configdo);
        $factdo = explode(".",$factdo);

        if (count($configdo) != count($factdo))
            return false;

        for($i =0 ;$i<count($configdo);$i++){
            if ($configdo[$i] == "*")
                continue;
            if ($configdo[$i] == $factdo[$i])
                continue;
            return false;
        }
        return true;
    }
   
}