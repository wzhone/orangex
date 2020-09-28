<?php
namespace core;
use \core\exception\ExceptionX;

// +----------------------------------------------------------------------
// | Description: Container 包含一个简单的IOC容器，用于减低框架的耦合性。
// +----------------------------------------------------------------------
// | Author: wzh <i@wzh.one>  2020/8/12
// +----------------------------------------------------------------------

/**
 * Container作为一个IOC容器降低耦合性，支持三种绑定方法
 *  1.singleton 单例绑定模式
 *      通过单例绑定模式绑定的接口和类，在被make一次后会保存初始化后的实例。
 *      在以后的make中只会返回该实例。如果传递了参数，则绑定时会马上实例化。
 * 
 *  2.instance 实例绑定模式
 *      将一个接口和实例绑定在一起，make一律返回绑定的实例
 * 
 *  3.bind 普通绑定
 *      每次make的时候都初始化一个新的实例
 * 
 *   在以上的绑定方法中，bind和singleton支持传递构造函数参数，如果不传递参数则
 *   参数默认为null，如果传递过多参数则会省略。参数必须是一个数据，如果构造函数
 *   所需对象的构造函数也有参数，可以使用数组嵌套的方式传递参数。所有的绑定所支
 *   持的接口名不允许重复，后绑定的会覆盖前绑定的对象。
 *
 *  
 * 通过调用Container的make方法，制造所需的对象，允许传递为数组的参数。
 * 通过调用Container的alias方法，指定接口的别名，别名允许嵌套。
 * 
 * Container支持call方法使用容器调用函数，解决函数调用参数依赖问题
 *  支持五种传参方式
 *      1.直接传入闭包函数
 *      2.[类名,方法名]
 *      3.类名,方法名
 *      4."类名@方法名"
 *      5."类名::方法名"
 *  对于类非静态函数，在第二种方法上，支持直接将类名替换为类实例。
 *  如果函数为非类静态函数，则需要实现绑定类本身，如果不使用接口可以进行自绑
 *  定。容器会自动产生类实例，并进行函数调用。此过程不支持构造函
 *  数传参，如有传参需求可以实现构造后传入。
 * 
 * 
 * 
 */


class Container{

    private $bindings = []; // 类名字符串 => 返回实例的闭包
    private $instances = []; // 类名字符串 => 实例Object
    private $singletons = []; // 类名字符串 => 返回实例的闭包
    private $aliases = []; // 字符串 => 类名字符串


    # 使用IOC容器工厂生成类
    public function make(string $abstract,array $parameter = [],
        bool $autobind = true)
    {
        if ($this->aliases[$abstract]?? false ){
            return $this->make($this->aliases[$abstract],$parameter);
        }

        if ($this->instances[$abstract]?? false ){
            return $this->instances[$abstract];
        }

        if ($this->singletons[$abstract] ?? false ){
            $singleton =  $this->singletons[$abstract];
            unset($this->singletons[$abstract]);
            $inst = $singleton($this,$parameter);
            $this->instances[$abstract] = $inst;
            return $inst;
        }

        if ($this->bindings[$abstract]?? false ){
            $concrete = $this->bindings[$abstract];
            return $concrete($this,$parameter);
        }

        if ($autobind){
            $this->bind($abstract);
            return $this->make($abstract,$parameter,false);
        }

        throw new \Exception("Class [$abstract] that not bound");
    }

    //使用容器调用函数
    public function call($callback,array $param = [],string $method = null){

        # $callback为类名 $method为方法名的情况
        if ($method != null ){
            return $this->call([$callback,$method],$param);
        }

        # $callback为 '类名@方法名' 的情况
        if (is_string($callback) && strpos($callback,'@')!= FALSE){
            return $this->call(explode('@',$callback,2),$param);
        }

        # 类静态函数 方式
        if (is_string($callback) && strpos($callback,'::')!= FALSE){
            return $this->call(explode('::',$callback,2),$param);
        }

        # [类名，方法名] 方式
        if (is_array($callback) && count($callback)==2){
            return $this->call_class_method($callback,$param);
        }

        # 闭包函数方式
        if ($callback instanceof \Closure) {
            return $this->call_function($callback,$param);
        }

        throw new \Exception("Invalid callback[$callback]");
    }

