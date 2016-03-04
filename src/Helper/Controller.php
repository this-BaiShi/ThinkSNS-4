<?php

namespace Ts\Helper;

/**
 * 控制器帮助类
 *
 * @package Ts\Helper\Controller;
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
class Controller
{

    /**
     * 定义控制器中动作的后缀
     *
     * @var string
     **/
    const ACTION_SUFFIX = 'Action';

    /**
     * 运行的应用名称
     *
     * @var string
     **/
    protected static $appName;

    /**
     * 运行的控制器名称
     *
     * @var string
     **/
    protected static $controllerName;

    /**
     * 运行的控制器动作名称
     *
     * @var string
     **/
    protected static $appAction;

    /**
     * 控制器缓存
     *
     * @var Array
     **/
    protected static $controllers = array();

    /**
     * 需要兼容的控制器命名空间
     *
     * @var array
     **/
    protected static $controllerClass = array(
        'Ts-2016' => 'App\\%s\\Controller\\%s',
        'Ts-2015' => 'Apps\\%s\\Controller\\%s',
        'Ts-old'  => '%sAction'
    );

    /**
     * 构建需要的对象 
     *
     * @return void
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    protected static function build($oldControllerName = false)
    {
        $className = null;
        foreach (self::$controllerClass as $key => $value) {
            if (
                $key == 'Ts-2016' && 
                class_exists($className = sprintf($value, ucfirst(self::$appName), ucfirst(self::$controllerName)))
            ) {
                self::setAction(sprintf('%s%s', self::$appAction, self::ACTION_SUFFIX));
                break;

            } elseif (
                $key == 'Ts-2015' && 
                class_exists($className = sprintf($value, self::$appName, ucfirst(self::$controllerName)))
            ) {
                break;

            } elseif (
                $key == 'Ts-old' && 
                class_exists($className = sprintf($value, ucfirst(self::$controllerName)))
            ) {
                break;
            }
        }

        if (!class_exists($className) && !$oldControllerName) {
            $className = self::$controllerName;
            self::setController('empty');
            self::build($className);
            return false;

        } elseif (!class_exists($className)) {
            throw_exception(L('_MODULE_NOT_EXIST_') . ' ' . $oldControllerName);
        }
        self::$controllers[self::$appName] = new $className;
    }

    /**
     * 设置运行的应用
     *
     * @return void
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    public static function setApp($appName)
    {
        self::$appName = $appName;
    }

    /**
     * 设置控制器名称
     *
     * @return void
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    public static function setController($controllerName)
    {
        self::$controllerName = $controllerName;
    }

    /**
     * 设置运行的控制器执行的动作
     *
     * @return self
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    public static function setAction($actionName)
    {
        self::$appAction = $actionName;
    }

    /**
     * 运行
     *
     * @return void
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    public static function run()
    {
        self::build();

        $GLOBALS['time_run_detail']['action_instance'] = microtime(true); // 旧系统的时间记录
        return call_user_func(array(self::$controllers[self::$appName], self::$appAction));
    }

} // END class Controller
