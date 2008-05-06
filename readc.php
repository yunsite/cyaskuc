<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'collectread');
require_once ('./include/common.inc.php');
$title=$site_name;

$id=intval($_GET['id']);
if($dblink->query("UPDATE {$dbprefix}collect SET click=click+1 WHERE id=$id"))
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}collect WHERE id=$id");
}
else
{
	show_message('action_error', './');
	exit;
}
$collect=$dblink->fetch_array($query);
$collect_sortname=$collect['sortname'];
$collect_title=$collect['title'];
$collect_content=filters_outcontent($collect['content']);
$collect_url=$collect['url'];
include template('collect_read');
exit;
?>
