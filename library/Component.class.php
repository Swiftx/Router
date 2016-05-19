<?php
namespace Swiftx\Router;
use Swiftx\System\Object;
use Swiftx\Http\Request;
use Swiftx\Http\Session;
/**
 * ---------------------------------------------------------------------------------------------------------------------
 * 路由器插件抽象类
 * ---------------------------------------------------------------------------------------------------------------------
 * @author		胡永强  <odaytudio@gmail.com>
 * @since		2015-12-11
 * @copyright	Copyright (c) 2014-2015 Swiftx Inc.
 * ---------------------------------------------------------------------------------------------------------------------
 */
abstract class Component extends Object implements IComponent {

    protected $request;
    protected $session;
    protected $matches;
    protected $options;

    /**
     * 路由器组件初始化构造
     * @param Session $session
     * @param Request $request
     * @param array   $matches
     * @param array   $options
     */
    public function __construct(Session $session, Request $request, array $matches, array $options){
        $this->request = $request;
        $this->session = $session;
        $this->matches = $matches;
        $this->options = $options;
    }

    /**
     * 解析url参数
     * @param $value
     * @return mixed
     */
    protected function ParameterMatches($value){
        foreach ($this->matches as $node => $data)
            $value = str_replace( '${'.$node.'}', $data, $value);
        return $value;
    }

}