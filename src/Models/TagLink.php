<?php

namespace Ts\Model;

use Ts\Base\Model;

/**
* 项目模型
*/
class TagLink extends Model{
    protected $table = 'topic_tag_link';
    protected $primaryKey = 'list_id';

    public function tag(){
    	return $this->hasOne('Ts\\Model\\Tag','tag_id','tag_id');
    }
    
}	