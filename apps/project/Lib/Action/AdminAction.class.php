<?php

/**
* 项目/话题 后台管理
*/
tsload(APPS_PATH.'/admin/Lib/Action/AdministratorAction.class.php');
class AdminAction extends AdministratorAction
{	

	protected $model = '';
	public function _initialize(){
		$this->model = D('Project');
	}

	private function _initTab(){
		$this->pageTab[] = array('title'=>'话题列表','tabHash'=>'index','url'=>U('project/Admin/index'));
		$this->pageTab[] = array('title'=>'项目列表','tabHash'=>'project','url'=>U('project/Admin/project'));
		$this->pageTab[] = array('title'=>'话题标签','tabHash'=>'taglist','url'=>U('project/Admin/taglist'));
		$this->pageTab[] = array('title'=>'邀请列表','tabHash'=>'list','url'=>U('project/Admin/index'));
		$this->pageTab[] = array('title'=>'邀请列表','tabHash'=>'list','url'=>U('project/Admin/index'));
		$this->pageTab[] = array('title'=>'邀请列表','tabHash'=>'list','url'=>U('project/Admin/index'));
	}

	//话题列表
	public function index(){
		$this->_initTab();

		$this->displayList($data);
	}

	//标签列表
	public function taglist(){
		$this->_initTab();
		$this->pageButton[] = array('title'=>'添加标签','onclick'=>"admin.add_tag()");	
		$this->pageKeyList = array('tag_id','tag_name','style','DOCATION');

		$list['data'] = $this->model->getTag(); 
		foreach ($list['data'] as $key => &$value) {
			$value['DOCATION'] = '<a href='.U('project/Admin/delTag',array('tag_id'=>$value['tag_id'])).'>删除</a>';
			$value['DOCATION'] .= '<a href='.U('project/Admin/edit_tag',array('tag_id'=>$value['tag_id'])).'>编辑</a>';
		}


		$this->displayList($list);
	}

	//添加/修改标签
	public function edit_tag(){
		$this->pageKeyList = array('tag_name','style');


		$this->displayConfig($data);
	}

	//删除标签
	public function delTag(){
		$map['tag_id'] = intval($_GET['tag_id']);
		if (empty($map['tag_id'])) {
			$this->error('参数错误');
		}
		D('topic_tag')->where($map)->delete();
		$this->success('操作成功');
	}
}