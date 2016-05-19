<?php
namespace Swiftx\Router;
use Swiftx\Tools\Debug;

/**
 * 路由转发器
 * @author		胡永强  <odaytudio@gmail.com>
 * @since		2015-10-09
 * @copyright	Copyright (c) 2014-2015 Swiftx Inc.
 */
class Validator extends Plugin {

    /**
     * 转发处理器
     * @param string|array $options
     * @throws Exception
     * @return mixed|void
     */
    public function Execute($options){
        foreach($options as $key => $value) {
            $data = $this->FetchData($key);
            if($data === null){
                if(!$value['Required']) continue;
                $exception = explode('::',$value['Exception']);
                $action = 'Exception'.$exception[0];
                return $this->$action($exception[1]);
            }
            if(is_string($value['Processor']))
                $value['Processor'] = array($value['Processor']);
            foreach($value['Processor'] as $processor) {
                $processor = explode('::', $processor);
                $action = 'Processor' . $processor[0];
                if (!$this->$action($data, $processor[1])) {
                    $exception = explode('::', $value['Exception']);
                    $action = 'Exception' . $exception[0];
                    return $this->$action($exception[1]);
                }
            }
        }
    }

    /**
     * 校验异常：抛出
     * @param $option
     */
    protected function ExceptionThrows($option){
        $option = explode('.', $option);
        $code = array_pop($option);
        $classname = implode('\\', $option);
        throw new $classname('校验未通过', $code);
    }

    /**
     * 校验异常：文本
     * @param $option
     */
    protected function ExceptionText($option){
        die($option);
    }

    /**
     * 静态方法校验
     * @param string $data
     * @param string $option
     * @return mixed
     */
    protected function ProcessorMethod($data, $option){
        $option = explode('.',$option);
        $action = array_pop($option);
        $classname = implode('\\',$option);
        $classname = $classname.'::'.$action;
        return call_user_func($classname, $data);
    }

    /**
     * 正则方式校验
     * @param string $data
     * @param string $option
     * @return int
     */
    protected function ProcessorRegex($data, $option){
        $option = '/'.addcslashes($option,'/').'/';
        return preg_match($option, $data);
    }

    /**
     * 枚举类型校验
     * @param string $data
     * @param string $option
     * @return int
     */
    protected function ProcessorEnum($data, $option){
        $option = explode(',', $option);
        return in_array($data, $option);
    }

    /**
     * 获取数据
     * @param string $option
     * @param $option
     * @return null|string
     * @throws Exception
     */
    protected function FetchData($option){
        $option = $this->ParameterMatches($option);
        $option = explode('.', $option);
        $type = array_shift($option);
        $name = implode('.', $option);
        switch(strtoupper($type)){
            case 'GET':
                return $this->request->Get->Value($name, null);
            case 'POST':
                return $this->request->Post->Value($name, null);
            case 'Matches':
                return isset($this->matches[$name])?$this->matches[$name]:null;
            default:
                throw new Exception('配置不正确',500);
        }
    }
}