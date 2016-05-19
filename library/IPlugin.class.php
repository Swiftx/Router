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
interface IPlugin {

    /**
     * 路由器插件初始化构造
     * @param Session $session
     * @param Request $request
     * @param array   $matches
     */
    public function __construct(Session $session, Request $request, array $matches);

    /**
     * 插件执行
     * @param $options
     * @return mixed
     */
    public function Execute($options);


}