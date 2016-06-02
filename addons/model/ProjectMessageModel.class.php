<?php
/**
* 项目/话题 消息模型
*/
class ProjectMessageModel extends Model
{
	//添加消息
	public function addMessage($uid,$to_uid,$type,$link_id,$table){
		$addinfo['uid'] = $uid;
		$addinfo['to_uid'] = $to_uid;
		$addinfo['type'] = $type;//1-评论 2-赞 3-支持
		$addinfo['is_read'] = 0;
		$addinfo['link_id'] = $link_id;
		$addinfo['table'] = $table;
		$addinfo['ctime'] = time();

		$return = D('user_message')->add($addinfo);
		return $return;
	}

	//获取消息未读数量
	public function getNotRead($uid){
		$return['sum'] = D('user_message')->where(array('to_uid'=>$uid,'is_read'=>0))->count();
		$return['comment_sum'] = D('user_message')->where(array('to_uid'=>$uid,'is_read'=>0,'type'=>1))->count();
		$return['digg_sum'] = D('user_message')->where(array('to_uid'=>$uid,'is_read'=>0,'type'=>2))->count();
		$return['suppost_sum'] = D('user_message')->where(array('to_uid'=>$uid,'is_read'=>0,'type'=>3))->count();

		return $return;

	}

	//消息列表
	public function getMessage($type,$limit,$uid){
		$map['type'] = $type;
		$map['to_uid'] = $uid;

		$list = D('user_message')->where($map)->limit($limit)->select();
		if (empty($list)) {
			return false;
		}
		foreach ($list as $key => $value) {
			$_return['uname'] = getUserName($value['uid']);
			$_return['avatar'] = getUserFace($value['uid']);
			$_return['uid'] = $value['uid'];
			$_return['is_read'] = $value['is_read'];
			$_return['ctime'] = friendlyDate($value['ctime']);
			$_return['link_id'] = $value['link_id'];
			$_return['table'] = $value['table'];
			switch ($value['table']) {
				case 'comment':
					$info = D('comment')->where(array('comment_id'=>$value['link_id']))->find();
					$list_info = D('list')->where(array('list_id'=>$info['row_id']))->find();
					$_return['list_id'] = $list_info['list_id'];
					$_return['comment_content'] = $info['content'];
					$_return['title'] = $list_info['title'];
					$_return['content'] = $list_info['intro'];
					break;
				case 'list':
					$info = D('list')->where(array('list_id'=>$value['link_id']))->find();
					$_return['list_id'] = $info['list_id'];
					$_return['title'] = $info['title'];
					$_return['content'] = $info['intro'];
					break;
				default:
					# code...
					break;
			}

			$return[] = $_return;
			unset($_return);
		}
		return $return;
	}

}