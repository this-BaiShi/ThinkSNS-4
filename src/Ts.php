<?php
use Ts\AutoLoader\TsAutoLoader;
use Ts\AutoLoader\VendorAutoLoader;
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
	 * 入口文件
	 *
	 * @return void
	 * @author Seven Du <lovevipdsw@vip.qq.com>
	 **/
	public static function run()
	{
		self::init();
		/* 新的自动加载类 */
		spl_autoload_register(function($namespace) {
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
		if(function_exists('date_default_timezone_set'))
		{
			date_default_timezone_set('Asia/Shanghai');
		}
		/* 加载必要文件 */
		self::import(self::getRootPath(), 'AutoLoader', 'TsAutoLoader', '.php');
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
		$name .= $ext;unset($ext);
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
	 * 添加一个第三方包
	 *
	 * @param string $vendorName 第三方包 在第三方包目录 /Vendor/$vendorName/Entry.php 这个文件必须存在，否则会失效或者报错，如果，如果是针对Ts开发的第三方包，直接用命名空间使用，无需使用本方法。
	 * @return void
	 * @author Seven Du <lovevipdsw@vip.qq.com>
	 **/
	public static function vendor($vendorName)
	{
		call_user_func_array('self::import', VendorAutoLoader::import($vendorName));
	}

} // END final class Ts