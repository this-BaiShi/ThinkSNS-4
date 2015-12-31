<?php
namespace Ts\AutoLoader;

use Ts;
use Ts\AutoLoader\VendorAutoLoader;
/**
 * Ts核心自动加载
 *
 * @package Ts\AutoLoader\TsAutoLoader
 * @author Seven Du <lovevipdsw@vip.qq.com>
 **/
class TsAutoLoader
{
	/**
	 * 自动加载入口
	 *
	 * @param string $namespace 命名空间
	 * @return boolean
	 * @author Seven Du <lovevipdsw@vip.qq.com>
	 **/
	public static function entry($namespace)
	{
		$namespace = str_replace('/', Ts::DS, $namespace);
		$namespace = str_replace('\\', Ts::DS, $namespace);
		$namespace = explode(Ts::DS, $namespace);
		switch ($namespace[0]) {
			case 'Vendor':
				$namespace = VendorAutoLoader::autoLoader($namespace);
				break;
			
			case 'Ts':
			default:
				$namespace = self::autoLoader($namespace);
				break;
		}
		return call_user_func_array('Ts::import', $namespace);
	}

	/**
	 * Ts自身文件加载
	 *
	 * @param array $namespace 切割成数组的命名空间
	 * @return array
	 * @author Seven Du <lovevipdsw@vip.qq.com>
	 **/
	public static function autoLoader(array $namespace)
	{
		if ($namespace[0] == 'Ts') {
			unset($namespace[0]);
			array_unshift($namespace, Ts::getRootPath());
			array_push($namespace, '.php');
			return $namespace;
		}
		return self::TsOldAutoLoader($namespace);
	}

	/**
	 * 原有Ts的命名空间加载
	 *
	 * @param array $namespace 切割成数组的命名空间
	 * @return array
	 * @author Seven Du <lovevipdsw@vip.qq.com>
	 **/
	public static function TsOldAutoLoader(array $namespace)
	{
		array_unshift($namespace, TS_ROOT);
		array_push($namespace, '.class.php');
		return $namespace;
	}

} // END class TsAutoLoader