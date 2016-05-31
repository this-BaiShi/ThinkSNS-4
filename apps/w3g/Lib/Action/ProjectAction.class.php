<?php
/**
* 项目详情页
*/
class ProjectAction extends Action
{
		    //项目详细页
    public function showProject(){
        $list_id = intval($this->data['list_id']);
        $listinfo = D('list')->where(array('list_id'=>$list_id))->find();
        $datainfo = D('project')->where(array('data_id'=>$listinfo['data_id']))->find();

        
        $this->display('project_detail');
    }
}