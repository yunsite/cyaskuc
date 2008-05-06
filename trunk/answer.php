<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'answer');
require_once ('./include/common.inc.php');

if($command=='ques_answer')
{
	$qid=intval($_POST['qid']);
	$query=$dblink->query("select uid,username from {$dbprefix}ques where qid=$qid");
	$asker=$dblink->fetch_array($query);
	if(!$dblink->num_rows($query))
	{
		show_message('action_error', './');
		exit;
	}
	if(!$cyask_uid)
	{
		$referer=get_referer();
		show_message('user_nologin', $referer);
		exit;
	}
	if($cyask_uid==$asker['uid'])
	{
		show_message('answer_yourself', '');
		exit;
	}

	if(check_submit($_POST['dosubmit'], $_POST['formhash']))
	{
		if(empty($_POST['content']))
		{
			show_message('answer_null', '');
			exit;
		}
	
		$query=$dblink->query("SELECT count(*) FROM {$dbprefix}answer WHERE uid=$cyask_uid AND qid=$qid");
		if($dblink->result($query,0))
		{
			show_message('answer_more', '');
			exit;
		}
		else
		{
			$content=filters_content($_POST['content']);
			
			$sql="INSERT INTO {$dbprefix}answer set qid=$qid,uid=$cyask_uid,username='$cyask_user',answer='$content',answertime=$timestamp";
			if($dblink->query($sql))
			{
				//$aid=$dblink->insert_id();
				add_score($cyask_uid, $score_answer);
				$dblink->query("UPDATE {$dbprefix}ques SET answercount=answercount+1 WHERE qid=$qid");
				
				$referer=get_referer($default = './');
				header("location:signal.php?resultno=109&url=$referer");
				exit;
			}
		}
	}
	else
	{
		show_message('url_error', './');
		exit;
	}
}
elseif($command=='answer_response')
{
	$aid=intval($_POST['aid']);
	$query=$dblink->query("select * from {$dbprefix}answer where aid=$aid");
	if(!$dblink->num_rows($query))
	{
		show_message('action_error', './');
		exit;
	}
	if(check_submit($_POST['dosubmit'], $_POST['formhash']))
	{
		$days = date("j");
		$months = date("n");
		$years = date("Y");
		$days = mktime(0,0,0,$months,$days,$years);
		
		if(empty($_POST['content']))
		{
			show_message('response_null', '');
			exit;
		}
		
		if($cyask_user)
		{
			$query=$dblink->query("SELECT count(*) FROM {$dbprefix}res WHERE aid=$aid AND uid=$cyask_uid AND days=$days");
		}
		else
		{
			$query=$dblink->query("SELECT count(*) FROM {$dbprefix}res WHERE aid=$aid AND uip='$onlineip' AND days=$days");
		}
		
		if($dblink->result($query,0)>3)
		{
			show_message('response_more', '');
			exit;
		}
		else
		{
			$content=filters_content($_POST['content']);
			$dblink->query("INSERT INTO {$dbprefix}res set aid=$aid,uid=$cyask_uid,username='$cyask_user',uip='$onlineip',content='$content',time=$timestamp,days=$days");
			$dblink->query("UPDATE {$dbprefix}answer SET response=response+1 WHERE aid=$aid");
			$referer=get_referer($default = './').'#response';
			header("location:signal.php?resultno=111&url=$referer");
			exit;
		}
	}
	else
	{
		show_message('url_error', './');
		exit;
	}
}
else
{
	show_message('action_error', './');
	exit;
}
?>
