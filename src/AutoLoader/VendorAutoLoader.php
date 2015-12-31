<?php
namespace Ts\AutoLoader;

use Ts;
/**
 * 第三方包自动加载
 *
 * @package Ts\AutoLoader\VendorAutoLoader
 * @author Seven Du <lovevipdsw@vip.qq.com>
 **/
class VendorAutoLoader
{
	/**
	 * 第三方包命名空间入口
	 *
	 * @param array $namespace 切割后的命名空间
	 * @return array
	 * @author Seven Du <lovevipdsw@vip.qq.com>
	 **/
	public static function autoLoader(array $namespace)
	{
		array_unshift($namespace, Ts::getRootPath());
		array_push($namespace, '.php');
		return $namespace;
	}

	/**
	 * 引入第三方包
	 *
	 * @return array
	 * @author Seven Du <lovevipdsw@vip.qq.com>
	 **/
	public static function import($vendorName)
	{
		$namespace = strval($namespace);
		return array(Ts::getRootPath(), 'Vendor', 'Entry', '.php');
	}

} // END class VendorAutoLoader