<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'index');
require_once ('include/common.inc.php');

$title=$site_name.' - powered by cyask.com';
$query=$dblink->query("select count(*) from {$dbprefix}ques where status=2");
$solve_ques_count=$dblink->result($query,0);

$query=$dblink->query("select count(*) from {$dbprefix}ques where status=1");
$nosolve_ques_count=$dblink->result($query,0);
	
$query=$dblink->query("select sid,sort1 as sort from {$dbprefix}sort where grade=1 order by orderid asc,sid asc limit $count_show_sort1");
$i=0;
while($sort1_temp=$dblink->fetch_array($query))
{
	$sort1_temp['id']= $i;
	$sort1_list[$i]  = $sort1_temp;
	$querytemp=$dblink->query("select sid,sort2 as sort from {$dbprefix}sort where sid1=$sort1_temp[sid] and grade=2 order by orderid asc limit $count_show_sort2");
	$j=1;
	while($sort2_temp=$dblink->fetch_array($querytemp))
	{
		$sort2_temp['id']= $j;
		$sort2_list[$i][$j]=$sort2_temp;
		$j++;
	}
	$i++;
}

$query_intro=$dblink->query("select qid,title,sid1,sid2,sid3 from {$dbprefix}ques where introtime!=0 order by introtime desc limit $count_show_intro");
$i=1;
while($intro=$dblink->fetch_array($query_intro))
{
	$intro['title']=cut_str($intro['title'],60);
	
	if($intro['sid3'])
	{
		$query_sort=$dblink->query("select sid,sort3 as sort from {$dbprefix}sort where sid=$intro[sid3]");
	}
	else if($intro['sid2'])
	{
		$query_sort=$dblink->query("select sid,sort2 as sort from {$dbprefix}sort where sid=$intro[sid2]");
	}
	else
	{
		$query_sort=$dblink->query("select sid,sort1 as sort from {$dbprefix}sort where sid=$intro[sid1]");
	}
	$sort=$dblink->fetch_array($query_sort);
	$intro_ques[$i] = array_merge($intro,$sort);
	$i++;
}

$query=$dblink->query("select qid,title,sid1,sid2,sid3 from {$dbprefix}ques where status=1 order by qid desc limit $count_show_nosolve");
$i=1;
while($nosolve=$dblink->fetch_array($query))
{
	$nosolve['title']=cut_str($nosolve['title'],58);
	
	if($nosolve['sid3'])
	{
		$query_sort=$dblink->query("select sid,sort3 as sort from {$dbprefix}sort where sid=$nosolve[sid3]");
	}
	elseif($nosolve['sid2'])
	{
		$query_sort=$dblink->query("select sid,sort2 as sort from {$dbprefix}sort where sid=$nosolve[sid2]");
	}
	else
	{
		$query_sort=$dblink->query("select sid,sort1 as sort from {$dbprefix}sort where sid=$nosolve[sid1]");
	}
	$sort=$dblink->fetch_array($query_sort);
	$nosolve_ques[$i] = array_merge($nosolve,$sort);
	$i++;
}

$query=$dblink->query("select qid,title,sid1,sid2,sid3 from {$dbprefix}ques where status=2 order by qid desc limit $count_show_solve");
$i=1;
while($solve=$dblink->fetch_array($query))
{
	$solve['title']=cut_str($solve['title'],58);
	
	if($solve['sid3'])
	{
		$query_sort=$dblink->query("select sid,sort3 as sort from {$dbprefix}sort where sid=$solve[sid3]");
	}
	elseif($solve['sid2'])
	{
		$query_sort=$dblink->query("select sid,sort2 as sort from {$dbprefix}sort where sid=$solve[sid2]");
	}
	else
	{
		$query_sort=$dblink->query("select sid,sort1 as sort from {$dbprefix}sort where sid=$solve[sid1]");
	}
	$sort=$dblink->fetch_array($query_sort);
	$solve_ques[$i] = array_merge($solve,$sort);
	$i++;
}

$query=$dblink->query("select id,title,url from {$dbprefix}notice order by orderid asc limit $count_show_note");
$i=1;
while($notice=$dblink->fetch_array($query))
{
	$notice['id']= empty($notice['url']) ? 'notice.php?id='.$notice['id'] : $notice['url'];
	$notice['stitle']=cut_str($notice['title'],24);
	$notice_list[$i]=$notice;
	$i++;
}
$query=$dblink->query("SELECT uid,username,scores AS score FROM $dbprefix"."members ORDER BY scores desc limit 6");

$i=0;
while($temp=$dblink->fetch_array($query))
{
	$scorelist[$i]=$temp;
	$i++;
}

unset($query);
//update_session();
include template('index');
?>
