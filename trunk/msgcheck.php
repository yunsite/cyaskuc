<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'msgcheck');
require_once './include/common.inc.php';
if($cyask_uid)
{
	exit(uc_pm_checknew($cyask_uid));
}
else
{
	exit();
}
?>