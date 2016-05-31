<?php

//接口数据统一返回格式
function rd($data = '',$message='成功',$status=1){
    return array(
        'data'      =>  $data,
        'msg'   =>  $message,
        'status'    =>  $status,
    );
}

function rdnull(){
    return array(
        'data'      =>  '',
        'msg'       =>  '暂无相关数据',
        'status'    =>  0,
    );
}

//接口统一分页
function pg(){
    $page = !empty($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    $num = !empty($_REQUEST['num']) ? intval($_REQUEST['num']) : 10;

    $start = ($page-1)*$num;

    $limit = $start.','.$num;
    return $limit;
}



/*
    处理api端的富文本编辑内容
 */
function contentApi($content){

    //添加style
    $style = '<style type="text/css">p{text-indent:initial;font-family:"SimHei","Myriad Pro",Arial !important;font-color:#333333 !important;font-size:16px;line-height:30px;text-align:justify !important;text-justify: inter-ideograph !important;} div{word-wrap:break-word;word-break:break-all;} img{width:100%;} </style>';
    $content = $style.'<div>'.$content.'</div>';


    //为img标签补全url
    $pattern  = '/<[\s]*img[\s]+[^>]*?src[\s]*=[\s]*(\'|\")(.*?)\\1[^>]*?[\/]?[\s]*>/is';
    $content = preg_replace_callback ($pattern, function($matches){
                            return str_replace($matches['2'], SITE_URL.$matches['2'], $matches['0']);
                    }, $content);
    $content = preg_replace("#<a[^>]*>(.*?)</a>#is", "$1", $content);

    return $content;
}

function isCollection($id,$uid,$source_app='project',$source_table_name='list'){
	$map['source_id'] = $id;
	$map['source_app'] = $source_app;
	$map['source_table_name'] = $source_table_name;
	$map['uid'] = $uid;

	$info = D('collection')->where($map)->find();
	if ($info) {
		return 1;
	}else{
		return 0;
	}
}