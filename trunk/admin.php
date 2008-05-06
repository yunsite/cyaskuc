<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'admin');
@set_time_limit(600);
error_reporting(7);
require('./include/common.inc.php');
require(CYASK_ROOT.'./admin/admin.func.php');
@extract(daddslashes($_POST));
@extract(daddslashes($_GET));
$admin_days=date("md");
define('ADMINHASH', form_hash($admin_days));
$admin_check=($_COOKIE['adminhash']==ADMINHASH) ? 1:0;

$admin_login=($cyask_user && $cyask_adminid && $admin_check) ? 1:0;
$grade=$_GET['grade'] ? $_GET['grade'] : 1;
if(empty($admin_action) || isset($frames))
{
	$admin_action='home';

?>
<html>
<head>
<title>CYASK admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset?>" />
</head>
<frameset rows=30,* cols="*" frameborder="yes" border="1" framespacing="6">
<frame name="header" noresize scrolling="no" src="admin.php?admin_action=header">
<frameset cols="160,*" frameborder="no" border="0" framespacing="0" rows="*">
<frame name="menu" noresize scrolling="yes" src="admin.php?admin_action=menu">
<frame name="main" noresize scrolling="yes" src="admin.php?admin_action=<?php echo $admin_action;?>">
</frameset></frameset>
<noframes></noframes>
</html>
<?php

	exit();

}
include language('admin');
if($admin_action == 'menu')
{
	include_once ('./admin/menu.inc.php');
}
elseif($admin_action == 'header')
{
	require_once ('./admin/header.inc.php');
}
elseif($admin_action == 'home')
{
	if(!$cyask_adminid)
	{
		admin_header();
		admin_msg('noaccess');
		admin_footer();
	}
	else
	{
		$serverinfo=getenv('OS').' / php v'.phpversion();
		$dbversion=$dblink->version();
		if(@ini_get('file_uploads'))
		{
			$fileupload = $lang['yes'].': file '.ini_get('upload_max_filesize').' - form '.ini_get('post_max_size');
		}
		else
		{
			$fileupload = '<font color="red">'.$lang['no'].'</font>';
		}
		$dbsize = 0;
		$query = $dblink->query("SHOW TABLE STATUS LIKE '$tablepre%'", 'SILENT');
		while($table = $dblink->fetch_array($query))
		{
			$dbsize += $table['Data_length'] + $table['Index_length'];
		}
		$dbsize = $dbsize ? sizecount($dbsize) : $lang['unknown'];
	
		$attachsize = dir_size('./attachments/');
		$attachsize = is_numeric($attachsize) ? sizecount($attachsize) : $lang['unknown'];
	
		admin_header();
?>
		<br /><br />
		<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
		<tr><td>
			<table border="0" cellspacing="0" cellpadding="4" width="100%">
			<tr class="header"><td colspan="2"><?php echo $lang['home_sys_info']?></td></tr>
			<tr bgcolor="#FFFFFF"><td width="45%"><?php echo $lang['home_environment']?></td><td><?php echo $serverinfo?></td></tr>
			<tr bgcolor="#F8F8F8"><td><?php echo $lang['home_database']?></td><td><?php echo $dbversion?></td></tr>
			<tr bgcolor="#FFFFFF"><td><?php echo $lang['home_upload_perm']?></td><td><?php echo $fileupload?></td></tr>
			<tr bgcolor="#F8F8F8"><td><?php echo $lang['home_database_size']?></td><td><?php echo $dbsize?></td></tr>
			<tr bgcolor="#FFFFFF"><td><?php echo $lang['home_attach_size']?></td><td><?php echo $attachsize?></td></tr>
			</table>
		</td></tr>
		</table>
		<br />
		<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
		<tr><td>
			<table border="0" cellspacing="0" cellpadding="4" width="100%">
			<tr class="header"><td colspan="2"><?php echo $lang['home_dev']?></td></tr>
			<tr bgcolor="#FFFFFF"><td width="45%"><?php echo $lang['home_dev_copyright']?></td><td class="smalltxt"><a href="http://www.cyask.com" target="_blank">www.cyask.com</a></td></tr>
			<tr bgcolor="#FFFFFF"><td><?php echo $lang['home_dev_skins']?></td><td class="smalltxt"><a href="http://www.cyask.com" target="_blank">http://www.cyask.com</a></td></tr>
			<tr bgcolor="#FFFFFF"><td><?php echo $lang['home_dev_project_site']?></td><td class="smalltxt"><a href="http://www.cyask.com" target="_blank">http://www.cyask.com</a></td></tr>
			</table>
		</td></tr>
		</table>
		<table cellspacing="0" cellpadding="0" width="0" align="center">
		<tr><td>
		<script type="text/javascript">
		var version="<?php echo $cyask_version;?>";
		var passport="<?php echo $passport;?>";
		//document.write('<img width=0 height=0 border=0 src="http://www.cyask.com/news.php?website='+escape(location.hostname)+'&version='+version+'&passport='+passport+'" />');
</script>
	</td></tr>
		</table>
		<?php
		admin_footer();
		exit();
	}
}
elseif($admin_action == 'login')
{
	admin_header();
?>
<br /><br /><br /><br /><br /><br />
<form method="post" name="loginForm" action="admin.php">
<table cellspacing="1" cellpadding="2" width="60%" align="center" class="tableborder">
<tr class="header"><td colspan="2"><?php echo $lang['safecode_required']?></td></tr>
<tr><td class="altbg1" height=10 colspan="2">&nbsp;</td></tr>
<tr><td class="altbg1" width="25%">&nbsp;<?php echo $lang['username']?>:</td><td class="altbg2">
<?php echo $cyask_user;?> &nbsp;&nbsp;<a href="admin.php?admin_action=logout_admin">[<?php echo $lang['menu_logout_sys'];?>]</a></td></tr>
<tr><td class="altbg1" width="25%">&nbsp;<?php echo $lang['password']?>:</td><td class="altbg2">
<input type="password" name="password" size="25" />
<input type="hidden" name="admin_action" value="login_submit" />
<input type="hidden" name="backaction" value="<?php echo $_GET['backaction'];?>" />
</td></tr>
<tr><td class="altbg1" height=10 colspan="2">&nbsp;</td></tr>
</table>
<br><center><input type="submit" value="<?php echo $lang['submit']?>"></center>
</form>
<br><br>
<?php
	admin_footer();
}
elseif($admin_action == 'login_submit')
{
	list($uid,$uname,$pwd,$email)=uc_user_login($cyask_user,$_POST['password']);
	if($uid > 0 && $cyask_adminid==1)
	{
		$adminhash=ADMINHASH;
		uc_dsetcookie("adminhash",ADMINHASH);
		echo '<meta http-equiv=refresh content=0;URL="./admin.php?admin_action='.$_POST['backaction'].'">'; 
	}
	else
	{
		admin_header();
		echo '<script language="javaScript">alert("'.$lang['admin_passwd_wrong'].'");history.back();</script>';
		admin_footer();
	}
}
elseif($admin_action == 'logout_sys')
{
	clear_cookies();
	uc_dsetcookie('adminhash','');
	echo '<script language="JavaScript">top.location.href="./";</script>'; 
	exit;
}
elseif($admin_action == 'logout_admin')
{
	uc_dsetcookie('adminhash','');
	echo '<script language="JavaScript">top.location.href="./";</script>';
	exit;
}
else
{
	if($cyask_adminid == 1)
	{
		$admin_script='';
		if($admin_action == 'sort_list' || $admin_action == 'sort_add' || $admin_action == 'sort_edit'|| $admin_action == 'sort_add_submit' || $admin_action == 'sort_edit_submit' || $admin_action=='sort_del' || $admin_action=='sort_join' || $admin_action=='sort_join_submit')
		{
			$admin_script = 'sort_manage';
		}
		elseif($admin_action == 'ques_sort' || $admin_action == 'ques_nosolve' || $admin_action == 'ques_solve' || $admin_action == 'ques_vote' || $admin_action == 'ques_intro' || $admin_action == 'ques_list' || $admin_action == 'ques_edit' || $admin_action == 'ques_del' || 
		$admin_action == 'ques_top' || $admin_action == 'ques_close')
		{
			$admin_script = 'ques_manage';
		}
		elseif($admin_action == 'ques_answer' || $admin_action == 'answer_edit' || $admin_action == 'answer_del')
		{
			$admin_script = 'answer_manage';
		}
		elseif($admin_action == 'collect_list'|| $admin_action == 'collect_share' || $admin_action=='collect_hidden'|| $admin_action=='collect_del')
		{
			$admin_script = 'collect_manage';
		}
		elseif($admin_action == 'user_list' || $admin_action == 'user_total_score' || $admin_action == 'user_grade_manage' ||  $admin_action == 'user_score_manage' || $admin_action == 'user_del' || $admin_action == 'user_find')
		{
			$admin_script = 'user_manage';
		}
		elseif($admin_action == 'count' || $admin_action == 'count_pv' || $admin_action == 'count_ip' || $admin_action == 'count_fromsite'|| $admin_action == 'count_visitpage' || $admin_action=='count_keyword' || $admin_action=='count_bot')
		{
			$admin_script = 'count_manage';
		}
		elseif($admin_action == 'db_export' || $admin_action == 'db_import' || $admin_action == 'db_optimize' || $admin_action == 'db_down' || $admin_action == 'db_runquery')
		{
			$admin_script = 'database_manage';
		}
		elseif($admin_action == 'password' || $admin_action == 'setting')
		{
			$admin_script = 'setting_manage';
		}
		elseif($admin_action == 'announcement' || $admin_action == 'announcement_add' || $admin_action == 'announcement_add_submit' || $admin_action == 'announcement_edit' || $admin_action == 'announcement_edit_submit' || $admin_action == 'announcement_del')
		{
			$admin_script = 'announcement_manage';
		}
		elseif($admin_action == 'var_setting' || $admin_action == 'setting_edit')
		{
			$admin_script = 'setting_manage';
		}
		else
		{
			exit("admin.php error");
		}

	}
	if($admin_script)
	{
		if(file_exists(CYASK_ROOT.'./admin/'.$admin_script.'.php'))
		{
			require(CYASK_ROOT.'./admin/'.$admin_script.'.php');
		}else
		{
			admin_header();
			admin_msg('admin_file_not_exists');
			admin_footer();
		}
	}
	else
	{
		admin_header();
		admin_msg('noaccess');
		admin_footer();
	}
}

