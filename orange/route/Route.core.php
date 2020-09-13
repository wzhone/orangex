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
        
        foreach($this->routes as $service => $datas){
            $info = $datas['info'] ?? [];
            foreach($datas['routes'] as $data){
                $urlprefix = $info['prefix'] ?? '';
                $param = [];
                if (!$this->matchurl($urlprefix.$data['url'],$facturl,$param)){
                    continue;
                }
                if (!$this->matchmethod($data['method'],$method)){
                    continue;
                }
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
            $file= APP."$s/route.php";
            $this->routes[$s] = require $file;
        }
    }

    private function findservices(){
        $services=scandir(APP);
        foreach($services as $s){
            $file=APP.$s;
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

        //检测是不是带参url
        if (strpos($configurl,'{')===false){
            //非带参url，直接匹配就好
            if ($configurl == $facturl)
                return true;
            else
                return false; 
        }else{
            //根据参数进行匹配
            $conurl = preg_quote($configurl,'/');
            $conurl = preg_replace("/\\\{[A-Za-z0-9_]+\\\}/i","([^\/]+)",$conurl);
            $matches = [];
            //echo $conurl . " ".$facturl;
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
   
}