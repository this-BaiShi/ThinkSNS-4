<?php

/**
* 话题/项目 模型
*/
class ProjectModel extends Model{
	//获取标签
	public function getTag(){
		$list = D('topic_tag')->select();
		return $list;
	}
	//获取列表
	public function getlist($type){
		$map['type'] = $type;
		$map['is_del'] = 0;
		$list = D('list')->where($map)->findPage(20);
		return $list;
	}
	//获取数据
	public function getData($list_id){
		$list = D('list')->where(array('list_id'=>$list_id))->find();
		if ($list['type'] == 2) {
			$project = D('project_data')->where(array('data_id'=>$list['data_id']))->find();
			$list['category_id'] = $project['category_id'];
			$list['amount'] = $project['amount'];
			$list['use'] = $project['use'];
			$list['etime'] = $project['etime'];
			$list['stime'] = $project['stime'];
			$list['image'] = $project['image'];
		}

		return $list;
	}
}