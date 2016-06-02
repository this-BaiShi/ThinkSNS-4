<?php
/**
* 项目详情页
*/
class ProjectAction extends Action
{
		    //项目详细页
    public function showProject(){
        $list_id = intval($_REQUEST['list_id']);
        $listinfo = D('list')->where(array('list_id'=>$list_id))->find();
        $datainfo = D('project_data')->where(array('data_id'=>$listinfo['data_id']))->find();

        $content = $datainfo['content']."<style type='text/css'>img{padding:0 0 5px 0;}</style>";
        $content = contentApi($content);
        $ids = explode(',', $datainfo['image']);
        if (!empty($ids)) {
	        $attach = D("Attach")->getAttachByIds($ids);
	        foreach ($attach as $key => $value) {
	        	$path = UPLOAD_URL.'/'.$value['save_path'].$value['save_name'];
	        	$img = "<img src= '".$path."'/>";
	        	$content = $img.$content;
	        	unset($path);
	        	unset($img);
	        }	
        }
        $this->assign('content',$content);
        $this->display('project_detail');
    }
}