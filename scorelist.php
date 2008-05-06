<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'scorelist');
require('./include/common.inc.php');
$title=$site_name;
$list_count=100;

$query=$dblink->query("SELECT uid,username,gender,regdate,scores AS score FROM $dbprefix"."members ORDER BY scores desc limit $list_count");

$i=1;
while($member_temp=$dblink->fetch_array($query))
{
	$member_temp['orderid']=$i;
	$member_temp['regdate']=date("Y-m-d H:i",$member_temp['regdate']);
	$members_list[$i]=$member_temp;
	$i++;
}

include template('scorelist');
?>