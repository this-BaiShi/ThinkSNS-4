<?php
/* 功能不完善，后续根据开发需要，慢慢完善，替换以前系统底层 */
/* 一步一步替换掉 */
/**
 * 定义根目录
 **/
define('TS_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

// include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Ts.php';
// Ts::run();

$file = dirname(__FILE__) . '/Vendor/autoload.php';
if (!file_exists($file)) {
    echo 'You must set up the project dependencies, run the following commands:', PHP_EOL,
         'curl -sS https://getcomposer.org/installer | php', PHP_EOL,
         'php composer.phar install', PHP_EOL;
    exit(1);
}

$loader = include $file;

/* Run */
Ts::run();