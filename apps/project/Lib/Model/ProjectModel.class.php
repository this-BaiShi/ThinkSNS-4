<?php

/**
* 话题/项目 模型
*/
class ProjectModel extends Model{
	
	public function getTag(){
		$list = D('topic_tag')->select();
		return $list;
	}
	
}