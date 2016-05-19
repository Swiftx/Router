<?php
namespace Swiftx\Router;
/**
 * ---------------------------------------------------------------------------------------------------------------------
 * 路由转发器
 * ---------------------------------------------------------------------------------------------------------------------
 * @author		胡永强  <odaytudio@gmail.com>
 * @since		2015-10-09
 * @copyright	Copyright (c) 2014-2015 Swiftx Inc.
 */
class Parameter extends Plugin {

    /**
     * 转发处理器
     * @param string|array $options
     * @throws Exception
     * @return mixed|void
     */
    public function Execute($options){
        foreach($options as $key => $value){
            $key = explode('.',$key);
            $method = $key[0];
            $param = $this->request->$method;
            $param[$key[1]] = $this->ParameterMatches($value);
        }
    }

}