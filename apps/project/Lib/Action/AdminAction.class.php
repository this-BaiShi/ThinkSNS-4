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
		$this->pageTab[] = array('title'=>'提现申请','tabHash'=>'deallist','url'=>U('project/Admin/deallist'));
		$this->pageTab[] = array('title'=>'回收站','tabHash'=>'recycle','url'=>U('project/Admin/recycle'));
	}

	//话题列表
	public function index(){
		$this->_initTab();
		$data = $this->model->getlist(1);
		$this->pageKeyList = array('list_id','title','ctime','is_hot','is_recommend','DOCATION');
		foreach ($data['data'] as $key => &$value) {
			$value['ctime'] = friendlyDate($value['ctime']);
			$value['is_hot'] = $value['is_hot'] == 1 ? "<a href='".U('admin/Administrator/changeStatus',array('id'=>$value['list_id'],'table'=>'list','key'=>'list_id','field'=>'is_hot'))."' >热门</a>" : "<a href='".U('admin/Administrator/changeStatus',array('id'=>$value['list_id'],'table'=>'list','key'=>'list_id','field'=>'is_hot'))."' >非热门</a>";
			$value['is_recommend'] = $value['is_recommend'] == 1 ? "<a href='".U('admin/Administrator/changeStatus',array('id'=>$value['list_id'],'table'=>'list','key'=>'list_id','field'=>'is_recommend'))."' >推荐</a>" : "<a href='".U('admin/Administrator/changeStatus',array('id'=>$value['list_id'],'table'=>'list','key'=>'list_id','field'=>'is_recommend'))."' >非推荐</a>";
			$value['DOCATION'] = '<a href='.U('project/Admin/edit_list',array('list_id'=>$value['list_id'],'tabHash'=>'index')).'>编辑</a>';

		}


		$this->displayList($data);
	}

	//项目列表
	public function project(){
		$this->_initTab();
		$data = $this->model->getlist(2);
		$this->pageKeyList = array('list_id','title','ctime','is_top','DOCATION');
		foreach ($data['data'] as $key => &$value) {
			$value['ctime'] = friendlyDate($value['ctime']);
			$value['is_top'] = $value['is_top'] == 1 ? "<a href='".U('admin/Administrator/changeStatus',array('id'=>$value['list_id'],'table'=>'list','key'=>'list_id','field'=>'is_top'))."' >首页</a>" : "<a href='".U('admin/Administrator/changeStatus',array('id'=>$value['list_id'],'table'=>'list','key'=>'list_id','field'=>'is_top'))."' >非首页</a>";
			$value['DOCATION'] = '<a href='.U('project/Admin/edit_list',array('list_id'=>$value['list_id'],'tabHash'=>'project')).'>编辑</a>';

		}
		$this->displayList($data);
	}
	//标签列表
	public function taglist(){
		$this->_initTab();
		$this->pageButton[] = array('title'=>'添加标签','onclick'=>"admin.add_tag()");	
		$this->pageKeyList = array('tag_id','tag_name','style','DOCATION');

		$list['data'] = $this->model->getTag(); 
		foreach ($list['data'] as $key => &$value) {
			$value['DOCATION'] = '<a href='.U('project/Admin/edit_tag',array('tag_id'=>$value['tag_id'],'tabHash'=>'taglist')).'>编辑</a>';
			$value['DOCATION'] .= '|<a href='.U('project/Admin/delTag',array('tag_id'=>$value['tag_id'])).'>删除</a>';
		}


		$this->displayList($list);
	}

	//添加/修改标签
	public function edit_tag(){
		$this->_initTab();
		$this->pageKeyList = array('tag_id','tag_name','style');
		if ($_GET['tag_id']) {
			$data = D('topic_tag')->where(array('tag_id'=>$_GET['tag_id']))->find();
		}
		if ($_POST) {
			$add['tag_name'] = $_POST['tag_name'];
			$add['style'] = $_POST['style'];
			if ($_POST['tag_id']) {
				$do = D('topic_tag')->where(array('tag_id'=>$_POST['tag_id']))->save($add);
			}else{
				$do = D('topic_tag')->add($add);
			}
			if ($do) {
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
		}
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

	//编辑
	public function edit_list(){
		$this->_initTab();
		$this->pageKeyList = array('list_id','title','intro');
		if (empty($_GET['list_id'])) {
			$this->error('参数错误');
		}
		$list_id = intval($_GET['list_id']);
		$data = $this->model->getData($list_id);

		$data['stime'] = date("Y-m-d H:i:s",$data['stime']);
		$data['etime'] = date("Y-m-d H:i:s",$data['etime']);

		$this->opt['category_id'] = $this->catlist();
		$this->savePostUrl = U('project/Admin/saveTopic');
		if ($data['type'] == 2) {
			$this->pageKeyList = array_merge($this->pageKeyList,array('etime','stime','cover','category_id','amount','use'));
			$this->savePostUrl = U('project/Admin/saveProject');
		}

		$this->displayConfig($data);
	}

	private function catlist(){
		$cat = D('project_category')->select();
		foreach ($cat as $key => $value) {
			$return[$value['category_id']] = $value['name'];
		}
		return $return;
	}

	public function saveTopic(){
		if ($_POST) {
			if ($_POST['list_id']) {
				$add['title'] = $_POST['title'];
				$add['intro'] = $_POST['intro'];
				$do = D('list')->where(array('list_id'=>$_POST['list_id']))->save($add);
				$list = D('list')->where(array('list_id'=>$_POST['list_id']))->find();
				$adddata['content'] = $_POST['intro'];
				D('topic_data')->where(array('data_id'=>$list['data_id']))->save($adddata);
				if ($do) {
					$this->success('保存成功');
				}else{
					$this->error('操作失败');
				}
			}
		}
	}

	public function saveProject(){
		if ($_POST) {
			$list = D('list')->where(array('list_id'=>$_POST['list_id']))->find();
			$addlist['intro'] = $_POST['intro'];
			$addlist['title'] = $_POST['title'];
			$addlist['cover'] = $_POST['cover'];

			// $adddata['image'] = str_replace('|', ',', substr($_POST['image_ids'], 1,-1));//附件上传有问题
			$adddata['category_id'] = $_POST['category_id'];
			$adddata['amount'] = $_POST['amount'];
			$adddata['use'] = $_POST['use'];
			$adddata['stime'] = strtotime($_POST['stime']);
			$adddata['etime'] = strtotime($_POST['etime']);

			$do1 = D('list')->where(array('list_id'=>$_POST['list_id']))->save($addlist);
			$do2 = D('project_data')->where(array('data_id'=>$list['data_id']))->save($adddata);
			$this->success('保存成功');
		}
	}

	public function deallist(){
		$this->_initTab();
		$this->pageKeyList = array('apply_id','name','phone','status','ctime','DOCATION');
		$list = D('user_apply')->findpage(20);
		foreach ($list['data'] as $key => &$value) {
			switch ($value['status']) {
				case '0':
					$value['status'] = "<span  style= 'color:orange;'>待处理</span>";
					break;
				case '1':
					$value['status'] = "<span class = 'color:green;'>已处理</span>";
					break;
				case '2':
					$value['status'] = "<span class = 'color:red;'>已拒绝</span>";
					break;
			}
			$value['ctime'] = friendlyDate($value['ctime']);
			$value['DOCATION'] = '<a href='.U('project/Admin/edit_tag',array('tag_id'=>$value['tag_id'],'tabHash'=>'taglist')).'>对账</a>';
			$value['DOCATION'] .= '|<a href='.U('project/Admin/delTag',array('tag_id'=>$value['tag_id'])).'>处理</a>';
		}
		$this->displayList($list);
	}

	public function recycle(){
		$this->_initTab();
		$this->pageKeyList = array('list_id','title','content','ctime');
		$list = D('list')->where(array('is_del'=>1))->findPage(20);
		foreach ($list['data'] as $key => &$value) {
			$value['ctime'] = friendlyDate($value['ctime']);
			$value['content'] = msubstr($value['content'],0,10);
		}

		$this->displayList($list);	
	}
}