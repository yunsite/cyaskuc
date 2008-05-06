<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'noticelist');
require('./include/common.inc.php');
$title=$site_name;
$query=$dblink->query("SELECT id,title,url FROM {$dbprefix}notice ORDER BY orderid asc");
$i=1;
while($row=$dblink->fetch_array($query))
{
	$row['id']= empty($row['url']) ? 'notice.php?id='.$row['id'] : $row['url'];
	$row['time']=date("y-n-j",$row['time']);
	$notice_list[$i]=$row;
	$i++;
}
include template('noticelist');
?>
