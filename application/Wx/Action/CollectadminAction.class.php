<?php
namespace Wx\Action;
use Common\Action\AdminbaseAction;
class CollectadminAction extends AdminbaseAction {
	
	//发送地理位置的用户
	public function location(){
		$sql = 'select count(1) as c,province from __TABLE__ 
				where status = 1 
				group by province 
				order by c desc';
		$rst=M('WxUser')->query($sql);
		$this->assign('rst',$rst);
		$this->display();
	}
	
	//用户回复数量统计
	public function answer(){
		//时间段
		$today= strtotime(date('Y-m-d'));
		$ds= 3600*24;
		$time = array(
				'0'		=> $today,
				'-1'	=> $today-$ds*1,
				'-2'	=> $today-$ds*2,
				'-3'	=> $today-$ds*3,
				'-4'	=> $today-$ds*4,
				'-5'	=> $today-$ds*5,
				'-6'	=> $today-$ds*6,
		);
		$sql= "select (select count(1) from __TABLE__ WHERE time > ".$time['0'].") as c0,
				(select count(1) from __TABLE__  WHERE time < ".$time['0']." and time > ".$time['-1'].") as c1,
				(select count(1) from __TABLE__  WHERE time < ".$time['-1']." and time > ".$time['-2'].") as c2,
				(select count(1) from __TABLE__  WHERE time < ".$time['-2']." and time > ".$time['-3'].") as c3,
				(select count(1) from __TABLE__  WHERE time < ".$time['-3']." and time > ".$time['-4'].") as c4,
				(select count(1) from __TABLE__  WHERE time < ".$time['-4']." and time > ".$time['-5'].") as c5,
				(select count(1) from __TABLE__  WHERE time < ".$time['-5']." and time > ".$time['-6'].") as c6";
		$rst=M('WxMessageText')->query($sql);
		$this->assign('rst',$rst);
		$this->display();
	}
	
	//用户列表
	public function userlist(){
		$rst = M('WxUser')->where('status=1')->order("id desc")->select();
		$this->assign('rst', $rst);
		$this->display();
	}
	//最近一周的关注统计
	function users(){
		//时间段
		$today= strtotime(date('Y-m-d'));
		$ds= 3600*24;
		$time = array(
				'0'		=> $today,
				'-1'	=> $today-$ds*1,
				'-2'	=> $today-$ds*2,
				'-3'	=> $today-$ds*3,
				'-4'	=> $today-$ds*4,
				'-5'	=> $today-$ds*5,
				'-6'	=> $today-$ds*6,
		);
	
		$sql1= "select (select count(1) from __TABLE__ WHERE subscribe_time > ".$time['0'].") as c0,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['0']." and subscribe_time > ".$time['-1'].") as c1,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-1']." and subscribe_time > ".$time['-2'].") as c2,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-2']." and subscribe_time > ".$time['-3'].") as c3,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-3']." and subscribe_time > ".$time['-4'].") as c4,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-4']." and subscribe_time > ".$time['-5'].") as c5,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-5']." and subscribe_time > ".$time['-6'].") as c6";
		$rst['subscribe']=M('WxUser')->query($sql1);
		$sql2= "select (select count(1) from __TABLE__ WHERE unsubscribe_time > ".$time['0'].") as c0,
				(select count(1) from __TABLE__  WHERE unsubscribe_time < ".$time['0']." and unsubscribe_time > ".$time['-1'].") as c1,
				(select count(1) from __TABLE__  WHERE unsubscribe_time < ".$time['-1']." and unsubscribe_time > ".$time['-2'].") as c2,
				(select count(1) from __TABLE__  WHERE unsubscribe_time < ".$time['-2']." and unsubscribe_time > ".$time['-3'].") as c3,
				(select count(1) from __TABLE__  WHERE unsubscribe_time < ".$time['-3']." and unsubscribe_time > ".$time['-4'].") as c4,
				(select count(1) from __TABLE__  WHERE unsubscribe_time < ".$time['-4']." and unsubscribe_time > ".$time['-5'].") as c5,
				(select count(1) from __TABLE__  WHERE unsubscribe_time < ".$time['-5']." and unsubscribe_time > ".$time['-6'].") as c6";
		$rst['unsubscribe']=M('WxUser')->query($sql2);
		$sql3= "select (select count(1) from __TABLE__ WHERE status=1) as c0,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['0']." and (unsubscribe_time>".$time['-1']." or unsubscribe_time = 0)) as c1,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-1']." and (unsubscribe_time>".$time['-2']." or unsubscribe_time = 0)) as c2,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-2']." and (unsubscribe_time>".$time['-3']." or unsubscribe_time = 0)) as c3,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-3']." and (unsubscribe_time>".$time['-4']." or unsubscribe_time = 0)) as c4,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-4']." and (unsubscribe_time>".$time['-5']." or unsubscribe_time = 0)) as c5,
				(select count(1) from __TABLE__  WHERE subscribe_time < ".$time['-5']." and (unsubscribe_time>".$time['-6']." or unsubscribe_time = 0)) as c6";
		$rst['usercount']=M('WxUser')->query($sql3);
		$this->assign('rst', $rst);
		$this->display();
	}
}