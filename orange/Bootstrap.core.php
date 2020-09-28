<?php
declare(strict_types = 1);
# 构建IOC 读取依赖
$app = new \core\Container();
$app->instance(\core\Container::class,$app);
$config = require BASEPATH.'orange/support/orange.config.php';
$dependency  = dotq($config,'dependency');
foreach($dependency as $depend){
    $alias = $depend[3] ?? null;
    $fun = $depend[2];
    $app->$fun($depend[0],$depend[1]);
    if ($alias !== null) $app->alias($alias,$depend[0]);
}

# register $app
$GLOBALS['core_app'] = $app;


$app->call([\core\leader\Kernel::class,'start']);