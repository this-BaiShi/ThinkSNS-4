<?php

namespace Ts\Base;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * 数据模型基类
 *
 * @package Ts\Base\Model
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
abstract class Model extends Eloquent
{
    abstract protected $table;

    abstract protected $fillable = array();

    protected $softDelete = true; // 默认开启软删除

} // END abstract class Model extends Eloquent
