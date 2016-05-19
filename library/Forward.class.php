<?php
namespace Swiftx\Router;

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * 路由转发器
 * ---------------------------------------------------------------------------------------------------------------------
 * @author		胡永强  <odaytudio@gmail.com>
 * @since		2015-10-09
 * @copyright	Copyright (c) 2014-2015 Swiftx Inc.
 * ---------------------------------------------------------------------------------------------------------------------
 */
class Forward extends Component {

    /**
     * 组件执行
     * @param Processor $processor
     * @param Config    $config
     * @return bool
     */
    public function Execute(Processor &$processor, Config $config){
        $option = explode('::',$this->options['Forward']);
        $action = 'Action'.$option[0];
        $result = $this->$action($option[1], $config, $processor);
        if($result) $processor->Output($result);
    }

    /**
     * 转发控制器处理
     * @param $forward
     * @throws Exception
     */
    protected function ActionText($forward){
        return $forward;
    }

    /**
     * 转发方法处理
     * @param string $forward
     * @param Config $config
     * @return string
     * @throws Exception
     */
    protected function ActionMethod($forward, Config $config){
        $forward = $this->ParameterMatches($forward);
        $forward = explode('.', $forward);
        if(count($forward) == 1) $forward[] = 'Index';
        else if(count($forward) < 1)
            throw new Exception('配置不正确:未指定类',500);
        $action = array_pop($forward);
        $controller = implode('\\', $forward);
        $params = [$this->session, $this->request,];
        if(isset($this->options['Options']))
            $params[] = $this->options['Options'];
        $method = $controller.'::'.$action;
        $result = call_user_func_array($method, $params);
        if(is_string($result)){
            if(isset($this->options['View'][$result]))
                $result = $this->options['View'][$result];
            $result = explode('::',$result);
            $action = 'Action'.$result[0];
            return $this->$action($result[1], $config);
        }
        if($result instanceof IView) {
            $result->Assign('Session', $this->session);
            $result->Assign('Request', $this->request);
            return $result->Render();
        }
    }


    /**
     * 视图类型
     * @param string $forward
     * @param Config $config
     * @return string
     * @throws Exception
     */
    protected function ActionView($forward, Config $config){
        $forward = $this->ParameterMatches($forward);
        $forward = explode('.', $forward);
        if(count($forward) < 2)
            throw new Exception('配置不正确:文件缺少后缀',500);
        $type = array_pop($forward);
        $format = $config->Views;
        if(!array_key_exists($type, $format))
            throw new Exception('配置不正确:类型不存在',500);
        $forward = implode(DS, $forward).'.'.$type;
        $driver = $format[$type];;
        $driver = new $driver($forward);
        if($driver instanceof IView) {
            $driver->Assign('Session', $this->session);
            $driver->Assign('Request', $this->request);
            if(!empty($this->options['DataAccess'])) {
                foreach ($this->options['DataAccess'] as $name => $class) {
                    $class = explode('.', $class);
                    $method = array_pop($class);
                    $class = implode('\\', $class);
                    $value = call_user_func([$class, $method], $this->session, $this->request);
                    $driver->Assign($name, $value);
                }
            }
            return $driver->Render();
        }
    }

    /**
     * 转发控制器处理
     * @param string $forward
     * @param Config $config
     * @return string
     * @throws Exception
     */
    protected function ActionController($forward, Config $config){
        $forward = $this->ParameterMatches($forward);
        $forward = explode('.', $forward);
        if(count($forward) == 1) $forward[] = 'Index';
        else if(count($forward) < 1)
            throw new Exception('配置不正确:未指定类',500);
        $action = array_pop($forward);
        $controller = implode('\\', $forward);
        $controller = new $controller($this->session, $this->request);
        if(isset($this->options['Options']))
            $result = $controller->$action($this->options['Options']);
        else $result = $controller->$action();
        if(is_string($result)){
            if(isset($this->options['View'][$result]))
                $result = $this->options['View'][$result];
            $result = explode('::',$result);
            $action = 'Action'.$result[0];
            return $this->$action($result[1], $config);
        }
        if($result instanceof IView) {
            $result->Assign('Session', $this->session);
            $result->Assign('Request', $this->request);
            return $result->Render();
        }
    }

    /**
     * 二级路由处理
     * @param string $forward
     * @param Config $config
     * @param Processor $processor
     * @return null
     */
    protected function ActionRouter($forward, Config $config, Processor $processor){
        /** @noinspection PhpIncludeInspection */
        $Rule = include $this->ParameterMatches($forward);
        /** @var Processor $classname */
        $classname = get_class($processor);
        $Router = $classname::Parser($this->session,$this->request,$Rule);
        $Router->Execute($config);
    }

}