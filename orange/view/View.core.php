<?php
namespace core\view;

use \core\Container;
use \core\leader\config\Config;
use \core\leader\http\Request;
use \core\leader\http\Response;

class View implements \core\leader\view\View{

    private array $data = [];
    private array $global = [];

    #Config类的引用
    private Config $config;
    private Request $request;

    private string $view = '';

    public function __construct(Config $config,Request $request){
        $this->config = $config;
        $this->request = $request;
    }

    private function initGlobal(){
        $this->global = $this->config->get('core.viewglobal',[]);
        
        #初始化魔术常量
        $this->global = \array_merge($this->global,[
            "page"=>$this->request->uri()->url(),  //去除get的url
            "request"=>$this->request->uri()->requestUrl() //请求的url
        ]);
    }

    public function bind($param,$value = null) : void{
        if (is_array($param)){
            $this->data = array_merge($this->data,$param);
        }else{
            $this->data[$param] = $value;
        }
    }

    public function errorPage(string $status) : void{
        $path = config()->get('core.errorpagepath',null);
        if ($path != null){
            $f = pathjoin($path,"/$status.html");
            if (file_exists($f)){
                $this->complicate($f,[]);
                return;
            }
        }
        $file = pathjoin(SUPPORT,'errorpage/',"$status.html");
        if (file_exists($file)){
            $this->complicate($file,[]);
        }else{
            throw new \Exception("There is no error page with error code $status");
        }
    }

    public function analysis($s) : void{
        if (is_array($s)){
            $this->view = json_encode($s,JSON_UNESCAPED_UNICODE); 
        }elseif (is_numeric($s)){
            $this->errorPage($s);
        }elseif(is_string($s)){
            $this->display($s);
        }elseif(is_object($s)){
            $this->view = json_encode($object，JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE );
        }else{
            //no deal
        }
    }

    public function display(string $file,array $param = []) : void{
        $basepath = pathjoin(serviceInfo()->getBasePath(),config("core.viewpath","view/"));
        $file = str_replace('.','/',$file);
        $this->complicate(pathjoin($basepath,$file.'.html'),$param);
    }

    private function complicate(string $file,array $param = []){
        $param = array_merge($this->data,$param);
        $file = file_get_contents($file);
        $this->initGlobal();
        $file = $this->replaceGlobal($file,$this->global);
        $file = $this->replaceVar($file,$param);
        $this->view = $file;
    }

    private function replaceVar(string $content,array $vardata){
        $replace_key = [];
        $replace_value = [];
        foreach($vardata as $key=>$var ){
            $replace_key[] = "${$key}";
            $replace_value[] = $var;
        }
        return str_replace($replace_key,$replace_value,$content);
    }

    private function replaceGlobal(string $content,array $vardata){
        $replace_key = [];
        $replace_value = [];
        foreach($vardata as $key=>$var ){
            $replace_key[] = '__' . $key . '__';
            $replace_value[] = $var;
        }
        return str_replace($replace_key,$replace_value,$content);
    }

    public function output() : void{
        echo $this->view;
    }
}