<?php
use Ts\Model;
/**
* 项目/话题 APi模型
*/
class ProjectApiModel extends \Model
{	
	//获取tag列表
	public function getTaglist(){
		$list = D('topic_tag')->select();


		$arr = getSubBykey($list,'tag_id');
		$arr = array_rand(array_flip($arr),5);
		$arr = implode(',', $arr);

		$map['tag_id'] = array('IN',$arr);

		$return = D('topic_tag')->where($map)->select();

		return $return;
	}		
	
	// //添加话题
	public function addTopic($addinfo){
		$returninfo['status'] = 0;
		if (empty($addinfo['title'])) {
			$returninfo['msg'] = '请输入标题';
			return $returninfo;
		}
		if (empty($addinfo['content'])) {
			$returninfo['msg'] = '请输入内容';
			return $returninfo;
		}

		$topic_data['content'] = $addinfo['content'];
		$topic_id = D('topic_data')->add($topic_data);
		if ($topic_id) {
			$list_data['title'] = $addinfo['title'];
			if (empty($addinfo['intro'])) {
				$list_data['intro'] = $addinfo['content'];//暂不截取
			}else{
				$list_data['intro'] = $addinfo['intro'];
			}
			$list_data['uid'] = $addinfo['uid'];
			$list_data['type'] = 1;//1-话题
			$list_data['data_id'] = $topic_id;
			$list_data['ctime'] = time();

			$list_id = D('list')->add($list_data);
			if ($list_id) {
				if ($addinfo['tag']) {
					$taglist = explode(',', $addinfo['tag']);
					foreach ($taglist as $key => $value) {
						$_tag['list_id'] = $list_id;
						$_tag['tag_id'] = $value;

						D('topic_tag_link')->add($_tag);
						unset($_tag);
					}
				}	
				$returninfo['list_id'] = $list_id;
				$returninfo['status'] = 1;//添加完毕
				return $returninfo;
			}else{
				$returninfo['msg'] = '数据添加错误';
				return $returninfo;
			}
		}else{
			$returninfo['msg'] = '内容添加错误';
			return $returninfo;
		}
	}

	//////////////////////////////试着用用laravel/////////////////////////////
	//话题标签
	public function getlistTag($list_id){
		$info = Model\ListData::find($list_id);
		return $info->toArray();
	}
	//项目频道
	public function getCatList(){
		$list = Model\ProjectCat::get();
		return $list->toArray();
	}
	//添加项目
	public function addProject($addinfo){
		$returninfo['status'] = 0;
		if (empty($addinfo['category_id'])) {
			$returninfo['msg'] = '请选择分类';
			return $returninfo;
		}
		if (empty($addinfo['amount'])) {
			$returninfo['msg'] = '请输入筹款金额';
			return $returninfo;
		}
		if (empty($addinfo['use'])) {
			$returninfo['msg'] = '请填写筹款用途';
			return $returninfo;
		}		
		if (empty($addinfo['etime'])||$addinfo['etime']<time()) {
			$returninfo['msg'] = '请输入正确的截止日期';
			return $returninfo;
		}
		if (empty($addinfo['stime'])||$addinfo['stime']<time()) {
			$returninfo['msg'] = '请输入正确的开始日期';
			return $returninfo;
		}
		if (empty($addinfo['title'])) {
			$returninfo['msg'] = '请输入标题';
			return $returninfo;
		}
		if (empty($addinfo['content'])) {
			$returninfo['msg'] = '请输入内容';
			return $returninfo;
		}
		if (empty($addinfo['cover'])) {
			$returninfo['msg'] = '请上传封面';
			return $returninfo;
		}
		$add_project_data = array(
			'category_id'	=>	$addinfo['category_id'],
			'amount'		=>	$addinfo['amount'],
			'use'			=>	$addinfo['use'],
			'content'		=>	$addinfo['content'],
			'etime'			=>	$addinfo['etime'],
			'stime'			=>	$addinfo['stime'],
			'ctime'			=>	time(),
			'image'			=>	$addinfo['photos'],
		);
		$add_list['data_id'] = Model\Project::insertGetId($add_project_data);
		if ($add_list['data_id']) {
			$add_list['title'] = $addinfo['title'];
			if (empty($addinfo['intro'])) {
				$add_list['intro'] = $addinfo['content'];//暂不截取
			}else{
				$add_list['intro'] = $addinfo['intro'];
			}
			$add_list['cover'] = $addinfo['cover'];
			$add_list['uid'] = $addinfo['uid'];
			$add_list['type'] = 2;
			$add_list['ctime'] = time();

			$list_id = Model\ListData::insertGetId($add_list);
			if ($list_id) {
				$returninfo['status'] = 1;
				$returninfo['list_id'] = $list_id;
				return $returninfo;
			}else{
				$returninfo['msg'] = '服务器错误';
				return $returninfo;
			}

		}else{
			$returninfo['msg'] = '更新数据错误';
			return $returninfo;
		}

	}
	// //发现列表
	// public function getList($type = 1,$lastId){
	// 	switch ($type) {
	// 		case 1:
	// 			$list = Model\ListData::where('is_hot',1)->skip($lastId)->take(10)->get()->toArray();
	// 			break;
	// 		case 2:
	// 			$list = Model\ListData::where('is_recommend',1)->skip($lastId)->take(10)->get()->toArray();
	// 			break;
	// 	}
	// 	if (empty($list)) {
	// 		return false;
	// 	}
	// 	foreach ($list as $key => $value) {
	// 		$_returninfo['is_collection'] = isCollection($this->mid,$value['list_id']);

