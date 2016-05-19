<?php
namespace Swiftx\Router;
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
interface IProcessor {

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
    public function __construct(Session $session, Request $request, array $matches, array $options);

    /**
     * ----------------------------------------------------------
     * 执行路由处理操作
     * ----------------------------------------------------------
     * @param Config $config
     * ----------------------------------------------------------
     * @throws \Exception
     * ----------------------------------------------------------
     */
    public function Execute(Config $config);

    /**
     * 解析路由器
     * @param Session $session
     * @param Request $request
     * @param array $rule
     */
    public static function Parser(Session $session, Request $request, array $rule);

}