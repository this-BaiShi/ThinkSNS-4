<?php
/**
* 项目/话题评论 相关模型
*/
class ProjectCommentModel extends Model
{       
        //评论列表
        public function getCommentList($list_id,$limit,$uid){
                $map['row_id'] = $list_id;
                $map['app'] = 'project';
                $map['table'] = 'list';
                $map['to_comment_id'] = 0;

                $list = D('comment')->where($map)->limit($limit)->order('ctime desc')->select();
                if (empty($list)) {
                        return false;
                }
                foreach ($list as $key => &$value) {
                        $_return['comment_id'] = $value['comment_id'];
                        $_return['content'] = $value['content'];
                        $_return['uid'] = $value['uid'];
                        $_return['uname'] = getUserName($value['uid']);
                        $_return['avatar'] = getUserFace($value['uid']);
                        $_return['ctime'] = friendlyDate($value['ctime']);
                        $_return['count'] = D('comment')->where(array('to_comment_id'=>$value['comment_id']))->count();
                        $_return['digg'] = D('comment_digg')->where(array('comment_id'=>$value['comment_id']))->count();
                        $_return['is_digg'] = isDigg($value['comment_id'],$uid,'comment');
                        $child = D('comment')->where(array('to_comment_id'=>$value['comment_id']))->limit(4)->select();//楼中楼回复默认显示四条
                        foreach ($child as $key2 => &$value2) {
                                $_child['comment_id'] = $value2['comment_id'];
                                $_child['uid'] = $value2['uid'];
                                $_child['to_uid'] = $value2['to_uid'];
                                $_child['content'] = $value2['content'];
                                $_child['ctime'] = friendlyDate($value2['ctime']);
                                $_child['uname'] = getUserName($value2['uid']);
                                $_child['avatar'] = getUserFace($value2['uid']);
                                $_child['to_uname'] = getUserName($value2['to_uid']);
                                $_child['to_avatar'] = getUserFace($value2['to_uid']);
                                $_return['childlist'][] = $_child;
                                unset($_child);
                        }
                        $return[] = $_return;
                        unset($_return);
                }
                return $return;
        }

        //查看一条评论的详细信息 
        public function getCommentInfo($comment_id,$limit,$uid){
                $info = D('comment')->where(array('comment_id'=>$comment_id))->find();
                $return['comment_id'] = $info['comment_id'];
                $return['content'] = $info['content'];
                $return['uid'] = $info['uid'];
                $return['uname'] = getUserName($info['uid']);
                $return['avatar'] = getUserFace($info['uid']);
                $return['ctime'] = friendlyDate($info['ctime']);
                $return['count'] = D('comment')->where(array('to_comment_id'=>$info['comment_id']))->count();
                $return['digg'] = D('comment_digg')->where(array('comment_id'=>$info['comment_id']))->count();
                $return['is_digg'] = isDigg($info['comment_id'],$uid,'comment');
                $child = D('comment')->where(array('to_comment_id'=>$info['comment_id']))->order('ctime desc')->limit($limit)->select();//楼中楼回复默认显示四条
                foreach ($child as $key2 => &$value2) {
                        $_child['comment_id'] = $value2['comment_id'];
                        $_child['uid'] = $value2['uid'];
                        $_child['to_uid'] = $value2['to_uid'];
                        $_child['content'] = $value2['content'];
                        $_child['ctime'] = friendlyDate($value2['ctime']);
                        $_child['uname'] = getUserName($value2['uid']);
                        $_child['avatar'] = getUserFace($value2['uid']);
                        $_child['to_uname'] = getUserName($value2['to_uid']);
                        $_child['to_avatar'] = getUserFace($value2['to_uid']);
                        $return['childlist'][] = $_child;
                        unset($_child);
                }

                return $return;        
        } 
}