<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'member');
require('./include/common.inc.php');
$command= empty($command) ? 'info': $command;

$uid=$_GET['uid'] ? intval($_GET['uid']) : intval($_POST['uid']);

	$query=$dblink->query("select uid,username,gender,scores AS totalscore,email,bday from {$dbprefix}members where uid=$uid");

if(!$dblink->num_rows($query))
{
	show_message('username_error', './');
	exit;
}
$members=$dblink->fetch_array($query);
$username=$members['username'];

if($command=='info')
{
	$member_email=$members['email'];
	$member_gender=$members['gender'];
	$member_bday=$members['bday'];
}
elseif($command=='score')
{
	$totalscore=intval($members['totalscore']);
	$query=$dblink->query("select count(*) from {$dbprefix}answer where uid=$uid");
	$answercount=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}answer where uid=$uid and adopttime<>0");
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
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$uid");
	$question_allcount=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$uid and status=2");
	$questionOK=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$uid and status=1");
	$questionASK=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$uid and status=3");
	$questionVOTE=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$uid and status=4");
	$questionCLOSE=$dblink->result($query,0);
	$query=$dblink->query("select count(*) from {$dbprefix}collect where uid=$uid");
	$collectcount=$dblink->result($query,0);
	unset($query);
}
elseif($command=='question')
{
	$page=intval($_GET['page']);
	if($page<1) $page=1;
	$pagerow=10;
	$query=$dblink->query("select count(*) from {$dbprefix}ques where uid=$uid");
	$quescount=$dblink->result($query,0);     
	$pagecount=ceil($quescount/$pagerow);
	if($page>$pagecount) $page=1;
	$pagestart=($page-1)*$pagerow;
	$query=$dblink->query("select * from {$dbprefix}ques where uid=$uid limit $pagestart,$pagerow");
	
	while($ques_temp=$dblink->fetch_array($query))
	{
		$ques_temp['stitle']=cut_str($ques_temp['title'],54);
		$ques_temp['asktime']=date("y-n-j",$ques_temp['asktime']);
		$ques_list[$ques_temp['qid']] = $ques_temp;
	}
	unset($query);
	$page_front	=$page-1;
	$page_next	=$page+1;
	$pagelinks = get_pages($page,$pagecount,'command='.$command.'&uid='.$uid);
}
elseif($command=='answer')
{
	$page=intval($_GET['page']);
	if($page<1) $page=1;
	$pagerow=10;
	$query=$dblink->query("select count(*) from {$dbprefix}answer where uid=$uid");
	$answercount=$dblink->result($query,0);     
	$pagecount=ceil($answercount/$pagerow);
	if($page>$pagecount) $page=1;
	$pagestart=($page-1)*$pagerow;
	$query=$dblink->query("select a.aid,a.qid,a.answertime,a.response,q.title,q.status,q.score,q.answercount from {$dbprefix}answer a,{$dbprefix}ques q WHERE a.uid=$uid AND a.qid=q.qid limit $pagestart,$pagerow");
	
	while($ques_temp=$dblink->fetch_array($query))
	{
		$ques_temp['stitle']=cut_str($ques_temp['title'],54);
		$ques_temp['answertime']=date("y-n-j",$ques_temp['answertime']);
		$ques_list[$ques_temp['aid']] = $ques_temp;
	}
	
	unset($query);
	$page_front	=$page-1;
	$page_next	=$page+1;
	$pagelinks = get_pages($page,$pagecount,'command='.$command.'&uid='.$uid);
}
elseif($command=='collect')
{
	$page=intval($_GET['page']);
	if($page<1) $page=1;
	$pagerow=10;
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}collect WHERE uid=$uid");
	$collect_count=$dblink->result($query,0);     
	$pagecount=ceil($collectcount/$pagerow);
	if($page>$pagecount) $page=1;
	$pagestart=($page-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}collect WHERE uid=$uid limit $pagestart,$pagerow");
	$i=1;
	while($ques_temp=$dblink->fetch_array($query))
	{
		$ques_temp['stitle']=cut_str($ques_temp['title'],30);
		$ques_temp['surl']=cut_str($ques_temp['url'],50).'...';
		$ques_temp['time']=date("y-n-j",$ques_temp['collecttime']);
		$ques_list[$ques_temp['id']] = $ques_temp;
		$i++;
	}
	unset($query);
	$page_front	=$page-1;
	$page_next	=$page+1;
	$pagelinks = get_pages($page,$pagecount,'command='.$command.'&uid='.$uid);
}
elseif($command=='message')
{
	if(!$cyask_user)
	{
		$url='member.php?uid='.$uid;
		show_message('user_nologin', '');
		exit;
	}
}
elseif($command=='sendmsg')
{
	if(!$cyask_user)
	{
		$backurl='member.php?uid='.$uid;
		show_message('user_nologin', '');
		exit;
	}
	if($cyask_uid==$uid)
	{
		$url='member.php?uid='.$uid;
		show_message('sendmsg_self', $url);
		exit;
	}
	if(check_submit($_POST['sendmsg'], $_POST['formhash']))
	{
		$num=uc_pm_send($cyask_uid,$uid,$_POST['title'],$_POST['content'],1,0,0);
		if($num>0)
		{
			$url='member.php?uid='.$uid;
			show_message('sendmsg_succeed', $url);
		}else
		{
			$url='member.php?uid='.$uid;
			show_message('sendmsg_error', $url);
		
		}
	
/*		if($passport=='discuz5.0.0')
		{
			$dblink->query("insert into $dbprefix"."pms set msgfrom='$cyask_user',msgfromid='$cyask_uid',msgtoid='$uid',folder='inbox',new=1,subject='$_POST[title]',dateline='$timestamp',message='$_POST[content]'");
			
		}
		elseif($passport=='discuz5.5.0')
		{
			$dblink->query("insert into $dbprefix"."pms set msgfrom='$cyask_user',msgfromid='$cyask_uid',msgtoid='$uid',folder='inbox',new=1,subject='$_POST[title]',dateline='$timestamp',message='$_POST[content]'");
			
			$url='member.php?uid='.$uid;
			show_message('sendmsg_succeed', $url);
		}
*/	}
	else
	{
		show_message('url_error', './');
	}
	
}
else
{
	show_message('action_error', './');
	exit;
}
include template('member');
?>