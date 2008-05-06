<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'register');
require('./include/common.inc.php');
$url=empty($_GET['url']) ? $_POST['url'] : $_GET['url'];
if($command=='registed')
{
	if($cyask_uid)
	{
		show_message('login_succeed', $url);
	}
	if(check_submit($_POST['registsubmit'], $_POST['formhash']))
	{
		$cyask_user=trim($_POST['username']);
		$cyask_user=strtolower($cyask_user);
		$password=trim($_POST['password']);
		$email=$_POST['email'];
		$username_ok=filters_username($cyask_user);
		if(!$username_ok)
		{
			show_message('regist_name_error', '');
		}
		$email_ok=uc_user_checkemail($email);
		if(!$email_ok)
		{
			show_message('regist_email_error'.abs($email_ok),'');
		}
		$usernum=uc_user_checkname($cyask_user);
		if($usernum!=1)
		{
			show_message('regist_name_used', '');
        }
		else
		{
			$password=trim($_POST['password']);
			$cyask_uid=uc_user_register($cyask_user,$password,$email);
			if($cyask_uid>0)
			{
					$dblink->query("INSERT INTO {$dbprefix}members(uid,username,email,adminid,groupid) VALUES('$cyask_uid','$cyask_user','$email','5','0')");
					list($cyask_uid,$username,$passwd,$email)=uc_user_login($cyask_user,$password);
					uc_dsetcookie('auth', uc_authcode($cyask_uid."\t".$username."\t".$email, 'ENCODE'), 86400 * 365);		
					$syninfo=uc_user_synlogin($cyask_uid);
					show_message('regist_succeed', $url);
			}
			else
			{
				show_message('regist_error', '');
			}
		}
	}
	else
	{
		exit("url error");
	}
}
else
{
	include template('register');
}
?>
