<?php

namespace Ts\Base;

use Ts\Base\Controller;

/**
 * 空控制器
 *
 * @package Ts\Base\NoneController
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
abstract class NoneController extends Controller
{
    /**
     * 不存在控制器的运行方法
     *
     * @return void
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    abstract protected function run();

    /**
     * 实例化后的执行
     *
     * @return void
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    protected function classConstructAfter()
    {
        $this->run();
    }

} // END abstract class NoneController extends Controller
