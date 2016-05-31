<?php
namespace Ts\Model;
use Ts\Base\Model;

/**
* 话题模型
*/
class OrderLog extends Model
{
	protected $table = 'project_order_log';
	protected $primaryKey = 'log_id';


	public function projects(){
		return $this->hasOne('Ts\\Model\\List','list_id','list_id');
	}

	
}