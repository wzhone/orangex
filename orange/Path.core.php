<?php
namespace core;

class Path implements \core\leader\Path{

    const core = BASEPATH."orange/";
    const common = BASEPATH."common/";
    const runtime = self::common."runtime/";
    const config = self::common."config/";
    const app = BASEPATH."app/";
    const cache = self::common."cache/";
    const support = self::core."support/";
    const publicpath = BASEPATH."public/";


    public function join(...$path) : string {
        if (count($path) == 0) return "";
        if (count($path) == 1) return $path[0];
    
        $pathret = $path[0];
        if ($pathret[strlen($pathret)-1] == '/')
            trimLastChar($pathret,'/');
    
        for ($i=1;$i<count($path);$i++){
            $str = $path[$i];
            if ($str == "") continue;
            if ($str == null) continue;
    
            # 每个拼接上的字符串都是前有 '/' 而后没有
            if ($str[strlen($str)-1] == '/')
                trimLastChar($str,'/');
            if ($str[0] != '/')
                $str = "/$str";
    
            $pathret .= $str;
        }
        return $pathret;
    }

    public function core_path(...$path) : string{
        return $this->join(self::core,...$path);
    }

    public function common_path(...$path) : string{
        return $this->join(self::common,...$path);
    }

    public function runtime_path(...$path) : string{
        return $this->join(self::runtime,...$path);
    }
    
    public function config_path(...$path) : string{
        return $this->join(self::config,...$path);
    }

    public function app_path(...$path) : string{
        return $this->join(self::app,...$path);
    }

    public function cache_path(...$path) : string{
        return $this->join(self::cache,...$path);
    }

    public function support_path(...$path) : string{
        return $this->join(self::support,...$path);
    }

    public function public_path(...$path) : string{
        return $this->join(self::$publicpath,...$path);
    }

}



