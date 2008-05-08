<?php
define('UC_VERSION', '1.0.0');

define('API_DELETEUSER', 1);
define('API_RENAMEUSER', 1);
define('API_GETTAG', 0);
define('API_SYNLOGIN', 1);
define('API_SYNLOGOUT', 1);
define('API_UPDATEPW', 1);
define('API_UPDATEBADWORDS', 1);
define('API_UPDATEHOSTS', 1);
define('API_UPDATEAPPS', 1);
define('API_UPDATECLIENT', 1);
define('API_UPDATECREDIT', 1);
define('API_GETCREDITSETTINGS', 1);
define('API_UPDATECREDITSETTINGS', 1);

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

error_reporting(7);
set_magic_quotes_runtime(0);

define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

define('IN_DISCUZ', TRUE);
define('DISCUZ_ROOT', substr(dirname(__FILE__), 0, -3));
define('UC_CLIENT_ROOT', DISCUZ_ROOT.'./uc_client/');
define('IN_CYASK', true);

$_DCACHE = array();

require_once DISCUZ_ROOT.'./config.inc.php';

$code = $_GET['code'];
parse_str(authcode($code, 'DECODE', UC_KEY), $get);
if(MAGIC_QUOTES_GPC) {
	foreach($get as $key=>$val) {
		$get[$key] = stripslashes($val);
	}
}
define('API_DEBUG',1);
if(defined('API_DEBUG') && API_DEBUG)
{
	$logfile=DISCUZ_ROOT.'./api/api.log';
	$get['debu_url']=$_SERVER['QUERY_STRING'];
	!file_exists($logfile) && @touch(DISCUZ_ROOT.'./api/api.log');
	$str=file_get_contents($logfile);
	$str=date('Y-m-d H:i:s')."\n".var_export($get,TRUE)."\n\n".$str;
	@file_put_contents($logfile,$str);
	unset($str);
}
if(time() - $get['time'] > 3600) {
	exit(time().'Authracation has expiried');
}
if(empty($get)) {
	exit('Invalid Request');
}
$action = $get['action'];
$timestamp = time();