	// 		$_returninfo['list_id'] = $value['list_id'];
	// 		$_returninfo['title'] = $value['title'];
	// 		$_returninfo['intro'] = $value['intro'];
	// 		$_returninfo['cover'] = getImageUrl($value['cover']);
	// 		$_returninfo['uid'] = $value['uid'];
	// 		$_returninfo['uname'] = getUserName($value['uid']);
	// 		$_returninfo['avatar'] = getUserFace($value['uid']);
	// 		$_returninfo['is_hot'] = $value['is_hot'];
	// 		$_returninfo['is_recommend'] = $value['is_recommend'];
	// 		$_returninfo['ctime'] = friendlyDate($value['ctime']);

	// 		$_returninfo['comment'] = DB::table('comment')->where('type','1')
	// 		->where('row_id',$value['list_id'])
	// 		->where('table','list')
	// 		->where('to_comment_id','0')
	// 		->count();
	// 		$_returninfo['collection'] = DB::table('collection')->where('source_id',$value['list_id'])
	// 		->where('source_app','project')
	// 		->where('source_table_name','list')
	// 		->count();

	// 		$returninfo[] = $_returninfo;
	// 		unset($_returninfo);
	// 	}
	// 	return $returninfo;
	// }
	
	//项目列表
	public function getProjectList($catid,$uid,$limit){
		$list = Model\ListData::join('project_data','list.data_id','=','project_data.data_id')
		->where('list.type','2')
		->where('project_data.category_id',$catid)
		->skip($limit['skip'])
		->take($limit['take'])
		->get()->toArray();
		foreach ($list as $key => &$value) {
			$attach = D('Attach')->getAttachById($value['cover']);
			$_return['cover'] = UPLOAD_URL.'/'.$attach['save_path'].$attach['save_name'];
			$_return['list_id'] = $value['list_id'];
			$_return['is_collection'] = isCollection($value['list_id'],$uid);
			$_return['is_digg'] = isDigg($value['list_id'],$uid);
			$_return['intro'] = $value['intro'];
			$_return['title'] = $value['title'];
			$_return['comment'] = Model\Comment::where('to_comment_id',0)
			->where('row_id',$value['list_id'])
			->where('table','list')
			->orderBy('ctime','desc')
			->count();
			$_return['support_num'] =  D('order_log')->where(array('list_id'=>$value['list_id']))->count();
			$_return['digg'] = D('list_digg')->where(array('list_id'=>$value['list_id']))->count();
			$_return['uid'] = $value['uid'];
			$_return['uname'] = getUserName($value['uid']);
			$_return['avatar'] = getUserFace($value['uid']);
			$_return['ctime'] = friendlyDate($value['ctime']);

			$return[] = $_return;
			unset($_return);
			unset($attach);
		}
		return $return;
	}
	
