<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'response');
require_once ('include/common.inc.php');
//update_session();
$aid=intval($_GET['aid']);
$query=$dblink->query("SELECT * FROM {$dbprefix}answer WHERE aid=$aid");
if($dblink->num_rows($query))
{
	$answer=$dblink->fetch_array($query);
	$answer['time']=date("y-m-d H:i",$answer['answertime']);
	$answer['answer']=filters_outcontent($answer['answer']);
	$qid=intval($answer['qid']);
}
else
{
	show_message('action_error', './');
	exit;
}
$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE qid=$qid");
$question=$dblink->fetch_array($query);
$ques_uid=$question['uid'];
$ques_user=$question['username'];
$ques_title =$question['title'];
$title=cut_str($ques_title,40);
$ques_supplement=filters_outcontent($question['content']);
$ques_status= $question['status'];
$ques_asktime= date("y-m-d H:i",$question['asktime']);
$ques_score = ($ques_status==1 && $question['score']) ? $question['score'] : 0;
if($ques_status==1 || $ques_status==3)
{
	$left_time=($question['endtime']-$timestamp);
	$left_day=floor($left_time/86400);
	$left_hour=floor($left_time%86400/3600);
	$left_day=$left_day>0 ? $left_day : 0;
	$left_hour=$left_hour>0 ? $left_hour : 0;
}
if($question['sid3'])
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid=$question[sid3]");
	$menu=$dblink->fetch_array($query);
	$toplink='<a class="question" href="./browse.php?sid='.$menu['sid1'].'">'.$menu['sort1'].'</a> &gt;&gt; <a class="question" href="./browse.php?sid='.$menu['sid2'].'">'.$menu['sort2'].'</a> &gt;&gt; <a class="question" href="./browse.php?sid='.$menu['sid'].'">'.$menu['sort3'].'</a>';
	$query=$dblink->query("SELECT qid,title FROM {$dbprefix}ques WHERE mid3=$question[sid3] ORDER BY answercount desc,clickcount desc limit 6");
	$sid_more=$question['sid3'];
	$title.='-'.$menu['sort3'].'-'.$menu['sort2'].'-'.$menu['sort1'];
}
elseif($question['sid2'])
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid=$question[sid2]");
	$menu=$dblink->fetch_array($query);
	$toplink='<a class="question" href="./browse.php?sid='.$menu['sid1'].'">'.$menu['sort1'].'</a> &gt;&gt; <a class="question" href="./browse.php?sid='.$menu['sid'].'">'.$menu['sort2'].'</a>';
	$query=$dblink->query("SELECT qid,title FROM {$dbprefix}ques WHERE sid2=$question[sid2] ORDER BY answercount desc,clickcount desc limit 6");
	$sid_more=$question['sid2'];
	$title.='-'.$menu['sort2'].'-'.$menu['sort1'];
}
elseif($question['sid1'])
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid=$question[sid1]");
	$menu=$dblink->fetch_array($query);
	$toplink='<a class="question" href="./browse.php?sid='.$menu['sid'].'">'.$menu['sort1'].'</a>';
	$query=$dblink->query("SELECT qid,title FROM {$dbprefix}ques WHERE sid1=$question[sid1] ORDER BY answercount desc,clickcount desc limit 6");
	$sid_more=$question['sid1'];
	$title.='-'.$menu['sort1'];
}
while($ques_tmp=$dblink->fetch_array($query))
{
	$ques_tmp['stitle']=cut_str($ques_tmp['title'],24);
	$hotques_list[$ques_tmp['qid']]=$ques_tmp;
}
$query=$dblink->query("SELECT * FROM {$dbprefix}res WHERE aid=$aid");
$response_count=$dblink->num_rows($query);
$i=1;
while($ques_tmp=$dblink->fetch_array($query))
{
	if(empty($ques_tmp['username']))
	{
		$ques_tmp['userlink']='#';
		$uip=explode('.',$ques_tmp['uip']);
		$ques_tmp['username']=$uip[0].'.'.$uip[1].'.'.$uip[2].'.'.'*';
	}
	else
	{
		$ques_tmp['userlink']='./member.php?uid='.$ques_tmp['uid'];
	}
	$ques_tmp['time']=date("y-m-d H:i",$ques_tmp['time']);
	$ques_tmp['content']=filters_outcontent($ques_tmp['content']);
	$response_list[$i]=$ques_tmp;
	$i++;
}
include template('response');
exit();	
?>