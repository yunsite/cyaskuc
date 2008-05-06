<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'question');
require_once ('include/common.inc.php');
$web_path='./';
$qid=intval($_GET['qid']);

if($dblink->query("UPDATE {$dbprefix}ques SET clickcount=clickcount+1 WHERE qid=$qid"))
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE qid=$qid");
}
else
{
	show_message('action_error', './');
	exit;
}
$question=$dblink->fetch_array($query);
$askername=$question['username'];
$title=cut_str($question['title'],40);
$ques_title =$question['title'];
$ques_supplement=filters_outsupply($question['content']);
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
		
$ques_user='<a href="member.php?uid='.$question['uid'].'" target="_blank">'.$question['username'].'</a>';

$ques_allowhandle = (($ques_status==1 || $ques_status==3) && $cyask_uid && $cyask_uid==$question['uid']) ? 1 : 0;
$ques_allowsetvote= ($cyask_uid && $ques_status!=3) ? 1 : 0;
$ques_allowclose  = ($cyask_uid && $ques_status==1) ? 1 : 0;
$ques_allowanswer = ($cyask_uid && $ques_status==1 && $cyask_uid!=$question['uid']) ? 1 : 0;
$ques_allowcollect= ($cyask_uid!=$question['uid']) ? 1 : 0;

if($question['sid3'])
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid=$question[sid3]");
	$menu=$dblink->fetch_array($query);
	$toplink='<a class="question" href="./browse.php?sortid='.$menu['sid1'].'">'.$menu['sort1'].'</a> &gt;&gt; <a class="question" href="./browse.php?sortid='.$menu['sid2'].'">'.$menu['sort2'].'</a> &gt;&gt; <a class="question" href="./browse.php?sortid='.$menu['sid'].'">'.$menu['sort3'].'</a>';
	$query=$dblink->query("SELECT qid,title FROM {$dbprefix}ques WHERE sid3=$question[sid3] ORDER BY answercount desc,clickcount desc limit 6");
	$sid_more=$question['sid3'];
}
elseif($question['sid2'])
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid=$question[sid2]");
	$menu=$dblink->fetch_array($query);
	$toplink='<a class="question" href="./browse.php?sortid='.$menu['sid1'].'">'.$menu['sort1'].'</a> &gt;&gt; <a class="question" href="./browse.php?sortid='.$menu['sid'].'">'.$menu['sort2'].'</a>';
	$query=$dblink->query("SELECT qid,title FROM {$dbprefix}ques WHERE sid2=$question[sid2] ORDER BY answercount desc,clickcount desc limit 6");
	$sid_more=$question['sid2'];
}
elseif($question['sid1'])
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid=$question[sid1]");
	$menu=$dblink->fetch_array($query);
	$toplink='<a class="question" href="./browse.php?sortid='.$menu['sid'].'">'.$menu['sort1'].'</a>';
	$query=$dblink->query("SELECT qid,title FROM {$dbprefix}ques WHERE sid1=$question[sid1] ORDER BY answercount desc,clickcount desc limit 6");
	$sid_more=$question['sid1'];
}
$i=1;
while($ques_tmp=$dblink->fetch_array($query))
{
	$ques_tmp['qid']='question.php?qid='.$ques_tmp['qid'];
	$ques_tmp['stitle']=cut_str($ques_tmp['title'],28);
	$hotques_list[$i]=$ques_tmp;
	$i++;
}

if($ques_status==1)
{
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}answer WHERE qid=$qid");
	$answer_count=$dblink->result($query,0);
	$ques_allowvote = ($answer_count >1) ? 1 :0;

	$query=$dblink->query("SELECT aid,qid,uid,username,answer,answertime,response FROM {$dbprefix}answer WHERE qid=$qid ORDER BY aid asc");
	while($answer_tmp=$dblink->fetch_array($query))
	{
		$answer_tmp['answer']=filters_outcontent($answer_tmp['answer']);
		$answer_tmp['time']=date("y-m-d H:i",$answer_tmp['answertime']);
     
		$answer_list[$answer_tmp['aid']]=$answer_tmp;
	}
	//update_session();
	include template('question_nosolve');
	exit();
}	
elseif($ques_status==2)
{
	$query=$dblink->query("SELECT aid,qid,uid,username,answer,answertime,adopttime,response FROM {$dbprefix}answer WHERE qid=$qid AND adopttime<>0  ORDER BY aid desc");
	$adoptanswer=$dblink->fetch_array($query);
	$adoptanswer['answer']=filters_outcontent($adoptanswer['answer']);
	$adoptanswer['answertime']=date("y-m-d H:i",$adoptanswer['answertime']);
	$adoptanswer['adopttime']=date("y-m-d H:i",$adoptanswer['adopttime']);
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}answer WHERE qid=$qid AND adopttime=0");
	$answer_count=$dblink->result($query,0);
	$query=$dblink->query("SELECT aid,qid,uid,username,answer,answertime,response FROM {$dbprefix}answer WHERE qid=$qid AND adopttime=0 ORDER BY aid asc");
	while($answer_tmp=$dblink->fetch_array($query))
	{
		$answer_tmp['answer']=filters_outcontent($answer_tmp['answer']);
		$answer_tmp['answertime']=date("y-m-d H:i",$answer_tmp['answertime']);
     
		$answer_list[$answer_tmp['aid']]=$answer_tmp;
	}
	//update_session();
	include template('question_solve');
	exit();
}	
elseif($ques_status==3)
{  
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}answer WHERE qid=$qid AND joinvote=1");
	$vote_count=$dblink->result($query,0);
	
	$query=$dblink->query("SELECT aid,qid,uid,username,answer,votevalue,answertime,response FROM {$dbprefix}answer WHERE qid=$qid AND joinvote=1 ORDER BY aid asc");
    $i=1;
    while($vote_tmp=$dblink->fetch_array($query))
    {
		$vote_tmp['answer']=filters_outcontent($vote_tmp['answer']);
		$vote_tmp['answertime']=date("y-m-d H:i",$vote_tmp['answertime']);
     
		$vote_list[$i]=$vote_tmp;
		$i++;
	}
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}answer WHERE qid=$qid AND joinvote<>1");
	$answer_count=$dblink->result($query,0);
	
	$query=$dblink->query("SELECT aid,qid,uid,username,answer,answertime,response FROM {$dbprefix}answer WHERE qid=$qid AND joinvote<>1 ORDER BY aid desc");
	while($answer_tmp=$dblink->fetch_array($query))
	{
		$answer_tmp['answer']=filters_outcontent($answer_tmp['answer']);
		$answer_tmp['answertime']=date("y-m-d H:i",$answer_tmp['answertime']);
		
		$answer_list[$answer_tmp['aid']]=$answer_tmp;
	}
	//update_session();
	include template('question_vote');
	exit();
}
else
{
	//update_session();
	include template('question_solve');
	exit();
}
?>