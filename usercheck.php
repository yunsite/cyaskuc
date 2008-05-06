<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'checkuser');
require('./include/common.inc.php');
$username = trim($_GET['username']);
$email=trim($_GET['email']);
if(!empty($username) || !empty($email))
{
	if(!empty($username))
	{
		$usernum=uc_user_checkname($username);
		if($usernum==1)
		{
			$text='yes';
		}
		elseif($usernum==-3)
		{
			$text='no';
		}else
		{
			$text='error';
		}
	}elseif(!empty($email))
	{
		$text=uc_user_checkemail($email);		
	}
}
else
{
	$text='error';
}
echo $text;
?>