    # 调用类函数
    private function call_class_method($callback,$param){
        $classreflect = new \ReflectionClass($callback[0]);
        if ($classreflect->isAbstract()){
            return $this->call_class_method([
                    $this->make($callback[0]),
                    $callback[1]
                ],$param);
        }

        $method = $classreflect->getMethod($callback[1]);
        $method->setAccessible(true);

        $inst = null;
        if ($method->isStatic()){
            $inst = null; 
        }else if (is_object($callback[0])){
            $inst = $callback[0];
        }else{
            $inst = $this->make($callback[0],[],true);
        }

        $formal = $method->getParameters();
        $realparam = $this->resolve($formal,$param);
        return $method->invokeArgs($inst,$realparam);

    }

    # 调用一个函数,参数通过容器给出
    private function call_function($closure,$param){
        $reflect_fun = new \ReflectionFunction($closure);
        $formal = $reflect_fun->getParameters();
        $realparam = $this->resolve($formal,$param);
        return $reflect_fun->invokeArgs($realparam);
    }


    public function singleton($abstract,$concrete = null,$parm = null){
        $this->bind($abstract,$concrete,true);
        if ($parm != null){
            # 绑定单例时提供了参数则直接实例化
            $this->make($abstract,$parm);
        }
    }

    public function instance($abstract, $instance){
        $this->removeBound($abstract);
        $this->instances[$abstract] = $instance;
    }

    public function bind($abstract, $concrete = null,$singleton = false){
        $this->removeBound($abstract);
        if ($concrete == null){
            $concrete = $abstract; # 绑定自身
        }
        if (!$concrete instanceof \Closure) {
            // 调用闭包时，传入的参数是容器本身，即 $this
            $concrete = function ($myself,$param = []) use ($concrete) {
                return $myself->buildClosure($concrete,$param);
            };
        }
        if ($singleton)
            $this->singletons[$abstract] = $concrete;
        else
            $this->bindings[$abstract] = $concrete;

        return true;
    }

    # 绑定别名,被绑定的name需要事先绑定
    public function alias($alias,$name){
        if ($name == $alias) return false;
        $this->aliases[$alias] = $name;
    }

    # 通过字符串类名实例化一个类
    private function buildClosure(string $class,$parm = []){

        $reflector = new \ReflectionClass($class);
        if (!$reflector->isInstantiable()) {
            throw new ExceptionX("Class [$class] that cannot be instantiated");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $class;
        }else{
            $parameters = $constructor->getParameters();
            $realParameters = $this->resolve($parameters,$parm);
            return $reflector->newInstanceArgs($realParameters);
        }
    }


    # 解决构造函数的参数依赖 传入一组参数，指明需要的参数类型

    /**
     * 
     * $parameters Array ReflectionParameter
     */
    private function resolve(array $parameters,array $parm = []){
        $instPram = [];
        $realParameters = [];

        for ($i=0;$i<count($parameters);$i++){

            $parameter = $parameters[$i];
            $dependency = $parameter->getClass(); //参数类名
            $name = $parameter->getName(); //参数名

            # 基础类型的形参无法通过容器生成，直接传递给定参数
            if (is_null($dependency)) {

                if (isset($parm[$name]))
                    $realParameters[] =  $parm[$name];
                else
                    $realParameters[] =  $parm[$i] ?? null;

            }else if (isset($parm[$i]) &&
                ($parm[$i] != null ||  $parm[$name] != null)){
                    $p = null;
                if (isset($parm[$name]))
                    $p =  $parm[$name];
                else
                    $p =  $parm[$i];

                if ($p instanceof $dependency->name){
                    $realParameters[] = $p;
                }else{
                    if (!is_array($p)){
                        throw new ExceptionX('Parameter passing error');
                    }
                    $realParameters[] = $this->make($dependency->name,$p ?? []);
                }
            }else{
                $realParameters[] = $this->make($dependency->name);
            }

        }

        return (array)$realParameters;
    }

    # 解除所有绑定
    private function removeBound($name){
        unset($this->aliases[$name]);
        unset($this->instances[$name]);
        unset($this->bindings[$name]);
        unset($this->singletons[$name]);
    }

}