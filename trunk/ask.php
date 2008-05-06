<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'ask');
require_once ('./include/common.inc.php');
//update_session();
$title=$site_name;

if(!$cyask_uid)
{
	$url='ask.php?word='.$_GET['word'];
	header("location:login.php?command=login&url=$url");
	exit;
}

$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE uid=$cyask_uid AND status IN(1,3) AND endtime<$timestamp");
$overdue_count=$dblink->result($query,0);
if($overdue_count)
{
	$dourl='my.php?command=myoverdue';
	show_message('ques_overdue', $dourl);
	exit;
}

$ques_title=empty($_GET['word']) ? $_POST['word'] : $_GET['word'];
$ques_title=trim($ques_title);
$ques_count=0;

if($command=='ask')
{
	if(check_submit($_POST['submit'], $_POST['formhash']))
	{
		if(empty($_POST['qtitle']))
		{
			show_message('title_null', '');
			exit;
		}
		$cid=intval($_POST['cid']);
		if($cid)
		{
			$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid=$cid");
			$sortrow=$dblink->fetch_array($query);
			switch($sortrow['grade'])
			{
				case 1 : $sid1=$sortrow['sid'];$sid2=0;$sid3=0;break;
				case 2 : $sid1=$sortrow['sid1'];$sid2=$sortrow['sid'];$sid3=0;break;
				case 3 : $sid1=$sortrow['sid1'];$sid2=$sortrow['sid2'];$sid3=$sortrow['sid'];break;
			}
		}
		else
		{
			show_message('class_error', '');
			exit;
		}
		$give_score=intval($_POST['givescore']);
		if($give_score)
		{
			$my_score=get_score($cyask_uid);
			if($give_score > $my_score)
			{
				show_message('score_error', '');
				exit;
			}
			else
			{
				sub_score($cyask_uid,$give_score);
			}
		}
		
		$ques_title	=filters_title($_POST['qtitle']);
		$ques_content=filters_content($_POST['qsupply']);
        $ques_hidanswer =$_POST['hidanswer'] ? 1 : 0;
		$endtime=$timestamp+1296000;
		$do=$dblink->query("INSERT INTO {$dbprefix}ques SET title='$ques_title',content='$ques_content',sid1='$sid1',sid2='$sid2',sid3='$sid3',uid='$cyask_uid',username='$cyask_user',
		score='$give_score',asktime='$timestamp',endtime='$endtime',hidanswer='$ques_hidanswer'");
		if($do)
		{
			include language('templates',$tpldir,$styleid);

			$feed = array();			
			$feed['icon'] = 'thread';
			$feed['title_template'] = $lang['ques_title_template'];
			$feed['title_data'] = array('username'=>$cyask_user);
			$feed['body_template'] = '<b>{subject}</b><br>{message}';
			$feed['body_data'] = array(
				'subject' => "<a href=\"".$baseurl."/question.php?qid=".$dblink->insert_id()."\">{$ques_title}</a>",
				'message' => cut_str(strip_tags(preg_replace("/\[.+?\]/is", '', $ques_content)), 150)
			);
			echo 1;
			uc_feed_add($feed['icon'], $cyask_uid, $cyask_user, $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], '', '', $feed['images']);
			echo 3;
	        header("location:signal.php?resultno=101&url=$url");
			exit;
		}
		else
		{
			show_message('ask_error', 'ask.php?word='.$word);
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
	if( !empty($ques_title) )
	{
		$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE status=2 AND title LIKE '%$ques_title%'");
		$ques_count=$dblink->result($query,0);
		$query=$dblink->query("SELECT qid,title,content,sid1,sid2,sid3 FROM {$dbprefix}ques WHERE status=2 AND title LIKE '%$ques_title%' LIMIT 15");
		$i=0;
		while($row=$dblink->fetch_array($query))
		{
			$row['title']=cut_str($row['title'],38);
			$row['supplement']=empty($row['content']) ? '' : cut_str($row['content'],100);
			$ques_list[$i]=$row;
			$i++;
		}
		$query=$dblink->query("SELECT sid2 AS sid,count(sid2) AS count2 FROM {$dbprefix}ques WHERE status=2 AND title LIKE '%$ques_title%' GROUP BY sid2 HAVING count(*)>0 ORDER BY count2 DESC LIMIT 5");
		$sort_count=$dblink->num_rows($query);
		if($sort_count)
		{
			$i=0;
			while($row=$dblink->fetch_array($query))
			{
				$query=$dblink->query("SELECT sort1,sort2 FROM {$dbprefix}sort WHERE sid=$row[sid]");
				$row2=$dblink->fetch_array($query);
				$sort_list[$i]=array_merge($row,$row2);
				$i++;
			}
		}
		else
		{
			$query=$dblink->query("SELECT sid,sort1 FROM {$dbprefix}sort WHERE grade=1");
			$count1=$dblink->num_rows($query);
			$class1='';
			$c=1;
			while($row1=$dblink->fetch_array($query))
			{
				$class1.='new Array("'.$row1[sid].'","'.$row1[sort1].'")';
				if($c==$count1) $class1.="\n"; else $class1.=",\n";
				$c++;
			}
			$query=$dblink->query("SELECT sid,sid1,sort2 FROM {$dbprefix}sort WHERE grade=2");
			$count2=$dblink->num_rows($query);
			$class2='';
			$c=1;
			while($row2=$dblink->fetch_array($query))
			{
				$class2.='new Array("'.$row2[sid1].'","'.$row2[sid].'","'.$row2[sort2].'")';
				if($c==$count2) $class2.="\n"; else $class2.=",\n";
				$c++;
			}
	
			$query=$dblink->query("SELECT sid,sid2,sort3 FROM {$dbprefix}sort WHERE grade=3");
			$count3=$dblink->num_rows($query);
			$class3='';
			$c=1;
			while($row3=$dblink->fetch_array($query))
			{
				$class3.='new Array("'.$row3[sid2].'","'.$row3[sid].'","'.$row3[sort3].'")';
				if($c==$count3) $class3.="\n"; else $class3.=",\n";
				$c++;
			}
		}
		$my_score=get_score($cyask_uid);
	}
}
include template('ask');
?>