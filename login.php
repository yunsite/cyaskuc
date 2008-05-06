<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'login');
require_once ('./include/common.inc.php');
$url=empty($_GET['url']) ? $_POST['url'] : $_GET['url'];
if($command == 'logout')
{
	uc_dsetcookie('auth', '');
	$url = empty($url) ? './' : $url;
	$syninfo=uc_user_synlogout();
	show_message('logout_succeed', $url);

}
elseif($command=='login')
{
	if($cyask_uid)
	{
		$url=empty($url) ? './' : $url;
		show_message('login_succeed', $url);
	}

	if(check_submit($_POST['loginsubmit'], $_POST['formhash']))
	{
		$cyask_user =trim($_POST['username']);
		$cyask_user = daddslashes($cyask_user);
		$password=trim($_POST['password']);
		list($cyask_uid,$username,$passwd,$email)=uc_user_login($cyask_user,$password);
		if($cyask_uid > 0)
		{
			$url=empty($url) ? './' : $url;
			uc_dsetcookie('auth', uc_authcode($cyask_uid."\t".$username."\t".$email, 'ENCODE'), 86400 * 365);		
			$syninfo=uc_user_synlogin($cyask_uid);
			show_message('login_succeed', $url);
		}
		else
		{	
			$url='login.php?command=login&url='.$url;
			if($cyask_uid==-1)
			{
				show_message('login_invalid', $url);
			}else
			{
				show_message('login_password_error', $url);
			}
		}
	}else 
	{
		include template('login');
	}
	exit;
}
else
{
	show_message('undefined_action');
}
?>