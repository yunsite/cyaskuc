<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/

error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);
error_reporting(7);
define('IN_CYASK', TRUE);
define('CYASK_ROOT', dirname(__FILE__));
if(PHP_VERSION < '4.1.0')
{
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
}

$action = $_POST['action'] ? $_POST['action'] : $_GET['action'];
$language = $_POST['language'] ? $_POST['language'] : $_GET['language'];

if(!function_exists('file_put_contents'))
{
	define('FILE_APPEND', 1);
	function file_put_contents($n, $d, $flag = false) {
	    $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
	    $f = @fopen($n, $mode);
	    if ($f === false) {
	        return 0;
	    } else {
	        if (is_array($d)) $d = implode($d);
	        $bytes_written = fwrite($f, $d);
	        fclose($f);
	        return $bytes_written;
	    }
	}
}
if (! function_exists('file_get_contents')) {
  function file_get_contents($file, $use_include_path=0) {
    $output = join('', file($file, $use_include_path));
    return str_replace(array("\r\n", "\r"), array("\n", "\n"), $output);
  }
}
@set_time_limit(100);
$sqlfile = CYASK_ROOT.'/install/cyask.sql';
$lockfile = CYASK_ROOT.'/askdata/install.lock';
if(file_exists($lockfile))exit('install locked.pls delete askdata/install.lock by ftp');
!file_exists(CYASK_ROOT.'/config.inc.php') &&touch(CYASK_ROOT.'/config.inc.php');
$tmp='';
if(strpos($_SERVER['PHP_SELF'],$DBDIR)!==false)
{
	$tmp=substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],$DBDIR));
}else
{
	$tmp=$_SERVER['PHP_SELF'];
}

if(empty($_SERVER['HTTP_HOST'])) 
	$baseurl = "http://".$_SERVER['HTTP_HOST'].substr($tmp,0,strrpos($tmp,'/'));
else 
	$baseurl = "http://".$_SERVER['SERVER_NAME'].substr($tmp,0,strrpos($tmp,'/'));

switch($language)
{
	case 'simplified_chinese_gbk':
		$dbcharset = $charset = 'gbk';
		break;
	case 'simplified_chinese_utf8':
		$dbcharset = 'utf8';
		$charset = 'utf-8';
		break;		
	default:
		$language = '';
		$dbcharset = $charset = '';
}

if($language)
{
	$languagefile = CYASK_ROOT.'/install/'.$language.'.lang.php';
	if(!is_readable($languagefile) || !is_readable($sqlfile))
	{
		exit('Please upload ./install and all its files completely.');
	}

	require_once $languagefile;

	$fp = fopen($sqlfile, 'rb');
	$sql = fread($fp, 2048000);
	fclose($fp);
}

header('Content-Type: text/html; charset='.$charset);
$version = '3.0 for UCenter 1.0';

