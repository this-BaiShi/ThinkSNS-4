<?php

namespace Ts\Model;

use Ts\Base\Model;

/**
* 项目模型
*/
class Project extends Model{
    protected $table = 'project_data';
    protected $primaryKey = 'data_id';

    public function category(){
    	return $this->hasOne('Ts\\Model\\ProjectCat','category_id','category_id');
    }

    public function listData(){
    	return $this->hasOne('Ts\\Model\\ListData','data_id','data_id');
    }
}	