	//话题列表
	public function getTopicList($type,$uid,$limit){
		switch ($type) {
			case '1':
				$list = Model\ListData::where('type','1')
				->skip($limit['skip'])
				->take($limit['take'])
				->orderBy('ctime','desc')
				->get()->toArray();
				break;//全部
			case '2':
				$list = Model\ListData::where('type','1')
				->where('is_hot',1)
				->skip($limit['skip'])
				->take($limit['take'])
				->orderBy('ctime','desc')
				->get()->toArray();
				break;//热门
			case '3':
				$list = Model\ListData::where('type','1')
				->where('is_recommend',1)
				->skip($limit['skip'])
				->take($limit['take'])
				->orderBy('ctime','desc')
				->get()->toArray();
				break;//推荐
		}
		foreach ($list as $key => &$value) {
			$_return['list_id'] = $value['list_id'];
			$_return['is_collection'] = isCollection($value['list_id'],$uid);
			$_return['is_digg'] = isDigg($value['list_id'],$uid);
			$_return['intro'] = $value['intro'];
			$_return['title'] = $value['title'];
			$_return['comment'] = Model\Comment::where('to_comment_id',0)
			->where('row_id',$value['list_id'])
			->where('table','list')
			->count();
			$_return['support_num'] =  D('order_log')->where(array('list_id'=>$value['list_id']))->count();
			$_return['content'] = Model\ListData::find($value['list_id'])->topic()->pluck('content');
			$_return['digg'] = D('list_digg')->where(array('list_id'=>$value['list_id']))->count();
			$_return['uid'] = $value['uid'];
			$_return['uname'] = getUserName($value['uid']);
			$_return['avatar'] = getUserFace($value['uid']);
			$_return['ctime'] = friendlyDate($value['ctime']);
			$tags = Model\TagLink::where('list_id',$value['list_id'])->get();
			foreach ($tags as $tag) {
				$_tag = $tag->tag()->first()->toArray();
				$_return['tags'][] = $_tag;
				unset($_tag);
			}

			$return[] = $_return;
			unset($_return);
		}
		return $return;
	}

	//点赞  已有则取消
	public function doDigg($id,$uid,$type){
		switch ($type) {
			case 'list':
				$map['list_id'] = $id;
				$map['uid'] = $uid;
				$info = D('list_digg')->where($map)->find();

				$listinfo = D('list')->where(array('list_id'=>$id))->find();

				if (!$info) {
					$map['ctime'] = time();
					$do = D('list_digg')->add($map);

					$this->addLog(2,$listinfo['type'],$id,$uid);//添加记录
					D('ProjectMessage')->addMessage($uid,$listinfo['uid'],2,$id,'list');//给对方发送消息
					if ($do) {
						return array(
							'status'	=>	1,
							'msg'		=>	'点赞成功',
						);
					}else{
						return array(
							'status'	=>	0,
							'msg'		=>	'点赞失败',
						);
					}
				}else{
					$do = D('list_digg')->where($map)->delete();

					$logmap['uid'] = $uid;
					$logmap['project'] = $listinfo['type'];
					$logmap['link_id'] = $id;
					$logmap['type'] = 2;
					D('user_log')->where($logmap)->delete();//删除记录

					$messagemap['table'] = 'list';
					$messagemap['uid'] = $uid;
					$messagemap['to_uid'] = $listinfo['uid'];
					$messagemap['link_id'] = $id;
					$messagemap['type'] = 2;
					D('user_message')->where($messagemap)->delete();//删除消息

					if ($do) {
						return array(
							'status'	=>	1,
							'msg'		=>	'已取消点赞',
						);
					}else{
						return array(
							'status'	=>	0,
							'msg'		=>	'操作失败',
						);
					}
				}

				break;
			case 'comment'://点赞评论不做记录
				$map['comment_id'] = $id;
				$map['uid'] = $uid;
				$info = D('comment_digg')->where($map)->find();

				$commentinfo = D('comment')->where(array('comment_id'=>$id))->find();

				if (!$info) {
					$map['ctime'] = time();
					$do = D('comment_digg')->add($map);

					D('ProjectMessage')->addMessage($uid,$commentinfo['uid'],2,$id,'comment');//给对方发送消息

					if ($do) {
						return array(
							'status'	=>	1,
							'msg'		=>	'点赞成功',
						);
					}else{
						return array(
							'status'	=>	0,
							'msg'		=>	'点赞失败',
						);
					}
				}else{
					$do = D('comment_digg')->where($map)->delete();
					$messagemap['table'] = 'comment';
					$messagemap['uid'] = $uid;
					$messagemap['to_uid'] = $commentinfo['uid'];
					$messagemap['link_id'] = $id;
					$messagemap['type'] = 2;
					D('user_message')->where($messagemap)->delete();//删除消息
					if ($do) {
						return array(
							'status'	=>	1,
							'msg'		=>	'已取消点赞',
						);
					}else{
						return array(
							'status'	=>	0,
							'msg'		=>	'操作失败',
						);
					}
				}
				break;
			default:
				return array(
					'status'	=>	0,
					'msg'		=>	'参数错误'
				);
				break;
		}
	}

