<?php
/**
* 用户个人糗事查询调用
* 查询第一个参数为 糗事id可查询多个
* 查询第二个参数为 用户id 所属糗事
**/
function sp_sql_qiushi($ids,$uid){
    $where = array();
	//根据参数生成查询条件
	$where['id'] = !empty($ids)?$ids:'';
	$where['uid'] = !empty($uid)?$uid:'';
	
	$qiushi_result = M("Qiushi")->where(" id in (".$where['id'].") and uid =".$where['uid'])->select();
	return 	$qiushi_result;
}




