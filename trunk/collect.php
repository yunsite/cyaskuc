<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'collect');
error_reporting(7);
require_once ('./include/common.inc.php');
$title=$site_name;

if(!$cyask_uid)
{
	$url=get_referer();
	show_message('user_nologin', '');
	exit;
}

if($command=='collect')
{
	if(check_submit($_POST['collectsubmit'], $_POST['formhash']))
	{
		$collect_url=trim($_POST['collect_url']);
		$collect_title=trim($_POST['collect_title']);
		$collect_content=empty($_POST['content']) ? '' : filters_content($_POST['content']);
		
		$sortname=$_POST['sortname'];
		$ctype=$_POST['ctype'];
		$public=$_POST['public'];
		$dblink->query("INSERT INTO {$dbprefix}collect SET uid=$cyask_uid,username='$cyask_user',sortname='$sortname',ctype='$ctype',title='$collect_title',content='$collect_content',url='$collect_url',public='$public',collecttime='$timestamp'");
		$url=$_POST['url'];
		show_message('collect_succeed', $url);
		exit;
	}
	else
	{	
		show_message('url_error', './');
		exit;
	}
	
}
else
{
	$url=get_referer();
	$neturl=empty($_POST['neturl']) ? trim($_GET['neturl']) : trim($_POST['neturl']);
	
	$collect_url=empty($neturl) ? $url : $neturl;
	
	$contents = '';
	if($fid=@fopen($collect_url,"r"))
	{
		do
		{
			$data = fread($fid, 4096);
			if (strlen($data) == 0)
			{
				break;
			}
			$contents .= $data;
		}
		while(true);
		fclose($fid);
	}
	else
	{
		show_message('collect_url_error', '');
		exit;
	}
	
	if(preg_match('/< *title *>(.*?)< *\/ *title *>/is',$contents,$titles))
	{
		$collect_title = trim($titles[1]);
	}
	else
	{
		$collect_title = '';
	}
	if($neturl)
	{
		$collect_content = trim($contents);
		$collect_content =cut_tags($collect_content);
		$collect_content =htmlspecialchars($collect_content);
	}
	else
	{
		$contents = '';
	}
	include template('collect_edit');
	exit;
}

?>