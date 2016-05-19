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
class Interceptor extends Plugin {

    /**
     * 转发处理器
     * @param string|array $options
     * @throws Exception
     * @return mixed|void
     */
    public function Execute($options){
        if(is_string($options)) $options = array($options);
        foreach($options as $value) {
            if($result = $this->Check($value)) continue;
            throw new Exception('请求无法通过拦截器',400);
        }
    }

    /**
     * 执行校验
     * @param string $option
     * @return bool mixed
     */
    public function Check($option){
        $option = $this->ParameterMatches($option);
        $option = explode('.',$option);
        $action = array_pop($option);
        $classname = implode('\\',$option);
        return $classname::$action($this->session, $this->request);
    }

}