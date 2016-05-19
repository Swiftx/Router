<?php
namespace Swiftx\Router;
use Swiftx\Http\Request;
use Swiftx\Http\Session;
use Swiftx\System\Object;

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * 路由器插件抽象类
 * ---------------------------------------------------------------------------------------------------------------------
 * @author		胡永强  <odaytudio@gmail.com>
 * @since		2015-12-11
 * @copyright	Copyright (c) 2014-2015 Swiftx Inc.
 * ---------------------------------------------------------------------------------------------------------------------
 */
abstract class Plugin extends Object implements IPlugin {

    protected $request = null;
    protected $session = null;
    protected $matches = null;

    /**
     * 处理器构造
     * @param Session $session
     * @param Request $request
     * @param array $matches
     */
    public function __construct(Session $session, Request $request, array $matches){
        $this->request = $request;
        $this->session = $session;
        $this->matches = $matches;
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

}