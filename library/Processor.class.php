<?php
namespace Swiftx\Router;
use Swiftx\System\Object;
use Swiftx\DataType\String;
use Swiftx\Http\Request;
use Swiftx\Http\Session;

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * 路由处理器
 * ---------------------------------------------------------------------------------------------------------------------
 * @author		胡永强  <odaytudio@gmail.com>
 * @since		2015-10-09
 * @copyright	Copyright (c) 2014-2015 Swiftx Inc.
 * ---------------------------------------------------------------------------------------------------------------------
 */
class Processor extends Object implements IProcessor{

    /** @var Request */
    protected $request;
    /** @var Session */
    protected $session;
    /** @var array */
    protected $matches;
    /** @var array */
    protected $options;
    /** @var string */
    protected $outputs;

    /**
     * ----------------------------------------------------------
     * 处理器构造
     * ----------------------------------------------------------
     * @param Session $session
     * @param Request $request
     * @param array   $matches
     * @param array   $options
     * ----------------------------------------------------------
     */
    public function __construct(Session $session, Request $request, array $matches, array $options){
        $this->request = $request;
        $this->session = $session;
        $this->matches = $matches;
        $this->options = $options;
        $this->outputs = '';
    }

    /**
     * 输出到输出缓存
     * @param $value
     */
    public function Output($value){
        $this->outputs .= $value;
        echo $value;
    }

    /**
     * ----------------------------------------------------------
     * 执行路由处理操作
     * ----------------------------------------------------------
     * @param Config $config
     * ----------------------------------------------------------
     * @throws \Exception
     * ----------------------------------------------------------
     */
    public function Execute(Config $config){
        try {
            // 执行路由插件
            foreach ($config->Plugins as $key => $value) {
                if (!array_key_exists($key, $this->options)) continue;
                $this->FetchPlugin($value)->Execute($this->options[$key]);
            }
            // 执行路由组件
            foreach ($config->Components as $key => $value) {
                $result = $this->FetchComponent($value)->Execute($this, $config);
                if($result == false) break;
            }
            // 页面静态化
            if(isset($this->options['Cache'])){
                $cachePath = $this->options['Cache'][0];
                $temp = explode('/',$this->request->Uri);
                $cacheFile = array_pop($temp);
                foreach($temp as $value){
                    $cachePath = $cachePath.DS.$value;
                    if(!is_dir($cachePath)) mkdir($cachePath);
                }
                $cacheFile = $cachePath.DS.$cacheFile;
                file_put_contents($cacheFile, $this->outputs);
            }
        } catch (\Exception $event){
            if(!isset($this->options['Exception'])) throw $event;
            if($event = $this->Exception($event, $config)) throw $event;
        }
    }

    /**
     * 路由异常解析
     * @param \Exception $event
     * @param Config $config
     * @return null|\Exception
     */
    protected function Exception(\Exception $event, Config $config){
        $classname = str_replace('\\','.',get_class($event)).'.'.$event->getCode();
        if(!array_key_exists($classname, $this->options['Exception'])) return $event;
        $forward = $config->Components['Forward'];
        $options['Forward'] = $this->options['Exception'][$classname];
        if(isset($this->options['View'])) $options['View'] = $this->options['View'];
        /** @var Forward $forward */
        $forward = new $forward($this->session, $this->request, $this->matches, $options);
        $forward->Execute($this, $config); return false;
    }

    /**
     * 解析url参数
     * @param $value
     * @return mixed
     */
    protected function ParameterMatches($value){
        foreach ($this->matches as $node => $data)
            $value = str_replace( '$('.$node.')', $data, $value);
        return $value;
    }

    /**
     * 获取插件
     * @param string $name
     * @return Plugin
     */
    protected function FetchPlugin($name){
        return new $name($this->session, $this->request, $this->matches);
    }

    /**
     * 获取组件
     * @param string $name
     * @return Forward
     */
    public function FetchComponent($name){
        return new $name($this->session, $this->request, $this->matches, $this->options);
    }

    /**
     * 解析路由器
     * @param Session $session
     * @param Request $request
     * @param array $rule
     * @return static
     * @throws Exception
     */
    public static function Parser(Session $session, Request $request, array $rule){
        // 搜索路由表规则
        foreach ($rule as $key => $value){
            $value['Pattern'] = '/'.addcslashes($value['Pattern'],'/').'/';
            if(preg_match($value['Pattern'], $request->Script, $matches)){
                $value['Name'] = $key;
                $router = static::class;
                return new $router($session, $request, $matches, $value);
            }
        }
        // 找不到匹配路由
        throw new Exception('找不到路由规则', 404);
    }

}