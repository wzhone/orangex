<?php
use \core\Container;


/**
 * 生成指定范围随机数
 * @param int          $max    最大值
 * @param int          $min    最小值
 * @return int
 */
function random($min,$max) :int {
    return mt_rand($min,$max);
}



/**
 * 生成指定长度的随机字符串 范围包括A-Za-z0-9
 * @param int          $length    字符串长度
 * @param string       $chars     可选，可自己提供字符串池
 * @return int
 */
function randomstring(int $length,string $chars = null): string{
    $charset ='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $c = ( $chars===null)?$charset:$chars;
    if (strlen($c)<1)
        throw new \Exception('Function randomstring : need $chars length is greater than 0');
    if ($length<1)
        throw new \Exception('Function randomstring : need $length is greater than 0');

    $str='';
    for ($i = 0;$i<$length;$i++)
        $str .= $c[ mt_rand(0, strlen($c) - 1) ];
    return $str;
}



/**
 * 通过点分字符串查询数组
 * @param array        $array    要查找的数组
 * @param string       $query    点分字符串
 * @return mixed       $default  默认值
 */
function dotq(array $array,string $query='',$default=null){
    if ($query==='') return $array;
    $name = explode('.',$query);
    $count=count($name);
    for($i=0;$i<$count;$i++){
        if (isset($array[$name[$i]])){
            $array = $array[$name[$i]];
        }else{
            return $default;
        }
    }
    return $array;
}



/**
 * 删除字符串最后的指定字符(支持中文)
 * @param string       $str    要处理的字符串
 * @param string       $char   要删除的字符
 */
function trimLastChar(string &$str,string $char){
    if ($str=='' || $char=='') return;
    if ((mb_substr($str,-1)) === $char){
        $str = mb_substr($str,0,mb_strlen($str)-1);
    }
}



/**
 * 删除字符串最后的指定字符(不支持中文，但是更快)
 * @param string       $str    要处理的字符串
 * @param string       $char   要删除的字符
 */
function trimLastCharFast(&$str,$char){
    if ($str=='' || $char=='') return;
    if ($str[strlen($str)-1] === $char){
        $str = substr($str,0,strlen($str)-1);
    }
}



/**
 * url安全的base64处理
 * @param string       $data   要解码/编码的字符串
 */
function base64_encode_url(string $data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
function base64_decode_url(string $data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
} 



/**
 * 读取Container
 * @return Container  
 */
function app() {
    return $GLOBALS['core_app'];
}



/**
 * 使用Container制造类
 * @param string     $abstract    类或者接口字符串
 * @param array      $para        传入的构造函数参数
 * @param bool       $autobind    是否自动绑定
 * @return mixed  
 */
function make(string $abstract, array $para = [],bool $autobind = true){
    return app()->make($abstract,$para,$autobind);
}



/**
 * 使用Container调用类函数，具体使用见IOC容器使用手册
 * @param mixed      $callback    调用
 * @param array      $para        传入的函数参数
 * @param bool       $method      调用的方法
 * @return mixed
 */
function call($callback,array $param = [],string $method = null){
    return app()->call($callback,$para,$method);
}



function session(){
    return app()->make('session');
}



function view(){
    return app()->make('view');
}



function cookie(){
    return app()->make('cookie');
}



function request(){
    return app()->make('request');
}

function path(){
    return app()->make('path');
}




function serviceInfo(){
    return request()->serviceInfo();
}



function config(string $configname=null,$default=null){
    if ($configname===null)
        return app()->make('config');
    else
        return app()->make('config')->get($configname,$default);
}



function DB(string $linkname = 'default'){
    $db = app()->make("db");
    $db->connect($linkname);
    return $db;
}



function i18n(string $msg,string $msgArea = 'zh-CN'){
    $nowarea = config('core.area');
    if ($nowarea == $msgArea){
        return $msg;
    }else{
        $areadata = config('i18n.$msgArea',null);
        if (!is_array($areadata))  return $msg;
        $key = array_search($msg,$areadata);
        if ($key === FALSE) return $msg;
        $nowareadata = config('i18n.$nowarea',null);
        if (($nowareadata[$key] ?? null) === null)
            return $msg;
        else
            return $nowareadata[$key];
    }
}



function pathjoin(...$path) : string {
    return make("path")->join(...$path);
}



/**
 * 判断字符串是不是手机号
 * @param mixed        $num    要处理的字符串
 * @return bool
 */
function is_phone($num){
    return (preg_match("/^1[345678]{1}\d{9}$/",$num)==1);
}



/**
 * 判断字符串是不是电子邮箱
 * @param mixed        $email    要处理的字符串
 * @return bool
 */
function is_email($email){
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}


/**
 * 随机返回数组的一个值
 * @param mixed        $array    要处理的数组
 * @return bool
 */
function random_array(array $array){
    $keys = array_keys($array);
    return $array[ mt_rand(0,count($keys) -1 ) ];
}


function core_path(...$path) : string{
    return path()->core_path(...$path);
}

function common_path(...$path) : string{
    return path()->common_path(...$path);
}

function runtime_path(...$path) : string{
    return path()->runtime_path(...$path);
}

function config_path(...$path) : string{
    return path()->config_path(...$path);
}

function app_path(...$path) : string{
    return path()->app_path(...$path);
}

function cache_path(...$path) : string{
    return path()->cache_path(...$path);
}

function support_path(...$path) : string{
    return path()->support_path(...$path);
}

function public_path(...$path) : string{
    return path()->public_path(...$path);
}