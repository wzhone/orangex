<?php
namespace core;
class AutoLoader{

    public static function reg(){
        spl_autoload_register('core\AutoLoader::classloader');
    }

    public static function classloader(string $class){
        $class=explode('\\',$class);
        if (count($class)<2){
            return;
        }
        $classname =  array_pop($class);
        $pathtype = $class[0];

        switch($pathtype){
        case 'core':
            array_shift($class);
            require_once(BASEPATH."orange/".implode('/',$class).'/'.$classname.'.core.php');
        break;
        default:
            require_once(BASEPATH.implode('/',$class).'/'.$classname.'.class.php');
        }
    }
}

\core\AutoLoader::reg();