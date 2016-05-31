<?php
namespace Ts\Model;
use Ts\Base\Model;

/**
* 话题模型
*/
class Topic extends Model
{
	protected $table = 'topic_data';
	protected $primaryKey = 'data_id';


	public function projects(){
		return $this->hasOne('Ts\\Model\\List','data_id','data_id');
	}

	
}