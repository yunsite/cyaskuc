<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'handle');
require_once ('./include/common.inc.php');
//update_session();
$qid= empty($_GET['qid']) ? $_POST['qid'] : $_GET['qid'];
$qid=intval($qid);

if(!$cyask_uid)
{
	$referer=get_referer();
	header("location:login.php?url=$referer");
	exit;
}

$url=empty($_GET['url']) ? $_POST['url'] : $_GET['url'];

if($command=='ques_supply')
{
	$query=$dblink->query("select title,content from {$dbprefix}ques where qid=$qid");
	if(!$dblink->num_rows($query))
	{
		show_message('action_error', './');
		exit;
	}
	$title=$site_name;
	$question=$dblink->fetch_array($query);
	$ques_title=$question['title'];
	$ques_supplement=filters_outcontent($question['content']);
	include template('handle_ques_supply');
}
elseif($command=='ques_supply_submit')
{
	$newcontent=filters_content($_POST['supplement']);
	if(check_submit($_POST['supllysubmit'], $_POST['formhash']))
	{
		$query=$dblink->query("UPDATE {$dbprefix}ques SET content='$newcontent' where qid=$qid");
		header("location:signal.php?resultno=102&url=$url");
		exit;
	}
	else
	{
		show_message('url_error', './');
		exit;
	}
}
elseif($command=='answer_adopt')
{
	$query=$dblink->query("SELECT title,score FROM {$dbprefix}ques WHERE qid=$qid");
	if(!$dblink->num_rows($query))
	{
		show_message('action_error', './');
		exit;
	}
	$title=$site_name;
	$ques_row=$dblink->fetch_array($query);
	$ques_title=$ques_row['title'];
	$quesscore=$ques_row['score'] ? $ques_row['score'] : 0;
	$my_score=get_score($cyask_uid);
	$query=$dblink->query("select aid,uid,username,answer,answertime from {$dbprefix}answer WHERE aid=$_POST[aid]");
	$answer_row=$dblink->fetch_array($query);
	$answerid=intval($answer_row['aid']);
	$ques_answer=filters_outcontent($answer_row['answer']);
	$answertime=$answer_row['answertime'];
	$answer_user='<a href="member.php?uid='.$answer_row['uid'].'" target="_blank">'.$answer_row['username'].'</a>';
	
	
	
	
	include template('handle_answer_adopt');
}
elseif($command=='answer_adopt_submit')
{
	$aid=intval($_POST['aid']);
	$query=$dblink->query("SELECT qid,uid,username FROM {$dbprefix}answer WHERE aid=$aid");
	if(!$dblink->num_rows($query))
	{
		show_message('action_error', './');
		exit;
	}
	$answer=$dblink->fetch_array($query);
	
	if(check_submit($_POST['adoptsubmit'], $_POST['formhash']))
	{
		$content=filters_content($_POST['content']);
		$allscore=intval($_POST['score']+$_POST['addscore'])+intval($score_adopt);
		$addscore=intval($_POST['addscore']);
		$my_score=get_score($cyask_uid);
		if($addscore>$my_score)
		{
			show_message('score_error', '');
			exit;
		}
		$dblink->query("UPDATE {$dbprefix}ques SET status=2 WHERE qid=$answer[qid]");
		$dblink->query("UPDATE {$dbprefix}answer SET adopttime=$timestamp,response=response+1 WHERE aid=$aid");
		$dblink->query("INSERT INTO {$dbprefix}res SET aid=$aid,uid=$cyask_uid,username='$cyask_user',uip='$onlineip',content='$content',time=$timestamp");
		if($allscore)
		{
			sub_score($cyask_uid,$addscore);
			add_score($answer['uid'],$allscore);
		}
		$query=$dblink->query("SELECT qid,title FROM {$dbprefix}ques where qid={$answer['qid']}");
		$ques=$dblink->fetch_array($query);
		include language('templates',$tpldir,$styleid);
		$feed = array();
		$feed['icon'] = 'thread';
		$feed['title_template'] = $lang['adopt_title_template'];
		$feed['title_data'] = array('username'=>$cyask_user,'ausername'=>$answer['username']);
		$feed['body_template'] = '<b>{subject}</b><br>{message}';
		$feed['body_data'] = array(
			'subject' => "<a href=".$baseurl."\"question.php?qid=".$ques['qid']."\">{$ques['title']}</a>",
			'message' => cut_str(strip_tags(preg_replace("/\[.+?\]/is", '', $answer['answer'])), 150)
		);
		
		uc_feed_add($feed['icon'], $answer['uid'], $answer['username'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], '', '', $feed['images']);
        header("location:signal.php?resultno=108&url=$url");
		exit;
	}
	else
	{
		show_message('url_error', './');
		exit;
	}
}
elseif($command=='ques_addscore')
{
	$query=$dblink->query("select title,score from {$dbprefix}ques where qid=$qid");
	if(!$dblink->num_rows($query))
	{
		show_message('action_error', './');
		exit;
	}
	$title=$site_name;
	$question=$dblink->fetch_array($query);
	$ques_title=$question['title'];
	$ques_score=$question['score'];
	$my_score=get_score($cyask_uid);
	include template('handle_ques_addscore');
}
elseif($command=='ques_addscore_submit')
{
	$query=$dblink->query("select count(*) from {$dbprefix}ques where qid=$qid");
	if(!$dblink->result($query,0))
	{
		show_message('action_error', './');
		exit;
	}
	if(check_submit($_POST['addscoresubmit'], $_POST['formhash']))
	{
		$addscore=intval($_POST['addscore']);
		$my_score=get_score($cyask_uid);
		if($addscore>$my_score)
		{
			show_message('score_error', '');
			exit;
		}
		else
		{
			$dblink->query("UPDATE {$dbprefix}ques SET score=score+$addscore,endtime=endtime+432000 WHERE qid=$qid");
			sub_score($cyask_uid,$addscore);
			header("location:signal.php?resultno=106&url=$url");
			exit;
		}
	}
	else
	{
		show_message('url_error', './');
		exit;
	}
}
elseif($command=='ques_close')
{
	$query=$dblink->query("select count(*) from {$dbprefix}ques where qid=$qid");
	if(!$dblink->result($query,0))
	{
		show_message('action_error', './');
		exit;
	}
	$title=$site_name;
	$query=$dblink->query("select title,score,answercount from {$dbprefix}ques where qid=$qid");
	$question=$dblink->fetch_array($query);
	$ques_title=$question['title'];
	$ques_score=$question['score'];
	$answercount=$question['answercount'];
	include template('handle_ques_close');
}
elseif($command=='ques_close_submit')
{
	$query=$dblink->query("select score from {$dbprefix}ques where qid=$qid");
	if(!$dblink->num_rows($query))
	{
		show_message('action_error', './');
		exit;
	}
	if(check_submit($_POST['quesclosesubmit'], $_POST['formhash']))
	{
		$ques_score=$dblink->result($query,0);
		$dblink->query("UPDATE {$dbprefix}ques SET status=4 where qid=$qid");
		add_score($cyask_uid,$ques_score);
        header("location:signal.php?resultno=107&url=$url");
		exit;
	}
	else
	{
		show_message('url_error', './');
		exit;
	}
}
elseif($command=='ques_vote')
{
	$query=$dblink->query("select title,score from {$dbprefix}ques where qid=$qid");
	if(!$dblink->num_rows($query))
	{
		show_message('action_error', './');
		exit;
	}
	$title=$site_name;
	
	$question=$dblink->fetch_array($query);
	$ques_title=$question['title'];
	$ques_score=$question['score'];
	
	$query=$dblink->query("select * from {$dbprefix}answer where qid=$qid");
    $i=1;
    while($row=$dblink->fetch_array($query))
    {
		$row['id']=$i;
		$row['answer']=cut_str($row['answer'],200);
		$answer_list[$i]=$row;
		$i++;
	}
	
	include template('handle_ques_setvote');
}
elseif($command=='ques_vote_submit')
{
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE qid=$qid");
	if(!$dblink->result($query,0))
	{
		show_message('action_error', './');
		exit;
	}
	if(check_submit($_POST['quesvotesubmit'], $_POST['formhash']))
	{
		$dblink->query("UPDATE {$dbprefix}ques SET status=3 WHERE qid=$qid");
		
		$vote_list=explode("|",$_POST[vote_list]);
		$vote_count=count($vote_list);
		for($i=0;$i<$vote_count;$i++)
		{
			$dblink->query("UPDATE {$dbprefix}answer SET joinvote=1 WHERE aid={$vote_list[$i]}");
		}
		header("location:signal.php?resultno=104&url=$url");
		exit;
	}
	else
	{
		show_message('url_error', './');
		exit;
	}
}
?>