if($action == 'test') {

	exit(API_RETURN_SUCCEED);

}elseif($action == 'deleteuser') {

	!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
	$ids=$get['ids'];
	if($ids)
	{
		require_once DISCUZ_ROOT.'./include/db_'.$database.'.php';
		$dblink = new db_sql;
		$dblink->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
		unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
		$dblink->query("DELETE FROM {$dbprefix}members WHERE uid in ({$ids})");
	}
	exit(API_RETURN_SUCCEED);

}elseif($action == 'synlogin' && $_GET['time'] == $get['time']) {

	!API_SYNLOGIN && exit(API_RETURN_FORBIDDEN);

	if(!empty($get['uid']))
	{
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		dsetcookie('auth', authcode($get['uid']."\t".$get['username']."\t".$get['email'], 'ENCODE'), 86400 * 365);
	}
} elseif($action == 'synlogout') {

	!API_SYNLOGOUT && exit(API_RETURN_FORBIDDEN);

	header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		dsetcookie('auth', '', -86400 * 10);
	
} elseif($action == 'renameuser') {

	!API_RENAMEUSER && exit(API_RETURN_FORBIDDEN);
	$uid = $get['uid'];

	
	exit(API_RETURN_SUCCEED);

}elseif($action == 'gettag') {

	!API_GETTAG && exit(API_RETURN_FORBIDDEN);

	
	exit(API_RETURN_SUCCEED);

} elseif($action == 'updateclient') {

	!API_UPDATECLIENT && exit(API_RETURN_FORBIDDEN);

	!API_UPDATECLIENT && exit(API_RETURN_FORBIDDEN);

	$post = uc_unserialize(file_get_contents('php://input'));
	$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/settings.php';
	$fp = fopen($cachefile, 'w');
	$s = "<?php\r\n";
	$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
	fwrite($fp, $s);
	fclose($fp);
	exit(API_RETURN_SUCCEED);

	exit(API_RETURN_SUCCEED);

} elseif($action == 'updatepw') {

	!API_UPDATEPW && exit(API_RETURN_FORBIDDEN);
	require_once("./member/config.php");
	
	$username = $get['username'];
	$password = $get['password'];

	exit(API_RETURN_SUCCEED);

} elseif($action == 'updatebadwords') {

	!API_UPDATEBADWORDS && exit(API_RETURN_FORBIDDEN);

	$post = uc_unserialize(file_get_contents('php://input'));	

	exit(API_RETURN_SUCCEED);

} elseif($action == 'updatehosts') {

	!API_UPDATEHOSTS && exit(API_RETURN_FORBIDDEN);

	$post = uc_unserialize(file_get_contents('php://input'));
	$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/hosts.php';
	$fp = fopen($cachefile, 'w');
	$s = "<?php\r\n";
	$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
	fwrite($fp, $s);
	fclose($fp);
	exit(API_RETURN_SUCCEED);

}elseif($action == 'updateapps') {

	!API_UPDATEAPPS && exit(API_RETURN_FORBIDDEN);

	$post = uc_unserialize(file_get_contents('php://input'));
	$UC_API = $post['UC_API'];
	unset($post['UC_API']);

	$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/apps.php';
	$fp = fopen($cachefile, 'w');
	$s = "<?php\r\n";
	$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
	fwrite($fp, $s);
	fclose($fp);

	if(is_writeable(DISCUZ_ROOT.'./config.inc.php')) {
		$configfile = trim(file_get_contents(DISCUZ_ROOT.'./config.inc.php'));
		$configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
		$configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
		if($fp = @fopen(DISCUZ_ROOT.'./config.inc.php', 'w')) {
			@fwrite($fp, trim($configfile));
			@fclose($fp);
		}
	}

	exit(API_RETURN_SUCCEED);

}elseif($action == 'updateclient') {

	!API_UPDATECLIENT && exit(API_RETURN_FORBIDDEN);

	$post = uc_unserialize(file_get_contents('php://input'));
	$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/settings.php';
	$fp = fopen($cachefile, 'w');
	$s = "<?php\r\n";
	$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
	fwrite($fp, $s);
	fclose($fp);
	exit(API_RETURN_SUCCEED);

} elseif($action == 'updatecredit') {

	!UPDATECREDIT && exit(API_RETURN_FORBIDDEN);
	$credit = intval($get['credit']);
	$amount = intval($get['amount']);
	$uid = intval($get['uid']);
	require_once DISCUZ_ROOT.'./include/db_'.$database.'.php';
	$dblink = new db_sql;
	$dblink->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	$dblink->query("UPDATE {$dbprefix}members SET scores=scores+'$amount' WHERE uid='$uid'");
	if($amount>0)
	{
		$add=$amount;
		$minus=0;
	}else
	{
		$add=0;
		$minus=$amount;
	}
	$query=$dblink->query("SEELCT scores FROM {$dbprefix}members WHERE uid='$uid'");
	$row=$dblink->fetch_array($query);
	$time=time();
	$scores=$row['scores'];
	unset($query,$row);
	$dblink->query("INSERT INTO {$dbprefix}scorelog(uid,optime,add,minus,op,scores,opuid) VALUES('$uid','$time','$add','$minus','$op','$scores','$opuid')");
	
	exit(API_RETURN_SUCCEED);

} elseif($action == 'getcreditsettings') {

	!GETCREDITSETTINGS && exit(API_RETURN_FORBIDDEN);
	include_once DISCUZ_ROOT.'templates/default/templates.'.$charset.'.lang.php';
	$credits=array(
        '1' => array($lang['menu_score'],''),
		);	
	echo uc_serialize($credits);

} elseif($action == 'updatecreditsettings') {

	!API_UPDATECREDITSETTINGS && exit(API_RETURN_FORBIDDEN);
	$outextcredits = array();
	foreach($get['credit'] as $appid => $credititems) {
		if($appid == UC_APPID) {
			foreach($credititems as $value) {
				$outextcredits[$value['appiddesc'].'|'.$value['creditdesc']] = array(
					'creditsrc' => $value['creditsrc'],
					'title' => $value['title'],
					'unit' => $value['unit'],
					'ratio' => $value['ratio']
				);
			}
		}
	}
	$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/creditsettings.php';
	$fp = fopen($cachefile, 'w');
	$s = "<?php\r\n";
	$s .= '$_CACHE[\'creditsettings\'] = '.var_export($outextcredits, TRUE).";\r\n";
	fwrite($fp, $s);
	fclose($fp);
	exit(API_RETURN_SUCCEED);

} else {

	exit(API_RETURN_FAILED);

}



function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function dsetcookie($var, $value, $life = 0, $prefix = 1) {
	global $cookiedomain, $cookiepath, $_SERVER;
	setcookie($var, $value, $life ? time() + $life : 0, $cookiepath,$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
	
}
function uc_serialize($arr, $htmlon = 0) {
	include_once UC_CLIENT_ROOT.'./lib/xml.class.php';
	return xml_serialize($arr, $htmlon);
}

function uc_unserialize($s) {
	include_once UC_CLIENT_ROOT.'./lib/xml.class.php';
	return xml_unserialize($s);
}
function language($file,$tpldir='templates/default',$styleid=1)
{
	global $charset;
	$templateid = $styleid;
	$languagepack = CYASK_ROOT.'./'.$tpldir.'/'.$file.'.'.$charset.'.lang.php';
	if(file_exists($languagepack))
	{
		return $languagepack;
	}
	return FALSE;
}

?>