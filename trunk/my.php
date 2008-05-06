<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'my');
require('./include/common.inc.php');
$command= empty($command) ? 'myscore': $command;

if($command=='myscore')
{
	$totalscore= get_score($cyask_uid);
	
	$query=$dblink->query("select count(*) from {$dbprefix}answer where uid=$cyask_uid");
	$answercount=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}answer where uid=$cyask_uid and adopttime<>0");
	$adoptcount=$dblink->result($query,0);
	
	if($answercount)
	{
		$rightvalage=$adoptcount/$answercount*100;
		$rightvalage=round($rightvalage).'%';
	}
	else
	{
		$rightvalage='0%';
	}
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$cyask_uid");
	$question_allcount=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$cyask_uid and status=2");
	$questionOK=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$cyask_uid and status=1");
	$questionASK=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$cyask_uid and status=3");
	$questionVOTE=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$cyask_uid and status=4");
	$questionCLOSE=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}collect where uid=$cyask_uid");
	$collectcount=$dblink->result($query,0);
	unset($query);
}
elseif($command=='myask')
{
	$page=intval($_GET['page']);
	if($page<1) $page=1;
	$pagerow=10;
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE uid=$cyask_uid");
	$quescount=$dblink->result($query,0);     
	$pagecount=ceil($quescount/$pagerow);
	if($page>$pagecount) $page=1;
	$pagestart=($page-1)*$pagerow;
	$query=$dblink->query("SELECT qid,title,status,score,asktime,answercount FROM {$dbprefix}ques where uid=$cyask_uid ORDER BY asktime desc LIMIT $pagestart,$pagerow");
	
	while($ques_temp=$dblink->fetch_array($query))
	{
		$ques_temp['stitle']=cut_str($ques_temp['title'],54);
		$ques_temp['asktime']=date("y-n-d",$ques_temp['asktime']);
		$ques_list[$ques_temp['qid']] = $ques_temp;
	}
	unset($query);
	$page_front	=$page-1;
	$page_next	=$page+1;
	$pagelinks = get_pages($page,$pagecount,'command='.$command);
}
elseif($command=='myoverdue')
{
	$page=intval($_GET['page']);
	if($page<1) $page=1;
	$pagerow=10;
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE uid=$cyask_uid AND endtime<$timestamp AND status IN (1,3)");
	$quescount=$dblink->result($query,0);     
	$pagecount=ceil($quescount/$pagerow);
	if($page>$pagecount) $page=1;
	$pagestart=($page-1)*$pagerow;
	$query=$dblink->query("SELECT qid,title,status,score,asktime,answercount FROM {$dbprefix}ques where uid=$cyask_uid AND endtime<$timestamp AND status IN (1,3) ORDER BY asktime desc LIMIT $pagestart,$pagerow");
	
	while($ques_temp=$dblink->fetch_array($query))
	{
		$ques_temp['stitle']=cut_str($ques_temp['title'],54);
		$ques_temp['asktime']=date("y-n-j",$ques_temp['asktime']);
		$ques_list[$ques_temp['qid']] = $ques_temp;
	}
	unset($query);
	$page_front	=$page-1;
	$page_next	=$page+1;
	$pagelinks = get_pages($page,$pagecount,'command='.$command);
}
elseif($command=='myanswer')
{
	$page=intval($_GET['page']);
	if($page<1) $page=1;
	$pagerow=10;
	$query=$dblink->query("select count(*) from {$dbprefix}answer where uid=$cyask_uid");
	$answercount=$dblink->result($query,0);     
	$pagecount=ceil($answercount/$pagerow);
	if($page>$pagecount) $page=1;
	$pagestart=($page-1)*$pagerow;
	$query=$dblink->query("SELECT a.aid,a.answertime,a.adopttime,a.response,q.title,q.status,q.score,q.answercount FROM {$dbprefix}answer AS a,{$dbprefix}ques AS q WHERE a.uid=$cyask_uid AND a.qid=q.qid ORDER BY a.answertime DESC LIMIT $pagestart,$pagerow");
	
	while($ques_temp=$dblink->fetch_array($query))
	{
		$ques_temp['stitle']=cut_str($ques_temp['title'],54);
		$ques_temp['answertime']=date("y-n-d",$ques_temp['answertime']);
		$ques_list[$ques_temp['aid']] = $ques_temp;
	}
	
	unset($query,$query2);
	$page_front	=$page-1;
	$page_next	=$page+1;
	$pagelinks = get_pages($page,$pagecount,'command='.$command);
}
elseif($command=='mycollect')
{
	$page=intval($_GET['page']);
	if($page<1) $page=1;
	$pagerow=10;
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}collect WHERE uid=$cyask_uid");
	$collectcount=$dblink->result($query,0);     
	$pagecount=ceil($collectcount/$pagerow);
	if($page>$pagecount) $page=1;
	$pagestart=($page-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}collect WHERE uid=$cyask_uid ORDER BY collecttime desc LIMIT $pagestart,$pagerow");
	$i=1;
	while($ques_temp=$dblink->fetch_array($query))
	{
		$ques_temp['stitle']=cut_str($ques_temp['title'],30);
		$ques_temp['surl']=cut_str($ques_temp['url'],30).'...';
		$ques_temp['time']=date("y-n-j",$ques_temp['collecttime']);
		$collect_list[$i] = $ques_temp;
		$i++;
	}
	unset($query);
	$page_front	=$page-1;
	$page_next	=$page+1;
	$pagelinks = get_pages($page,$pagecount,'command='.$command);
}
elseif($command=='mymessage')
{
	$page=intval($_GET['page']);
	$msgtype=empty($_GET['msgtype']) ? 'receive' :$_GET['msgtype'];
	if($page<1) $page=1;
	$pagerow=10;
	$msg_list=array();
	if($msgtype=='receive')
		$msg_list=uc_pm_list($cyask_uid,$page,$pagerow,'inbox','');
	else
		$msg_list=uc_pm_list($cyask_uid,$page,$pagerow,'outbox','');	
	foreach($msg_list['data'] as $k=>$v)
		$msg_list['data'][$k]['dateline']=date("Y-m-d H:i:s",$msg_list['data'][$k]['dateline']);

	$page_front	=$page-1;
	$page_next	=$page+1;
	$parameter = 'command='.$command.'&msgtype='.$msgtype;
	$pagelinks = get_pages($page,$pagecount,$parameter);
}
elseif($command=='myinfo')
{

	$query=$dblink->query("select username,gender,email,bday from {$dbprefix}members where uid=$cyask_uid");
	$members=$dblink->fetch_array($query);
	$member_username=$members['username'];
	$member_email=$members['email'];
	$member_gender=$members['gender'];
	$member_bday=$members['bday'];
	unset($query);
	
}
elseif($command=='upinfo')
{
	$query=$dblink->query("select username,gender,email,bday from {$dbprefix}members where uid=$cyask_uid");
	$members=$dblink->fetch_array($query);
	$member_username=$members['username'];
	$member_email=$members['email'];
	$member_gender=$members['gender'];
	$member_bday=$members['bday'];
	
	unset($query);
}
elseif($command=='upinfosubmit')
{
	if(check_submit($_POST['upinfosubmit'], $_POST['formhash']))
	{
		
		$query=$dblink->query("update {$dbprefix}members set gender='$_POST[gender]',email='$_POST[email]',bday='$_POST[bday]' where uid=$cyask_uid");
		uc_user_edit($cyask_user,'','',$_POST['email'],1);
		$backurl='my.php?command=myinfo';
		show_message('upinfo_succeed', $backurl);
		exit;
	}
	else
	{
		show_message('url_error', './');
		exit;
	}
	
}
elseif($command=='uppassword')
{
}
elseif($command=='uppwsubmit')
{
	if(check_submit($_POST['uppwsubmit'], $_POST['formhash']))
	{
		$opw=md5($_POST['opw']);
		$npw=md5($_POST['npw']);
		$ret=uc_user_edit($cyask_user,$_POST['opw'],$_POST['npw'],'',0);
		
		if($ret==1)
		{
			
			$dblink->query("update $dbprefix"."members set password='$npw' where uid=$cyask_uid");
			$backurl='my.php?command=myinfo';
			show_message('uppw_succeed', $backurl);
			exit;
		}
		else
		{
			$backurl='my.php?command=uppassword';
			show_message('uppw_error', $backurl);
			exit;
		}
	}
	else
	{
		show_message('url_error', './');
		exit;
	}
	
}
elseif($command=='sendmsg')
{

}
elseif($command=='sendmsgsubmit')
{
	$num=uc_pm_send($cyask_uid,$_POST['username'],$_POST['title'],$_POST['content'],1,0,1);
	if($num>0)
	{
		$backurl='my.php?command=mymessage';
		show_message('sendmsg_succeed', $backurl);
	}else
	{
		show_message('sendmsg_error', $backurl);	
	}
	exit;
}
elseif($command=='readmsg')
{
	$mid=intval($_GET['mid']);
	
	$msg=uc_pm_view($cyask_uid,$mid);

	
}
elseif($command=='replymsg')
{
	$msguid=intval($_POST['fromuid']);
	$num=uc_pm_send($cyask_uid,$_POST['username'],$_POST['title'],$_POST['content'],1,$_POST['pmid'],1);
	if($num>0)
	{
		$backurl='my.php?command=mymessage';
		show_message('sendmsg_succeed', $backurl);
	}else
	{
		show_message('sendmsg_error', $backurl);	
	}
	exit;	

}
elseif($command=='delcollect')
{
	$id=intval($_GET['id']);
	$page=intval($_GET['page']);
	$query=$dblink->query("DELETE FROM {$dbprefix}collect where uid=$cyask_uid AND id=$id");
	$backurl='my.php?command=mycollect&page='.$page;
	show_message('delcollect_succeed', $backurl);
	exit;
}
elseif($command=='delmessage')
{
	$page=intval($_GET['page']);
	$mid=intval($_GET['mid']);
	if($_GET['type']=='receive')
	{
		uc_pm_delete($cyask_uid,'inbox',array($mid));
	}elseif($_GET['type']=='send')
	{
		uc_pm_delete($cyask_uid,'outbox',array($mid));
	}
	
	
	$backurl='my.php?command=mymessage&type='.$_GET['type'].'&page='.$page;
	show_message('delmessage_succeed', $backurl);
	exit;
}
else
{
	show_message('action_error', './');
	exit;
}
include template('my');
?>


