<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$mtime = explode(' ', microtime());
$cyask_starttime = $mtime[1] + $mtime[0];
error_reporting(7);
define('IN_CYASK', TRUE);
define('CYASK_ROOT', substr(dirname(__FILE__), 0, -7));
require_once CYASK_ROOT.'./config.inc.php';

require_once CYASK_ROOT.'./include/global.func.php';

require_once CYASK_ROOT.'./include/db_'.$database.'.php';

require_once CYASK_ROOT.'./uc_client/client.php';

file_exists(CYASK_ROOT.'./uc_client/data/cache/apps.php') &&include_once(CYASK_ROOT.'./uc_client/data/cache/apps.php');
if(isset($_CACHE['apps']))
	rsort($_CACHE['apps']);
else
	$_CACHE['apps']=rsort(uc_app_ls());


if(!defined('CURSCRIPT'))
{
	exit('CURSCRIPT ERROR');
}
$magic_quotes_gpc = get_magic_quotes_gpc();
if(!$magic_quotes_gpc)
{
	$_POST	= daddslashes($_POST);
	$_GET	= daddslashes($_GET);
	$_FILES = daddslashes($_FILES);
}

$cyask_version='3.0 for UCenter';
$timestamp	  = time();
$PHP_SELF	  = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$QUERY_STRING = empty($_SERVER['QUERY_STRING']) ? '' : '?'.$_SERVER['QUERY_STRING'];
$onlineip	  = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
$command	  = empty($_POST['command']) ? $_GET['command'] : $_POST['command'];

	$tmp='';
	if(strpos($_SERVER['PHP_SELF'],$DBDIR)!==false)
	{
		$tmp=substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],$DBDIR));
	}else
	{
		$tmp=$_SERVER['PHP_SELF'];
	}
if(empty($_SERVER['HTTP_HOST'])) 
	$baseurl = "http://$_SERVER[HTTP_HOST]".substr($tmp,0,strrpos($tmp,'/'));
else 
	$baseurl = "http://$_SERVER[HTTP_HOST]".substr($tmp,0,strrpos($tmp,'/'));
if($gzipcompress && function_exists('ob_gzhandler') && CURSCRIPT != 'wap')
{
	ob_start('ob_gzhandler');
}
else
{
	$gzipcompress = 0;
	ob_start();
}

$dblink = new db_sql;
$dblink->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

$_DCOOKIE = $_DCACHE =array();

list($cyask_uid,$username,$email)=explode("\t",uc_authcode($_COOKIE['auth'],'DECODE'));

define('FORMHASH', form_hash());
$styleid =$_DCOOKIE['styleid'] ? $_DCOOKIE['styleid'] : 1 ;
$cyask_adminid=0;
if($cyask_uid)
{
	$query = $dblink->query("SELECT username,password,adminid,groupid FROM {$dbprefix}members WHERE uid=$cyask_uid");
	$members=$dblink->fetch_array($query);
	if(empty($members))
	{
		$members=array();
		list($uid,$uname,$email)=uc_get_user($cyask_uid,1);
		$dblink->query("INSERT INTO {$dbprefix}members(uid,username,email,adminid,groupid,regdate) VALUES('$cyask_uid','$username','$email','5','0','".time()."')");
		$cyask_user=$username;
		unset($uid,$uname);
		$adminid='5';
		$groupid=0;
		$cyask_adminid=($adminid==1 || $groupid==3) ? 1 : 0 ;
	}else
	{
		$cyask_user=$members['username'];
		$adminid=$members['adminid'];
		$groupid=$members['groupid'];
		$cyask_adminid=($adminid==1 || $groupid==3) ? 1 : 0 ;
	}
}

$cache_variable_file = CYASK_ROOT.'./askdata/cache/cache_variable.php';
if(file_exists($cache_variable_file))
{
	include_once($cache_variable_file);
}
else
{
	create_cache('variable');
	include_once($cache_variable_file);
}

$cache_style_file = CYASK_ROOT.'./askdata/cache/cache_style.php';
if(file_exists($cache_style_file))
{
	include_once($cache_style_file);
}
else
{
	create_cache('style');
	include_once($cache_style_file);
}
$tpldir = $_DCACHE['style'][$styleid]['tpldir'];
$styledir = $_DCACHE['style'][$styleid]['styledir'];

if(!defined('CURSCRIPT') || CURSCRIPT != 'wap')
{
	if(!$headercache)
	{
		@header("Expires: 0");
		@header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
	}
	if($headercharset && !defined('TEXTXML'))
	{
		@header('Content-Type: text/html; charset='.$charset);
	}
	if(empty($_DCOOKIE['sid']) || $sid != $_DCOOKIE['sid'])
	{
		set_cookie('sid', $sid, 604800);
	}
}
?>