function admin_header()
{
	extract($GLOBALS, EXTR_SKIP);
	global $charset;
	echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset='.$charset.'">
	<style type="text/css">
<!--
a			{ text-decoration: none; color: #003366 }
a:hover			{ text-decoration: underline }
body			{ scrollbar-base-color: #F8F8F8; scrollbar-arrow-color: #698CC3; font-size: 12px; background-color: #9EB6D8 }
table			{ font: 12px Tahoma, Verdana; color: #000000 }
input,select,textarea	{ font: 11px Tahoma, Verdana; color: #000000; font-weight: normal; background-color: #F8F8F8 }
form			{ margin: 0; padding: 0}
select			{ font: 11px Arial, Tahoma; color: #000000; font-weight: normal; background-color: #F8F8F8 }
.nav			{ font: 12px Tahoma, Verdana; color: #000000; font-weight: bold }
.nav a			{ color: #000000 }
.header			{ font: 11px Tahoma, Verdana; color: #FFFFFF; font-weight: bold; background-color: #698CC3 }
.header a		{ color: #FFFFFF }
.category		{ font: 11px Arial, Tahoma; color: #000000; background-color: #EFEFEF }
.tableborder	{ background: #D6E0EF; border: 1px solid #698CC3 } 
.singleborder	{ font-size: 0px; line-height: 1px; padding: 0px; background-color: #F8F8F8 }
.smalltxt		{ font: 11px Arial, Tahoma }
.outertxt		{ font: 12px Tahoma, Verdana; color: #000000 }
.outertxt a		{ color: #000000 }
.bold			{ font-weight: bold }
.altbg1			{ background: #F8F8F8 }
.altbg2			{ background: #FFFFFF }
.maintable		{ width: 99%; background-color: #FFFFFF }
-->
</style>
<script language="JavaScript">
function deleteit()
{
	if( !confirm("'.$lang['admin_delete_info_confirm'].'")) return false;
	else return true;
}
function deleteall()
{
	if( !confirm("'.$lang['admin_bash_delete_info_confirm'].'")) return false;
	else return true;
}
</script>
</head>
<body background-color: #9EB6D8 text=#000000 leftmargin=10 topmargin=10>
<br />';
}

function admin_footer()
{
	global $cyask_version;
	echo "<br><br><hr size=0 noshade color=#698CC3 width=98%>
	<center>
	<font style=\"font-size: 11px; font-family: Tahoma, Verdana, Arial\">
	Powered by <a href=\"http://www.cyask.com\" target=\"_blank\" style=\"color: #000000\"><b>Cyask</b> $cyask_version</a> &nbsp;&copy; 2006-2007</font></center>
	</body></html>";
	exit;
}

function admin_msg($message, $url_forward = '', $msgtype = 'message', $extra = '')
{
	extract($GLOBALS, EXTR_SKIP);
	eval("\$message = \"".(isset($msglang[$message]) ? $msglang[$message] : $message)."\";");

	if($msgtype == 'form')
	{
		$message = "<form method=\"post\" action=\"$url_forward\">".
			"<br><br><br>$message$extra<br><br><br><br>\n".
        		"<input type=\"submit\" name=\"confirmed\" value=\"$lang[ok]\"> &nbsp; \n".
       			"<input type=\"button\" value=\"$lang[cancel]\" onClick=\"history.go(-1);\"></form><br>";
	}
	else
	{
		if($url_forward)
		{
			$message .= "<br><br><br><a href=\"$url_forward\">$lang[message_redirect]</a>";
			$url_forward = transsid($url_forward);
			$message .= "<script>setTimeout(\"redirect('$url_forward');\", 1250);</script>";
		}
		elseif(strpos($message, $lang['return']))
		{
			$message .= "<br><br><br><a href=\"javascript:history.go(-1);\" class=\"mediumtxt\">$lang[message_return]</a>";
		}
		$message = "<br><br><br>$message$extra<br><br>";
	}
	
echo "<br><br><br><br><br>
<table cellspacing=1 cellpadding=0 width=80% align=center class=tableborder>
<tr class=header><td height=25>&nbsp;&nbsp;$lang[cyask_message]</td></tr>
<tr><td bgcolor=#FFFFFF align=center>
<table border=0 width=90% cellspacing=0 cellpadding=0>
<tr><td width=100% align=center>
$message<br /><br />
</td></tr></table>
</td></tr></table>
<br><br><br>";
admin_footer();
}
?>