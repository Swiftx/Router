<?php
namespace Swiftx\Router;
use Swiftx\System\Object;

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * 框架配置程序
 * ---------------------------------------------------------------------------------------------------------------------
 * @author		胡永强  <odaytudio@gmail.com>
 * @since		2015-11-11
 * @copyright	Copyright (c) 2014-2015 Swiftx Inc.
 * ---------------------------------------------------------------------------------------------------------------------
 * @property array Plugins          路由插件
 * @property array Components       路由组件
 * @property array Views            视图组件
 * ---------------------------------------------------------------------------------------------------------------------
 */
class Config extends Object {

    /** @var array 路由插件 */
    protected $_plugins = array();
    /** @var array 路由组件 */
    protected $_components = [
        'Forward' => 'Swiftx\\Router\\Forward',
        'Cache' => 'Swiftx\\Router\\Cache'
    ];
    /** @var array 视图组件 */
    protected $_views = array();

    /**
     * 所有插件配置
     * @return array
     */
    protected function _getPlugins(){
        return $this->_plugins;
    }

    /**
     * 配置路由插件
     * @param string $name
     * @param string $class
     */
    public function Plugin($name, $class){
        $this->_plugins[$name] = str_replace('.', '\\', $class);
    }

    /**
     * 所有组件配置
     * @return array
     */
    protected function _getComponents(){
        return $this->_components;
    }

    /**
     * 配置路由组件
     * @param string $name
     * @param string $class
     * @throws Exception
     */
    public function Component($name, $class){
        if(isset($this->_components[$name]))
            $this->_components[$name] = str_replace('.', '\\', $class);
        else throw new Exception('不存在的配置项', 500);
    }

    /**
     * 所有视图配置
     * @return array
     */
    protected function _getViews(){
        return $this->_views;
    }

    /**
     * 配置/读取视图组件
     * @param string $name
     * @param string $class
     * @return null|string
     * @throws Exception
     */
    public function View($name, $class=null){
        if($class == null)
            return isset($this->_views[$name])?$this->_views[$name]:null;
        $this->_views[$name] = str_replace('.', '\\', $class);
    }

}