<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'notice');
require('./include/common.inc.php');
$title=$site_name;
$id=intval($_GET['id']);
$query=$dblink->query("select * from {$dbprefix}notice where id=$id");
$row=$dblink->fetch_array($query);
$notice['title']=$row['title'];
$notice['time'] =date("y-n-j",$row['time']);
$notice['author']=$row['author'];
$notice['content']=filters_outcontent($row['content']);
include template('notice');
?>
