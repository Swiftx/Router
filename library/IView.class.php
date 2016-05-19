<?php
namespace Swiftx\Router;
/**
 * ---------------------------------------------------------------------------------------------------------------------
 * 视图对象接口
 * ---------------------------------------------------------------------------------------------------------------------
 * @author		Hismer <odaytudio@gmail.com>
 * @since		2014-04-28
 * @copyright	Copyright (c) 2014-2015 Swiftx Inc.
 * ---------------------------------------------------------------------------------------------------------------------
 */
interface IView{

    /**
     * 模板赋值
     * @param $key
     * @param $data
     * @return null
     */
    public function Assign($key, $data);

    /**
     * 渲染显示模板
     * @return mixed
     */
    public function Display();

    /**
     * 渲染显示模板
     * @return string
     */
    public function  Render();

}