	//对账
	public function Reconciliation($uid){

	}

	//资产记录
	public function getProjectLogs($uid,$limit){

	}

	//参与记录
	public function getLogs($type,$uid,$limit){
		if ($type == 1) {
			$map['project'] = 1;//话题
		}else{
			$map['project'] = 2;//项目
		}

		$map['uid'] = $uid;
		$list = D('user_log')->where($map)->order('ctime desc')->limit($limit)->select();
		if (empty($list)) {
			return false;
		}
		foreach ($list as $key => $value) {
			$_return['link_id'] = $value['link_id'];
			$_return['type'] = $value['type'];
			$listinfo = D('list')->where(array('list_id'=>$value['link_id']))->find();
			if (!empty($listinfo['cover'])) {
				$attach = D('Attach')->getAttachById($listinfo['cover']);
				$_return['cover'] = UPLOAD_URL.'/'.$attach['save_path'].$attach['save_name'];
			}
			$_return['title'] = $listinfo['title'];
			$_return['intro'] = $listinfo['intro'];
			$_return['uname'] = getUserName($value['uid']);
			$_return['avatar'] = getUserFace($value['uid']);
			$_return['ctime'] = friendlyDate($value['ctime']);
			$_return['is_collection'] = isCollection($value['link_id'],$uid);
			$_return['is_digg'] = isDigg($value['link_id'],$uid);
			$_return['comment'] = Model\Comment::where('to_comment_id',0)
			->where('row_id',$value['link_id'])
			->where('table','list')
			->count();
			$_return['digg'] = D('list_digg')->where(array('list_id'=>$value['link_id']))->count();
			if ($value['type'] == 1) {
				$commentinfo = D('comment')->where(array('comment_id'=>$link_id))->find();
				$_return['comment_info'] = $commentinfo['content'];
			}

			$return[] = $_return;
			unset($_return); 
		}
		return $return;

	}

	//添加记录
	public function addLog($type,$project,$link_id,$uid){
		if (!empty($type)&&!empty($link_id)&&!empty($uid)) {
			$addinfo['type'] = intval($type);//1-评论 2-点赞  3-支持
			$addinfo['project'] = intval($project);
			$addinfo['link_id'] = intval($link_id);
			$addinfo['uid'] = intval($uid);
			$addinfo['ctime'] = time();

			$log_id = D('user_log')->add($addinfo);
			return $log_id;
		}
	}

