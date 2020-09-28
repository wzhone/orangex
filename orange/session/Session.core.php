<?php
namespace core\session;

use \core\leader\config\Config;
use \core\leader\session\SessionStorage;
use \core\leader\session\SessionFlash;

class Session implements \core\leader\session\Session{

    private SessionStorage $drive;
    private array $data;

    public function init() : void{
        $config = app()->make(Config::class);

        $this->drive = app()->make(SessionStorage::class);
        $this->drive->init($config->get('core.session.data'));
        $this->data = $this->drive->get();
    }
    
    public function save() : void{
        $this->drive->save($this->data);
    }

    public function put(string $key,$value) : void{
        $this->data[$key] = $value;
    }

    public function flash(string $key,$value) : void{
        $this->data[$key] = new SessionFlash($value);
    }
    
    public function get(string $key,$default = null){
        $value = $this->data[$key] ?? $default;
        if ($value instanceof SessionFlash){
            unset($this->data[$key]);
            return $value->value;
        }else{
            return $value;
        }
    }
    
    public function pull(string $key,$default = null){
        $value = $this->get($key,$default);
        unset($this->data[$key]);
        return $value;
    }
    
    public function has(string $key) : bool{
        return array_key_exists($key,$this->data);
    }

    public function all() : array{
        return $this->data;
    }
    
    public function delete() : void{
        $this->data = [];
    }

}