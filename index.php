<?php
define("DEBUG",true);
$GLOBALS['_beginTime'] = microtime(TRUE);
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if (MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();
define("BASEPATH",dirname(__FILE__)."/");
require_once('./orange/AutoLoader.core.php');
require_once('./orange/Functions.core.php');
require_once('./orange/Container.core.php');
require_once('./orange/Bootstrap.core.php');