	//列表详情
	public function showDetail($list_id,$uid){
		$map['list_id'] = $list_id;
		$info = D('list')->where($map)->find();
		if (empty($info)) {
			return false;
		}
		if (!empty($info['cover'])) {
			$attach = D('Attach')->getAttachById($info['cover']);
			$return['cover'] = UPLOAD_URL.'/'.$attach['save_path'].$attach['save_name'];
		}
		$return['uname'] = getUserName($info['uid']);
		$return['avatar'] = getUserFace($info['uid']);
		$return['title'] = $info['title'];
		$return['content'] = $info['intro'];
		$return['ctime'] = friendlyDate($info['ctime']);
		$return['is_collection'] = isCollection($info['list_id'],$uid);
		$return['is_digg'] = isDigg($info['list_id'],$uid);
		$return['comment'] = Model\Comment::where('to_comment_id',0)
		->where('row_id',$info['list_id'])
		->where('table','list')
		->count();
		$return['digg'] = D('list_digg')->where(array('list_id'=>$info['list_id']))->count();
		$return['url'] = U('w3g/Project/showProject',array('list_id'=>$info['list_id']));
		$return['support_num'] =  D('order_log')->where(array('list_id'=>$info['list_id']))->count();
		$order_logs = D('order_log')->where(array('list_id'=>$info['list_id']))->limit(3)->select();
		if (!empty($order_logs)) {
			foreach ($order_logs as $key => &$value) {
				$_user['uid'] = $value['uid'];
				$_user['uname'] = getUserName($value['uid']);
				$_user['avatar'] = getUserFace($value['uid']);

				$return['support_user'][] = $_user;
				unset($_user);
			}
		}

		// $commentmap['table'] = 'list';
		// $commentmap['row_id'] = $list_id;
		// $comment = D('Comment')->getCommentList($commentmap,'comment_id desc',4,true);
		// if (empty($comment['data'])) {
		// 	$return['commentlist'] = $comment['data'];
		// }
		if ($info['type'] == 1) {
			$tags = Model\TagLink::where('list_id',$info['list_id'])->get();
			foreach ($tags as $tag) {
				$_tag = $tag->tag()->first()->toArray();
				$return['tags'][] = $_tag;
				unset($_tag);
			}
		}else{
			$data = D("project_data")->where(array('data_id'=>$info['data_id']))->find();
			$sum = D('order_sum')->where(array('list_id'=>$info['list_id']))->find();
			$return['stime'] = friendlyDate($data['stime']);
			$return['etime'] = friendlyDate($data['etime']);
			$return['days_sum'] = intval(($data['etime']-$data['stime'])/(3600*24));//总共天数
			if ((time()-$data['stime'])>0) {
				$return['remain'] = intval((time()-$data['stime'])/(3600*24));//剩余天数
			}else{
				$return['remain'] = $return['days_sum'];
			}	
			
			$return['amount'] = $data['amount'];
			$return['use'] = $data['use'];
			if (empty($sum)) {
				$return['sum'] = 0;
			}else{
				$return['sum'] = $sum['sum'];
			}
		}


		return $return;
	}


	//获取全部支持者
	public function getAllSupportUser($list_id,$limit){
		$order_logs = D('order_log')->where(array('list_id'=>$list_id))->limit($limit)->select();
		if (!empty($order_logs)) {
			foreach ($order_logs as $key => &$value) {
				$_user['uid'] = $value['uid'];
				$_user['uname'] = getUserName($value['uid']);
				$_user['avatar'] = getUserFace($value['uid']);
				$_user['ctime'] = friendlyDate($value['ctime']);
				$return[] = $_user;
				unset($_user);
			}
		}
		return $return;
	}

	//获取首页数据
	public function getHomeData(){
		$return['banner'] = D('application_slide')->select(); 
		foreach ($return['banner'] as $key => &$value) {
			$attach = D('Attach')->getAttachById($info['image']);
			$value['image'] = UPLOAD_URL.'/'.$attach['save_path'].$attach['save_name'];
		}
		$list = D('list')->where(array('type'=>2))->order('is_top desc,ctime desc')->limit($limit)->select();
		foreach ($list as $key => &$value) {
			$attach = D('Attach')->getAttachById($value['cover']);
			$_return['cover'] = UPLOAD_URL.'/'.$attach['save_path'].$attach['save_name'];
			$_return['list_id'] = $value['list_id'];
			$_return['is_collection'] = isCollection($value['list_id'],$uid);
			$_return['is_digg'] = isDigg($value['list_id'],$uid);
			$_return['intro'] = $value['intro'];
			$_return['title'] = $value['title'];
			$_return['comment'] = Model\Comment::where('to_comment_id',0)
			->where('row_id',$value['list_id'])
			->where('table','list')
			->orderBy('ctime','desc')
			->count();
			$_return['support_num'] =  D('order_log')->where(array('list_id'=>$value['list_id']))->count();
			$_return['digg'] = D('list_digg')->where(array('list_id'=>$value['list_id']))->count();
			$_return['uid'] = $value['uid'];
			$_return['uname'] = getUserName($value['uid']);
			$_return['avatar'] = getUserFace($value['uid']);
			$_return['ctime'] = friendlyDate($value['ctime']);

			$return['list'][] = $_return;
			unset($_return);
			unset($attach);
		}

		return $return;
	}
}