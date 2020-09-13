<?php
namespace core\http;

class Cookie implements \core\leader\http\Cookie {

    private array $cookies = [];
    private array $updateQueue = [];

    public function init(){
        $this->cookies = $_COOKIE;
    }

    public function put(string $key,string $value,...$other) : void{
        $this->updateQueue[] = array_merge([$key,$value],$other);
    }

    public function pull(string $key,string $default = null) : string{
        $v = $this->get($key,$default);
        $this->updateQueue[$key]=[$key,'',time()-3600*24];
        unset($this->cookies[$key]);
        return $v;
    }

    public function delete() : void{
        foreach($this->cookies as $key=>$value){
            $this->updateQueue[]=[
                $key,'',time()-3600*24
            ];
        }
        $this->cookies = [];
    }

    public function get(string $key,string $default = null) : string{
        return $this->cookies[$key] ?? $default;
    }
        
    public function has(string $key):bool{
        return array_key_exists($key,$this->cookies);
    }

    public function all() : array{
        return $this->cookies;
    }

    public function save() : void{
        foreach($this->updateQueue as $data){
            if (!isset($data[2])) $data[2] = '0';
            if (!isset($data[3])) $data[3] = '/';
            call_user_func_array('setcookie',$data);
        }
    }

}