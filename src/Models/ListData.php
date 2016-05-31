<?php

namespace Ts\Model;

use Ts\Base\Model;

/**
* 项目/话题APi
*/
class ListData extends Model{
	protected $table = 'list';
    /**
     * 表主键
     *
     * @var string
     */
    protected $primaryKey = 'list_id';


    public function topic(){
    	return $this->hasOne('Ts\\Model\\Topic','data_id','data_id');
    }

    public function project(){
    	return $this->hasOne('Ts\\Model\\Project','data_id','data_id');
    }

    public function taglinks(){
    	return $this->hasMany('Ts\\Model\\TagLink','list_id','list_id');
    }

    public function diggs(){
        return $this->hasMany('Ts\\Model\\Digg','list_id','list_id');
    }
}