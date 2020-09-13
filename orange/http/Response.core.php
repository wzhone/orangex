<?php
namespace core\http;

use \core\leader\http\Uri;

class Response implements \core\leader\http\Response {
    

    public function init() : void{
        ob_start();
    }

    private array $headers = [
        'X-Powered-By'=>'OrangeX'
    ];
    private int $code = 200;
    

    public function header($name,string $value = null) : void{
        if (is_array($name))
            $this->headers=array_merge($this->headers,$name);
        else
            $this->headers[$name]=$value;
    }

    public function status(int $status) : void{
        $this->code = $status;
    }

    public function redirect(string $url) : void{
        $this->header('Location',$url);
    }

    public function response() : void{
        header('HTTP/1.1 '.$this->code);

        foreach ($this->headers as $key => $value){
            header($key. ':' .$value);
        }
    }

}