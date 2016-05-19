<?php
namespace Swiftx\Router;
use Swiftx\Http\Request;
use Swiftx\Http\Session;
/**
 * ---------------------------------------------------------------------------------------------------------------------
 * 路由器插件接口
 * ---------------------------------------------------------------------------------------------------------------------
 * @author		胡永强  <odaytudio@gmail.com>
 * @since		2015-12-11
 * @copyright	Copyright (c) 2014-2015 Swiftx Inc.
 * ---------------------------------------------------------------------------------------------------------------------
 */
interface IComponent {

    /**
     * 路由器组件初始化构造
     * @param Session $session
     * @param Request $request
     * @param array   $matches
     * @param array   $options
     */
    public function __construct(Session $session, Request $request, array $matches, array $options);

    /**
     * 组件执行
     * @param Processor $processor
     * @param Config    $config
     * @return bool
     */
    public function Execute(Processor &$processor, Config $config);


}