<?php

use Composer\Autoload\ClassLoader;
use Ts\AutoLoader\TsAutoLoader;

/**
 * 新入口核心
 *
 * @package Ts;
 * @author Seven Du <lovevipdsw@vip.qq.com>
 **/
final class Ts
{
    /**
     * 系统文件目录分隔符
     *
     * @var string
     **/
    const DS = DIRECTORY_SEPARATOR;

    /**
     * 文件列表
     *
     * @var array
     **/
    protected static $_files = array();

    /**
     * 框架根
     *
     * @var string
     **/
    protected static $_root;

    /**
     * 储存Composer自动加载类的对象
     *
     * @var new \Composer\Autoload\ClassLoader();
     **/
    protected static $_classLoader;

    /**
     * 入口文件
     *
     * @param \Composer\Autoload\ClassLoader $classLoader
     * @return void
     * @author Seven Du <lovevipdsw@vip.qq.com>
     **/
    public static function run(ClassLoader $classLoader)
    {
        self::$_classLoader = $classLoader;
        self::init();
        /* 新的自动加载类 */
        spl_autoload_register(function ($namespace) {
            TsAutoLoader::entry($namespace);
        });
    }

    /**
     * 获取框架根目录
     *
     * @return string
     * @author Seven Du <lovevipdsw@vip.qq.com>
     **/
    public static function getRootPath()
    {
        return self::$_root;
    }

    /**
     * 初始化
     *
     * @return void
     * @author Seven Du <lovevipdsw@vip.qq.com>
     **/
    protected static function init()
    {
        self::$_root = dirname(__FILE__);
        /* # 设置时区 */
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Shanghai');
        }
    }

    /**
     * 文件加载类
     *
     * @param string $name 文件名
     * @param string $ext 文件拓展名
     * @param param [param ...] 按照完整路径的层级，最后一个默认为拓展名
     * @return boolean
     * @author Seven Du <lovevipdsw@vip.qq.com>
     **/
    public static function import($name, $ext = '.php')
    {
        $name  = func_get_args();
        $ext   = array_pop($name);
        $name  = implode(self::DS, $name);
        $name .= $ext;
        unset($ext);
        $name  = 'file://' . $name;
        /* 是否已经加载过了 */
        if (in_array($name, self::$_files)) {
            return true;

        /* 加载文件，并插入到记录 */
        } elseif (file_exists($name)) {
            array_push(self::$_files, $name);
            return include $name;
        }
        return false;
    }

    /**
     * 取得Composer的ClassLoader对象
     *
     * @return new \Composer\Autoload\ClassLoader();
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    public static function classLoader()
    {
        return self::$_classLoader;
    }
} // END final class Ts
