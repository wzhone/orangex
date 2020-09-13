<?php
declare(strict_types = 1);

$app = new \core\Container();
$app->instance(\core\Container::class,$app);
$config = require SUPPORT.'orange.config.php';
$dependency  = dotq($config,'dependency');
foreach($dependency as $depend){
    $alias = $depend[3] ?? null;
    $fun = $depend[2];
    $app->$fun($depend[0],$depend[1]);
    if ($alias !== null) $app->alias($alias,$depend[0]);
}

# register $app
app($app);



$app->call([\core\leader\Kernel::class,'start']);

