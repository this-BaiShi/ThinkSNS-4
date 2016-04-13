<?php

namespace Ts\Model;

use Ts\Base\Model;

/**
 * 用户数据模型
 *
 * @package App\H5\Model\User
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
class User extends Model
{
    protected $table = 'user';

    protected $primaryKey = 'uid';

    protected $softDelete = false;

    protected $hidden = array('password', 'login_salt');

    protected static $instances = array();

} // END class User extends Model