?>
<html>
<head>
<title>Cyask with UCenter Installation Wizard</title>
<style>
A:visited	{COLOR: #3A4273; TEXT-DECORATION: none}
A:link		{COLOR: #3A4273; TEXT-DECORATION: none}
A:hover		{COLOR: #3A4273; TEXT-DECORATION: underline}
body,table,td	{COLOR: #3A4273; FONT-FAMILY: Tahoma, Verdana, Arial; FONT-SIZE: 12px; LINE-HEIGHT: 20px; scrollbar-base-color: #E3E3EA; scrollbar-arrow-color: #5C5C8D}
input		{COLOR: #085878; FONT-FAMILY: Tahoma, Verdana, Arial; FONT-SIZE: 12px; background-color: #3A4273; color: #FFFFFF; scrollbar-base-color: #E3E3EA; scrollbar-arrow-color: #5C5C8D}
.install	{FONT-FAMILY: Arial, Verdana; FONT-SIZE: 20px; FONT-WEIGHT: bold; COLOR: #000000}
</style>
</head>
<?

if(!in_array($language, array('simplified_chinese_gbk','simplified_chinese_utf8')))
{

?>
<body bgcolor="#FFFFFF">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%" align="center">
<tr><td valign="middle" align="center">

<table cellpadding="0" cellspacing="0" border="0" align="center">
  <tr align="center" valign="middle">
    <td bgcolor="#000000">
    <table cellpadding="10" cellspacing="1" border="0" width="500" height="100%" align="center">
    <tr>
      <td valign="middle" align="center" bgcolor="#EBEBEB">
         
        <br><b>Cyask with UCenter Installation Wizard</b><br><br>Please choose your prefered language<br><br>
        <center><a href="?language=simplified_chinese_gbk">[&#31616;&#20307;&#20013;&#25991; GBK]</a><br><br>
		<center><a href="?language=simplified_chinese_utf8">[&#31616;&#20307;&#20013;&#25991; UTF8]</a><br><br>
      </td>
    </tr>
    </table>
    </td>
  </tr>
</table>

</td></td></table>
</body>
</html>
<?

	exit();

} else {
?>
<body bgcolor="#3A4273" text="#000000">
<table width="95%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
  <tr>
    <td>
      <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td class="install" height="30" valign="bottom"><font color="#FF0000">&gt;&gt;</font>
            <?=$lang['install_wizard']?></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
            <b><?=$lang['welcome']?></b>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
<?php

}
if(!$action)
{
	
	$cyask_license = str_replace('  ', '&nbsp; ', $lang['license']);

?>
        <tr>
          <td><b><?=$lang['current_process']?> </b><font color="#0000EE"><?=$lang['show_license']?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['agreement']?></font></b></td>
        </tr>
        <tr>
          <td><br>
            <table width="90%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr>
                <td bgcolor="#E3E3EA">
                  <table width="99%" cellspacing="1" border="0" align="center">
                    <tr>
                      <td>
                        <?=$cyask_license?><?=CYASK_ROOT?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?=$language?>">
              <input type="hidden" name="action" value="ucconfig">
              <input type="submit" name="submit" value="<?=$lang['agreement_yes']?>" style="height: 25">&nbsp;
              <input type="button" name="exit" value="<?=$lang['agreement_no']?>" style="height: 25" onClick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?

}elseif($action=='ucconfig')
{
	$configfile = file_get_contents(CYASK_ROOT.'/config.inc.php');
	$configfile  = trim($configfile );
	$configfile  = substr($configfile , -2) == '?>' ? substr($configfile , 0, -2) : $configfile ;
	$configfile = insertconfig($configfile, "/[$]charset\s*\=\s*[\"'].*?[\"'];/is", "\$charset = '$charset';");
	$configfile = insertconfig($configfile, "/[$]dbcharset\s*\=\s*[\"'].*?[\"'];/is", "\$charset = '$dbcharset';");
	file_put_contents(CYASK_ROOT.'/config.inc.php',$configfile);
	@include_once CYASK_ROOT.'/config.inc.php';
	$ucapi=defined('UC_API') && UC_API?UC_API:$baseurl.'/uc_server';

?>
		<tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?=$language?>">
              <table width="700" cellspacing="1" bgcolor="#000000" border="0" align="center">
                 <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['uc_api']?></td>
                  <td bgcolor="#EEEEF6">&nbsp;<input name="ucapi" type="text" value="<?php echo $ucapi; ?>" size="30" /></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['uc_api_comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['ucfounderpw']?></td>
                  <td bgcolor="#EEEEF6" align="center"><INPUT type="text" size="30" value="" name="ucfounderpw"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['ucfounderpw_comment']?></td>
                </tr>
                 <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['app_name']?></td>
                  <td bgcolor="#EEEEF6" align="center"><INPUT class=txt size=30 value="Cyask3.0 for UCenter" name=app_name></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['app_name_comment']?></td>
                </tr>
                  <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;<?=$lang['app_url']?></td>
                  <td bgcolor="#EEEEF6" align="center"><INPUT class=txt size=30 value="<?php echo $baseurl; ?>" name=app_url></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['app_url_comment']?></td>
                </tr>
			</table>
			<td>
		</tr>
        <tr>
          <td align="center">
            <br>
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="<?=$lang['save_config']?>" style="height: 25">&nbsp;
              <input type="button" name="exit" value="<?=$lang['exit']?>" style="height: 25" onClick="javascript: window.close();">
            </form>
          </td>
        </tr>


<?php
}
elseif($action == 'config')
{
	
	
	//show_error($msg);
	$exist_error = FALSE;
	$write_error = FALSE;
	if(file_exists(CYASK_ROOT.'/config.inc.php'))
	{
		$fileexists = result(1, 0);
	}
	else
	{
		$fileexists = result(0, 0);
		$exist_error = TRUE;
		$config_info = $lang['config_nonexistence'];
	}
	if(is_writeable(CYASK_ROOT.'/config.inc.php'))
	{
		$filewriteable = result(1, 0);
		$config_info = $lang['config_comment'];
	}
	else
	{
		$filewriteable = result(0, 0);
		$write_error = TRUE;
		$config_info = $lang['config_unwriteable'];
	}

?>
        <tr>
          <td><b><?=$lang['current_process']?> </b><font color="#0000EE"><?=$lang['configure']?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['check_config']?></font></b></td>
        </tr>
        <tr>
          <td>config.inc.php <?=$lang['check_existence']?> <?=$fileexists?></td>
        </tr>
        <tr>
          <td>config.inc.php <?=$lang['check_writeable']?> <?=$filewriteable?></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['edit_config']?></font></b></td>
        </tr>
        <tr>
          <td align="center"><br><?=$config_info?></td>
        </tr>
<?

	if(!$exist_error)
	{

		if(!$write_error)
		{
		//uc start ====UC
		$app_type="OTHER";	
		$app_charset=$charset;
		$app_dbcharset=$dbcharset;	
		
		$app_name=isset($_POST['app_name'])?setconfig($_POST['app_name']):'Cyask 3.0 for UCenter';
		$app_url=isset($_POST['app_url'])?setconfig($_POST['app_url']):$baseurl;
		$ucapi=isset($_POST['ucapi'])?setconfig($_POST['ucapi']):'';
		$ucfounderpw=isset($_POST['ucfounderpw'])?setconfig($_POST['ucfounderpw']):'';
		$ucip='';
		$app_tagtemplates='';
		$ucapi = preg_replace("/\/$/", '', trim($ucapi));
		$dns_error=false;
		if(empty($ucapi) || !preg_match("/^(http:\/\/)/i", $ucapi)) 
		{
			show_error($lang['uc_url_error']);
		} else {
			if(!$ucip) 
			{
				$temp = @parse_url($ucapi);
				$ucip = gethostbyname($temp['host']);
				if(ip2long($ucip) == -1 || ip2long($ucip) === FALSE) 
				{
					$ucip = '';
					show_error($lang['uc_url_error']);
				}
			}
		}
		if(!defined('UC_API')) define('UC_API',$ucapi);
		include_once './uc_client/client.php';
		$ucinfo = dfopen($ucapi.'/index.php?m=app&a=ucinfo', 500, '', '', 1, $ucip);
		list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
		$dbcharset = strtolower($dbcharset ? str_replace('-', '', $dbcharset) : $dbcharset);
		$ucdbcharset = strtolower($ucdbcharset ? str_replace('-', '', $ucdbcharset) : $ucdbcharset);
		if($status != 'UC_STATUS_OK') {
			show_error($lang['uc_not_connetc']);
		} elseif(UC_VERSION > $ucversion) {
			show_error($lang['uc_wrong_version']);
		} elseif($dbcharset && $ucdbcharset != $dbcharset) {
			show_error($lang['uc_wrong_charset']);
		} else{
			$postdata = "m=app&a=add&ucfounder=&ucfounderpw=".urlencode($ucfounderpw)."&apptype=".urlencode($app_type)."&appname=".urlencode($app_name)."&appurl=".urlencode($app_url)."&appip=&appcharset=".$app_charset.'&appdbcharset='.$app_dbcharset.'&'.$app_tagtemplates;
			$ucconfig = dfopen($ucapi.'/index.php', 500, $postdata, '', 1, $ucip);
			if(empty($ucconfig)) {
				show_error($lang['uc_app_wrong']);
			} elseif($ucconfig == '-1') {
				show_error($lang['uc_wrong_funderpwd']);
			} else {
				list($appauthkey, $appid) = explode('|', $ucconfig);
				if(empty($appauthkey) || empty($appid)) {
					show_error($lang['uc_not_connetc']);
				}
			}
		}
		list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset) = explode('|', $ucconfig);

		$configfile = file_get_contents(CYASK_ROOT.'/config.inc.php');
		$configfile  = trim($configfile );
		$configfile  = substr($configfile , -2) == '?>' ? substr($configfile , 0, -2) : $configfile ;
		$configfile = insertconfig($configfile, "/define\('UC_CONNECT',\s*'.*?'\);/i", "define('UC_CONNECT', 'mysql');");
		$configfile = insertconfig($configfile, "/define\('UC_DBHOST',\s*'.*?'\);/i", "define('UC_DBHOST', '$ucdbhost');");
		$configfile = insertconfig($configfile, "/define\('UC_DBUSER',\s*'.*?'\);/i", "define('UC_DBUSER', '$ucdbuser');");
		$configfile = insertconfig($configfile, "/define\('UC_DBPW',\s*'.*?'\);/i", "define('UC_DBPW', '$ucdbpw');");
		$configfile = insertconfig($configfile, "/define\('UC_DBNAME',\s*'.*?'\);/i", "define('UC_DBNAME', '$ucdbname');");
		$configfile = insertconfig($configfile, "/define\('UC_DBCHARSET',\s*'.*?'\);/i", "define('UC_DBCHARSET', '$ucdbcharset');");
		$configfile = insertconfig($configfile, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "define('UC_DBTABLEPRE', '`$ucdbname`.$uctablepre');");
		$configfile = insertconfig($configfile, "/define\('UC_DBCONNECT',\s*'.*?'\);/i", "define('UC_DBCONNECT', '0');");
		$configfile = insertconfig($configfile, "/define\('UC_KEY',\s*'.*?'\);/i", "define('UC_KEY', '$appauthkey');");
		$configfile = insertconfig($configfile, "/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$ucapi');");
		$configfile = insertconfig($configfile, "/define\('UC_CHARSET',\s*'.*?'\);/i", "define('UC_CHARSET', '$uccharset');");
		$configfile = insertconfig($configfile, "/define\('UC_IP',\s*'.*?'\);/i", "define('UC_IP', '$ucip');");
		$configfile = insertconfig($configfile, "/define\('UC_APPID',\s*'?.*?'?\);/i", "define('UC_APPID', '$appid');");
		$configfile = insertconfig($configfile, "/define\('UC_PPP',\s*'?.*?'?\);/i", "define('UC_PPP', '20');");
		file_put_contents(CYASK_ROOT.'/config.inc.php',$configfile);
	//========uc end			



			include CYASK_ROOT.'/config.inc.php';




			$dbhost = 'localhost';
			$dbuser = isset($dbuser)?$dbuser:'root';
			$dbpw = isset($dbpw)?$dbpw:'';
			$dbname = isset($dbname)?$dbname:'dbname';
			$adminemail = isset($adminemail)?$adminemail:'admin@domain.com';
			$dbprefix =isset($dbprefix)?$dbprefix:'cyask_';
			

?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?=$language?>">
              <table width="700" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr bgcolor="#3A4273">
                  <td align="center" width="20%" style="color: #FFFFFF"><?=$lang['variable']?></td>
                  <td align="center" width="25%" style="color: #FFFFFF"><?=$lang['value']?></td>
                  <td align="center" width="55%" style="color: #FFFFFF"><?=$lang['comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;<?=$lang['dbhost']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbhost" value="<?=$dbhost?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbhost_comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbuser']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbuser" value="<?=$dbuser?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbuser_comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbpw']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="password" name="dbpw" value="<?=$dbpw?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbpw_comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbprefix']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbprefix" value="<?=$dbprefix?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbprefix_comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['email']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="adminemail" value="<?=$adminemail?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['email_comment']?></td>
                </tr>
				
              </table>
              <br>
              <input type="hidden" name="action" value="dbselect">
              <input type="hidden" name="saveconfig" value="1">
              <input type="submit" name="submit" value="<?=$lang['save_config']?>" style="height: 25">
              <input type="button" name="exit" value="<?=$lang['exit']?>" style="height: 25" onClick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?php
		}
		else
		{
			@include CYASK_ROOT.'/config.inc.php';
?>
        <tr>
          <td>
            <br>
            <table width="60%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center" style="color: #FFFFFF"><?=$lang['variable']?></td>
                <td align="center" style="color: #FFFFFF"><?=$lang['value']?></td>
                <td align="center" style="color: #FFFFFF"><?=$lang['comment']?></td>
              </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;<?=$lang['dbhost']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbhost" value="<?=$dbhost?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbhost_comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbuser']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbuser" value="<?=$dbuser?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbuser_comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbpw']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="password" name="dbpw" value="<?=$dbpw?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbpw_comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbprefix']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbprefix" value="<?=$dbprefix?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['dbprefix_comment']?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['email']?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="adminemail" value="<?=$adminemail?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['email_comment']?></td>
                </tr>             
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td align="center">
            <form method="post" action="?language=<?=$language?>">
              <input type="hidden" name="action" value="environment">
              <input type="submit" name="submit" value="<?=$lang['confirm_config']?>" style="height: 25">
              <input type="button" name="exit" value="<?=$lang['refresh_config']?>" style="height: 25" onClick="javascript: window.location=('?language=<?=$language?>&action=config');">
            </form>
          </td>
        </tr>
<?

		}

	}
	else
	{

?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?=$language?>">
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="<?=$lang['recheck_config']?>" style="height: 25">
              <input type="button" name="exit" value="<?=$lang['exit']?>" style="height: 25" onClick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?
	}
}
elseif($action == 'dbselect')
{

	$exist_error = FALSE;
	$write_error = FALSE;
	if(file_exists(CYASK_ROOT.'/config.inc.php'))
	{
		$fileexists = result(1, 0);
	}
	else
	{
		$fileexists = result(0, 0);
		$exist_error = TRUE;
		$config_info = $lang['config_nonexistence'];
	}
	if(is_writeable(CYASK_ROOT.'/config.inc.php'))
	{
		$filewriteable = result(1, 0);
		$config_info = $lang['choice_or_new_db'];
	}
	else
	{
		$filewriteable = result(0, 0);
		$write_error = TRUE;
		$config_info = $lang['config_unwriteable'];
	}
?>
        <tr>
          <td><b><?=$lang['current_process']?> </b><font color="#0000EE"><?=$lang['forum_db_conf']?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['check_config']?></font></b></td>
        </tr>
        <tr>
          <td>config.inc.php <?=$lang['check_existence']?> <?=$fileexists?></td>
        </tr>
        <tr>
          <td>config.inc.php <?=$lang['check_writeable']?> <?=$filewriteable?></td>
        </tr>
         <tr>
          <td>UC <?=$config_info?></td>
        </tr>
       <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"><?=$lang['show_and_edit_db_conf']?></font></b></td>
        </tr>
        <tr>
          <td align="center"><br><?=$config_info?></td>
        </tr>
         <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?=$language?>">
            <table width="40%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center" colspan="3" style="color: #FFFFFF"><?=$lang['db_set']?></td>
              </tr>
<?
	if(!$exist_error)
	{

		if(!$write_error)
		{

			if($_POST['saveconfig'] && is_writeable(CYASK_ROOT.'/config.inc.php'))
			{
				$dbhost = setconfig($_POST['dbhost']);
				$dbuser = setconfig($_POST['dbuser']);
				$dbpw = setconfig($_POST['dbpw']);
				$cookiepre = setconfig($_POST['cookiepre']);
				$dbprefix = setconfig($_POST['dbprefix']);
				$adminemail = setconfig($_POST['adminemail']);
				$configfile = file_get_contents(CYASK_ROOT.'/config.inc.php');
				$configfile  = trim($configfile );
				$configfile  = substr($configfile , -2) == '?>' ? substr($configfile , 0, -2) : $configfile ;
				$configfile = insertconfig($configfile, "/[$]dbhost\s*\=\s*[\"'].*?[\"'];/is", "\$dbhost = '$dbhost';");
				$configfile = insertconfig($configfile, "/[$]dbuser\s*\=\s*[\"'].*?[\"'];/is", "\$dbuser = '$dbuser';");
				$configfile = insertconfig($configfile, "/[$]dbpw\s*\=\s*[\"'].*?[\"'];/is", "\$dbpw = '$dbpw';");
				$configfile = insertconfig($configfile, "/[$]dbprefix\s*\=\s*[\"'].*?[\"'];/is", "\$dbprefix = '$dbprefix';");
				$configfile = insertconfig($configfile, "/[$]adminemail\s*\=\s*[\"'].*?[\"'];/is", "\$adminemail = '$adminemail';");
				file_put_contents(CYASK_ROOT.'/config.inc.php',$configfile);
			}
			include CYASK_ROOT.'/config.inc.php';
			include CYASK_ROOT.'/include/db_'.$database.'.php';
			$db = new db_sql;
			$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

			$query = $db->query("CREATE DATABASE {$dbprefix}temp", 'SILENT');
			if($db->error())
			{
				$createerror = TRUE;
			}
			else
			{
				$query = $db->query("DROP DATABASE {$dbprefix}temp", 'SILENT');
				$createerror = FALSE;
			}

			$query = $db->query("SHOW DATABASES", 'SILENT');

			$option = '';
			if($query)
			{
				while($database = $db->fetch_array($query))
				{
					if($database['Database'] != 'mysql')
					{
						$option .= '<option value="'.$database['Database'].'"' .($dbname == $database['Database'] ? ' selected' : '') . '>'.$database['Database']."</option>";
					}
				}
			}
			if(!empty($option))
			{
?>
              <tr>
              	<td bgcolor="#EEEEF6">&nbsp;
                  <input name="type" type="radio" value="2" checked style="background-color:#EEEEF6">
        	  <?=$lang['db_use_existence']?>:
                </td>
                <td bgcolor="#EEEEF6">&nbsp;
                  <select name="dbnameselect" style="width:200px"><?=$option?></select>
                </td>
              </tr>
<tr>
                <td bgcolor="#EEEEF6">&nbsp;
				<input name="type" type="radio" value="1" style="background-color:#EEEEF6">
                  <?=$lang['db_new']?>:
                </td>
                <td bgcolor="#EEEEF6">&nbsp;
                  <input type="text" name="dbname" value="" style="width:200px">
                </td>
              </tr>
<?
			}
			if($createerror && empty($option))
			{
?>
              <tr>
                <td bgcolor="#EEEEF6">&nbsp;
                  <?=$lang['choice_one_db']?>:
                </td>
                <td bgcolor="#EEEEF6">&nbsp;
                  <input type="text" name="dbname" value="<?=$dbname?>" style="width:200px">
                </td>
              </tr>
              <?
			}
?>
            </table>
           </td>
         </tr>
<?
		} else {
				@include CYASK_ROOT.'/config.inc.php';
?>
              <tr>
        	<td bgcolor="#EEEEF6">&nbsp;
                  <?=$lang['db']?>:
                </td>
                <td bgcolor="#EEEEF6">&nbsp;
                  <input type="hidden" name="dbname" value="<?=$dbname?>"><?=$dbname?>
                </td>
              </tr>
            </table>
           </td>
         </tr>
<?
		}
?>
         <tr>
	   <td align="center">
	     <br>
	     <input type="hidden" name="action" value="environment">
	     <input type="hidden" name="saveconfig" value="1">
	     <input type="submit" name="submit" value="<?=$lang['save_config']?>" style="height: 25">
	     <input type="button" name="exit" value="<?=$lang['exit']?>" style="height: 25" onClick="javascript: window.close();">
	   </td>
	 </tr>
	 </form>
<?
	}
	if($exist_error)
	{
?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?=$language?>">
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="<?=$lang['recheck_config']?>" style="height: 25">
              <input type="button" name="exit" value="<?=$lang['exit']?>" style="height: 25" onClick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?

	}
}
elseif($action == 'environment')
{
	$dbname = ($_POST['type'] == 1) ? $_POST['dbname'] : $_POST['dbnameselect'];
	$dbname = setconfig($dbname);

	$configfile = file_get_contents(CYASK_ROOT.'/config.inc.php');
	$configfile  = trim($configfile );
	$configfile  = substr($configfile , -2) == '?>' ? substr($configfile , 0, -2) : $configfile ;
	$configfile = insertconfig($configfile, "/[$]dbname\s*\=\s*[\"'].*?[\"'];/is", "\$dbname = '$dbname';");
	file_put_contents(CYASK_ROOT.'/config.inc.php',$configfile);
	include CYASK_ROOT.'/config.inc.php';

	include CYASK_ROOT.'/include/db_'.$database.'.php';
	$db = new db_sql;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

	$msg = '';
	$quit = FALSE;

	$curr_os = PHP_OS;

	if(version_compare(PHP_VERSION,'4.3.5')==-1)
	{
		$msg .= "<font color=\"#FF0000\">$lang[php_version_435]</font>\t";
		$quit = TRUE;
	}

	if(@ini_get(file_uploads))
	{
		$max_size = @ini_get(upload_max_filesize);
		$curr_upload_status = $lang['attach_enabled'].$max_size;
		$msg .= $lang['attach_enabled_info'].$max_size."\t";
	}
	else
	{
		$curr_upload_status = $lang['attach_disabled'];
		$msg .= "<font color=\"#FF0000\">$lang[attach_disabled_info]</font>\t";
	}

	$query = $db->query("SELECT VERSION()");
	$curr_mysql_version = $db->result($query, 0);
	if($curr_mysql_version < '3.23')
	{
		$msg .= "<font color=\"#FF0000\">$lang[mysql_version_323]</font>\t";
		$quit = TRUE;
	}

	$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

	if(dir_writeable('./templates'))
	{
		$curr_tpl_writeable = $lang['writeable'];
	}
	else
	{
		$curr_tpl_writeable = $lang['unwriteable'];
		$msg .= "<font color=\"#FF0000\">$lang[unwriteable_template]</font>\t";
	}

	if(dir_writeable('./askdata'))
	{
		$curr_data_writeable = $lang['writeable'];
	}
	else
	{
		$curr_data_writeable = $lang['unwriteable'];
		$msg .= "<font color=\"#FF0000\">$lang[unwriteable_askdata]</font>\t";
	}

	if(dir_writeable('./askdata/templates'))
	{
		$curr_template_writeable = $lang['writeable'];
	}
	else
	{
		$curr_template_writeable = $lang['unwriteable'];
		$msg .= "<font color=\"#FF0000\">$lang[unwriteable_askdata_template]</font>\t";
		$quit = TRUE;
	}

	if(dir_writeable('./askdata/cache'))
	{
		$curr_cache_writeable = $lang['writeable'];
	}
	else
	{
		$curr_cache_writeable = $lang['unwriteable'];
		$msg .= "<font color=\"#FF0000\">$lang[unwriteable_askdata_cache]</font>\t";
		$quit = TRUE;
	}

	if(strstr($dbprefix, '.'))
	{
		$msg .= "<font color=\"#FF0000\">$lang[tablepre_invalid]</font>\t";
		$quit = TRUE;
	}

	$db->select_db($dbname);
	if($db->error())
	{
		if($db->version() > '4.1')
		{
			$db->query("CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET $dbcharset");
		}
		else
		{
			$db->query("CREATE DATABASE IF NOT EXISTS $dbname");
		}
		if($db->error())
		{
			$msg .= "<font color=\"#FF0000\">$lang[db_invalid]</font>\t";
			$quit = TRUE;
		}
		else
		{
			$db->select_db($dbname);
			$msg .= "$lang[db_auto_created]\t";
		}
	}

	$query = $db->query("CREATE TABLE {$dbprefix}test (test TINYINT (3) UNSIGNED)", 'SILENT');
	if($db->error())
	{
		$dbpriv_createtable = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	}
	else
	{
		$dbpriv_createtable = $lang['yes'];
	}
	$query = $db->query("INSERT INTO {$dbprefix}test (test) VALUES (1)", 'SILENT');
	if($db->error()) 
	{
		$dbpriv_insert = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	} 
	else 
	{
		$dbpriv_insert = $lang['yes'];
	}
	$query = $db->query("SELECT * FROM {$dbprefix}test", 'SILENT');
	if($db->error())
	{
		$dbpriv_select = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	}
	else
	{
		$dbpriv_select = $lang['yes'];
	}
	$query = $db->query("UPDATE {$dbprefix}test SET test='2' WHERE test='1'", 'SILENT');
	if($db->error())
	{
		$dbpriv_update = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	} else {
		$dbpriv_update = $lang['yes'];
	}
	$query = $db->query("DELETE FROM {$dbprefix}test WHERE test='2'", 'SILENT');
	if($db->error())
	{
		$dbpriv_delete = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	}
	else
	{
		$dbpriv_delete = $lang['yes'];
	}
	$query = $db->query("DROP TABLE {$dbprefix}test", 'SILENT');
	if($db->error())
	{
		$dbpriv_droptable = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	}
	else
	{
		$dbpriv_droptable = $lang['yes'];
	}

	$query = $db->query("SELECT COUNT(*) FROM {$dbprefix}set", 'SILENT');
	if(!$db->error())
	{
		$msg .= "<font color=\"#FF0000\">$lang[db_not_null]</font>\t";
		$alert = " onSubmit=\"return confirm('$lang[db_drop_table_confirm]');\"";
	}
	else
	{
		$alert = '';
	}

	if($quit)
	{
		$msg .= "<font color=\"#FF0000\">$lang[install_abort]</font>";
	}
	else
	{
		$msg .= $lang['install_process'];
	}
?>
        <tr>
          <td><b><?=$lang['current_process']?> </b><font color="#0000EE"><?=$lang['check_env']?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['check_user_and_pass']?></font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="50%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center" style="color: #FFFFFF"><?=$lang['permission']?></td>
                <td align="center" style="color: #FFFFFF"><?=$lang['status']?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">CREATE TABLE</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpriv_createtable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">INSERT</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpriv_insert?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">SELECT</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpriv_select?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">UPDATE</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpriv_update?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">DELETE</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpriv_delete?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">DROP TABLE</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpriv_droptable?></td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['compare_env']?></font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="80%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center"></td>
                <td align="center" style="color: #FFFFFF"><?=$lang['env_required']?></td>
                <td align="center" style="color: #FFFFFF"><?=$lang['env_best']?></td>
                <td align="center" style="color: #FFFFFF"><?=$lang['env_current']?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['env_os']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$lang['unlimited']?></td>
                <td bgcolor="#E3E3EA" align="center">UNIX/Linux/FreeBSD</td>
                <td bgcolor="#E3E3EA" align="center"><?=$curr_os?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['env_php']?></td>
                <td bgcolor="#EEEEF6" align="center">4.3.5+</td>
                <td bgcolor="#E3E3EA" align="center">4.4.0+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_php_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['env_attach']?></td>
                <td bgcolor="#EEEEF6" align="center"3><?=$lang['unlimited']?></td>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['enabled']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_upload_status?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['env_mysql']?></td>
                <td bgcolor="#EEEEF6" align="center">3.23+</td>
                <td bgcolor="#E3E3EA" align="center">4.0.18</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_mysql_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['env_diskspace']?></td>
                <td bgcolor="#EEEEF6" align="center">2M+</td>
                <td bgcolor="#E3E3EA" align="center">50M+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_disk_space?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./templates <?=$lang['env_dir_writeable']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$lang['unlimited']?></td>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['writeable']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_tpl_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./askdata <?=$lang['env_dir_writeable']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$lang['unlimited']?></td>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['writeable']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_data_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./askdata/templates <?=$lang['env_dir_writeable']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$lang['writeable']?></td>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['writeable']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_template_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./askdata/cache <?=$lang['env_dir_writeable']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$lang['writeable']?></td>
                <td bgcolor="#E3E3EA" align="center"><?=$lang['writeable']?></td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_cache_writeable?></td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['confirm_preparation']?></font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol><?=$lang['preparation']?></ol>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['install_note']?></font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
<?

	foreach(explode("\t", $msg) as $message)
	{
		echo "              <li>$message</li>\n";
	}
	echo"            </ol>\n";

	if($quit)
	{

?>
            <center>
            <input type="button" name="refresh" value="<?=$lang['recheck_config']?>" style="height: 25" onClick="javascript: window.location=('?language=<?=$language?>&action=environment');">&nbsp;
            <input type="button" name="exit" value="<?=$lang['exit']?>" style="height: 25" onClick="javascript: window.close();">
            </center>
<?

	}
	else
	{

?>
        <form method="post" action="?language=<?=$language?>" <?=$alert?>>

        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['add_admin']?></font></b></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <table width="600" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr>
                <td bgcolor="#E3E3EA" width="30%">&nbsp;<?=$lang['username']?></td>
                <td bgcolor="#EEEEF6" width="70%"><input type="text" name="username" value="admin" size="30"> &nbsp;<?=$lang['username_note']?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" width="30%">&nbsp;email</td>
                <td bgcolor="#EEEEF6" width="70%"><input type="text" name="email" value="admin@domain.cn" size="30"> &nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" width="30%">&nbsp;<?=$lang['password']?></td>
                <td bgcolor="#EEEEF6" width="70%"><input type="text" name="password" size="30"> &nbsp;<?=$lang['password_note']?></td>
              </tr>
            </table>
            <br>
            <input type="hidden" name="action" value="install">
            <input type="submit" name="submit" value="<?=$lang['start_install']?>" style="height: 25" >&nbsp;
            <input type="button" name="exit" value="<?=$lang['exit']?>" style="height: 25" onClick="javascript: window.close();">
          </td>
        </tr>

        </form>
<?

	}
}
elseif($action == 'install')
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	include CYASK_ROOT.'/config.inc.php';
	

	include (CYASK_ROOT.'/include/db_'.$database.'.php');
	$db = new db_sql;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	$db->select_db($dbname);
?>
        <tr>
          <td><b><?=$lang['current_process']?> </b><font color="#0000EE"> <?=$lang['installing']?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['check_admin']?></font></b></td>
        </tr>
        <tr>
          <td><?=$lang['check_admin_validity']?>
<?php

	$msg = '';
	include_once(CYASK_ROOT.'/uc_client/client.php');
	list($uid,$uname,$email)= uc_get_user($username,0);
	$email	  = $_POST['email'];	
	if($uid<=0)
	{
		$uid=uc_user_register($username,$password,$email);
	}	

	switch($uid)
	{
		case -1:
		case -2:
		case -4:
		case -5:
		case -6:
			$msg=$lang['admin_username_invalid'.abs($uid)];
		break;
		default:
		break;
	}

	if($msg)
	{

?>
            ... <font color="#FF0000"><?=$lang['fail_reason']?> <?=$msg?></font></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <input type="button" name="back" value="<?=$lang['go_back']?>" onClick="javascript: history.go(-1);">
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
            <b style="font-size: 11px">Powered by <a href="http://www.cyask.com" target="_blank">Cyask <?=$version?></a> , &nbsp; Copyright &copy; Cyask 2005-2006</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
</body>
</html>

<?

		exit();
	}
	else
	{
		echo result(1, 0)."</td>\n";
		echo"        </tr>\n";
	}

?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?=$lang['select_db']?></font></b></td>
        </tr>
<?php

	if(empty($dbcharset) && $charset == 'gbk')
	{
		$dbcharset = $charset;
	}else
	{
		$dbcharset='utf8';
	}

echo"        <tr>\n";
echo"          <td>$lang[select_db] $dbname ".result(1, 0)."</td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> $lang[create_table]</font></b></td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";

$fp = fopen($sqlfile, 'rb');
$sql= fread($fp, filesize($sqlfile));
fclose($fp);
$db->select_db($dbname);
runquery($sql);
$query=$db->query("INSERT INTO {$dbprefix}members(uid,username,email,adminid,regdate,groupid) VALUES('$uid','$username','$email','1','".time()."','1')");
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> $lang[init_file]</font></b></td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";

loginit('adminlog');
loginit('errorlog');

dir_clear('./askdata/templates');
dir_clear('./askdata/cache');
@touch($lockfile);
?>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
            <font color="#FF0000"><b><?=$lang['install_succeed']?></font><br>
            <?=$lang['username']?></b> <?=$username?><b> &nbsp; <?=$lang['password']?></b> <?=$password?><br><br>
            <a href="index.php" target="_blank"><?=$lang['goto_cyask']?></a>
          </td>
        </tr>
<?

}

?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center" height="30" valign="top">
            <b style="font-size: 11px">Powered by <a href="http://www.cyask.com" target="_blank">Cyask <?=$version?></a> , &nbsp; Copyright &copy; Cyask, 2006-2007</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<iframe width="0" height="0" src="index.php"></iframe>
</body>
</html>
<?php

function show_error($msg)
{
	global $lang,$version
?>
            ... <font color="#FF0000"><?=$lang['fail_reason']?> <?=$msg?></font></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <input type="button" name="back" value="<?=$lang['go_back']?>" onClick="javascript: history.go(-1);">
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
            <b style="font-size: 11px">Powered by <a href="http://www.cyask.com" target="_blank">Cyask <?=$version?></a> , &nbsp; Copyright &copy; Cyask 2005-2006</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
</body>
</html>

<?php
exit;
}

function loginit($logfile)
{
	global $lang;

	echo $lang['init_log'].' '.$logfile;
	$fp = @fopen(CYASK_ROOT.'/askdata/'.$logfile.'.php', 'w');
	@fwrite($fp, "<?PHP exit(\"Access Denied\"); ?>\n");
	@fclose($fp);
	result();
}

function runquery($sql)
{
	global $lang, $dbcharset, $dbprefix, $db,$dbname;
	$ret = array();
	$num = 0;
	$db->select_db($dbname);
	foreach(explode(";\n", trim($sql)) as $query)
	{
		$queries = explode("\n", trim($query));
		foreach($queries as $query)
		{
			$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query)
	{
		$query = trim($query);
		$query=str_replace('cyask_',$dbprefix,$query);
		if($query)
		{
			if(substr($query, 0, 12) == 'CREATE TABLE')
			{
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				echo $lang['create_table'].' '.$name.' ... <font color="#0000EE">'.$lang['succeed'].'</font><br>';
				$db->query(createtable($query, $dbcharset));
			}
			else
			{
				$db->query($query);
			}
		}
	}
}

function result($result = 1, $output = 1)
{
	global $lang;

	if($result)
	{
		$text = '... <font color="#0000EE">'.$lang['succeed'].'</font><br>';
		if(!$output)
		{
			return $text;
		}
		echo $text;
	}
	else
	{
		$text = '... <font color="#FF0000">'.$lang['fail'].'</font><br>';
		if(!$output)
		{
			return $text;
		}
		echo $text;
	}
}

function dir_writeable($dir)
{
	if(!is_dir($dir))
	{
		@mkdir($dir, 0777);
	}
	if(is_dir($dir))
	{
		if($fp = @fopen("$dir/test.test", 'w'))
		{
			@fclose($fp);
			@unlink("$dir/test.test");
			$writeable = 1;
		}
		else
		{
			$writeable = 0;
		}
	}
	return $writeable;
}

function dir_clear($dir)
{
	global $lang;

	echo $lang['clear_dir'].' '.$dir;
	$directory = dir($dir);
	while($entry = $directory->read())
	{
		$filename = $dir.'/'.$entry;
		if(is_file($filename))
		{
			@unlink($filename);
		}
	}
	$directory->close();
	result();
}

function random($length)
{
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++)
	{
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

function createtable($sql, $dbcharset)
{
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

function setconfig($string)
{
	if(!get_magic_quotes_gpc())
	{
		$string = str_replace('\'', '\\\'', $string);
	}
	else
	{
		$string = str_replace('\"', '"', $string);
	}
	return $string;
}
function insertconfig($s, $find, $replace) {
	if(preg_match($find, $s)) {
		$s = preg_replace($find, $replace, $s);
	} else {
		$s .= "\r\n".$replace;
	}
	return $s;
}

function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].'?'.$matches['query'].(isset($matches['fragment'])? '#'.$matches['fragment']:'') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return '';
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp)) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}
?>