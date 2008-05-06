<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
if(!defined('IN_CYASK'))
{
	exit('Access Denied');
}

function uc_dsetcookie($var, $value, $life = 0, $prefix = 1) {
	global $cookiedomain, $cookiepath, $_SERVER;
	setcookie($var, $value, $life ? time() + $life : 0, $cookiepath,$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
	
}

function check_submit($submit,$formhash)
{
	if(empty($submit))
	{
		return FALSE;
	}
	else
	{
		global $_SERVER;
		if($formhash == form_hash() && preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

function clear_cookies()
{
	uc_dsetcookie('auth', '');
}

function create_pic($upfile,$new_path,$width)
{
	$quality = 100;
	$image_path=$upfile; 
	$image_info=getimagesize($image_path);
	$exname='';
	//1  =  GIF,  2  =  JPG,  3  =  PNG,  4  =  SWF,  5  =  PSD,  6  =  BMP,  7  =  TIFF(intel  byte  order),  8  =  TIFF(motorola  byte  order),  9  =  JPC,  10  =  JP2,  11  =  JPX,  12  =  JB2,  13  =  SWC,  14  =  IFF
	switch($image_info[2]) 
	{
		case 1: @$image=imagecreatefromgif($image_path); $exname='gif'; break;
		case 2: @$image=imagecreatefromjpeg($image_path); $exname='jpg'; break;
		case 3: @$image=imagecreatefrompng($image_path); $exname='png'; break;
		case 6: @$image=imagecreatefromwbmp($image_path); $exname='wbmp'; break;
	}

	$T_width = $image_info[0];
	$T_height = $image_info[1];

	if(!empty($image))
	{
		$image_x=imagesx($image); 
		$image_y=imagesy($image);
	}
	else
	{
		return FALSE;
	} 
	@chmod($new_path,0777);
	if($image_x > $width)
	{
		$x=$width; 
		$y=intval($x*$image_y/$image_x); 
	}
	else
	{
		@copy($image_path,$new_path.'.'.$exname);
		return $exname;
	}
	
	$newimage=imagecreatetruecolor($x,$y);
	imagecopyresampled($newimage,$image,0,0,0,0,$x,$y,$image_x,$image_y);
	switch($image_info[2])
	{
		case 1: imagegif($newimage,$new_path.'.gif',$quality);break;
		case 2: imagejpeg($newimage,$new_path.'.jpg',$quality); break;
		case 3: imagepng($newimage,$new_path.'.png',$quality); break;
		case 6: imagewbmp($newimage,$new_path.'.wbmp',$quality); break;
	}
	
	imagedestroy($newimage);
	return $exname;
} 

function cut_str($string, $length, $dot = '...')
{
	global $charset;

	if(strlen($string) <= $length)
	{
		return $string;
	}

	$strcut = '';
	if(strtolower($charset) == 'utf-8')
	{
		$n = $tn = $noc = 0;
		while ($n < strlen($string))
		{
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126))
			{
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if ($noc >= $length)
			{
				break;
			}

		}
		if ($noc > $length)
		{
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
	}
	else
	{
		for($i = 0; $i < $length - 3; $i++)
		{
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	return $strcut.$dot;
}

function cut_tags($text)
{
	$text = preg_replace("/<html[^>]*?>/is","",$text);
	$text = preg_replace("/<head[^>]*?>.*?<\/head>/is","",$text);
	$text = preg_replace("/<title[^>]*?>.*?<\/title>/is","",$text);
	$text = preg_replace("/<style[^>]*?>.*?<\/style>/is","",$text);
	$text = preg_replace("/<script[^>]*?>.*?<\/script>/is","",$text);
	$text = preg_replace("/<form[^>]*?>.*?<\/form>/is","",$text);
	$text = preg_replace("/<body[^>]*?>/is","",$text);
	$text = preg_replace("/<\/body>/is","",$text);
	$text = preg_replace("/<\/html>/is","",$text);
	//$text = str_replace("&nbsp;"," ",$text);
	//$text = ereg_replace("(<br>|<br />)","\n",$text);
	//$text=strip_tags($text);
	$text=stripslashes($text);
	$text=trim($text);
	return $text;
}

function daddslashes($string, $force = 0)
{
	if(!$GLOBALS['magic_quotes_gpc'] || $force)
	{
		if(is_array($string))
		{
			foreach($string as $key => $val)
			{
				$string[$key] = daddslashes($val, $force);
			}
		}
		else
		{
			$string = addslashes($string);
		}
	}
	return $string;
}

function debug_info()
{
	if($GLOBALS['debug'])
	{
		global $dblink, $cyask_starttime, $debuginfo;
		$mtime = explode(' ', microtime());
		$debuginfo = array('time' => number_format(($mtime[1] + $mtime[0] - $cyask_starttime), 6), 'queries' => $dblink->querynum);
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function dhtmlspecialchars($string)
{
	if(is_array($string))
	{
		foreach($string as $key => $val)
		{
			$string[$key] = dhtmlspecialchars($val);
		}
	}
	else
	{
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}
function discuz500_code($string, $operation, $key = '')
{
	global $cyask_auth_key;
	$key 	= $key ? $key : $cyask_auth_key;

	$code 	= '';
	$len 	= strlen($key);
	$string = $operation == 'DECODE' ? base64_decode($string) : $string;
	for($i = 0; $i < strlen($string); $i += $len)
	{
		$code .= substr($string, $i, $len) ^ $key;
	}
	$code = $operation == 'ENCODE' ? str_replace('=', '', base64_encode($code)) : $code;
	return $code;
}
function discuz550_code($string, $operation, $key = '')
{

	$key = md5($key ? $key : $GLOBALS['cyask_auth_key']);
	$key_length = strlen($key);

	$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
	$string_length = strlen($string);

	$rndkey = $box = array();
	$result = '';

	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
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
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
		} else {
			return '';
		}
	} else {
		return str_replace('=', '', base64_encode($result));
	}

}
function errorlog($type, $message, $halt = 1)
{
	global $timestamp, $cyask_user;
	@$fp = fopen(CYASK_ROOT.'./askdata/errorlog.php', 'a');
	@fwrite($fp, "$timestamp\t$type\t$cyask_user\t".str_replace(array("\r", "\n"), array(' ', ' '), trim(dhtmlspecialchars($message)))."\n");
	@fclose($fp);
	if($halt)
	{
		dexit();
	}
}

function filters_title($text) 
{ 
	$text=trim($text);
    $text=strip_tags($text);
    $text=preg_replace("/\r\n|\t| |'/is", "", $text);
    $text=stripslashes($text);
  	return $text; 
}
function filters_content($text) 
{
	$text=addslashes($text);
  	return $text; 
}
function filters_outcontent($text) 
{
  	$text=stripslashes($text);
  	$text=preg_replace("/<div[^>]*?>/is","",$text);
  	$text=str_replace("</div>","<br />",$text);
  	//$text=str_replace("\r\n","",$text);
	
  	return $text; 
}
function filters_outsupply($Text) 
{ 
	$Text=trim($Text);
	$Text=stripslashes($Text);
	$Text=htmlspecialchars($Text);
	$Text=ereg_replace("\n","<br />",$Text); 
  	$Text=ereg_replace("\r","",$Text);
  	$Text=preg_replace("/\\t/is","&nbsp;&nbsp;",$Text);
  	return $Text; 
}
function filters_username($string)
{
	$length=strlen($string);
	if($length<2 || $length>18){return false;}
	for($n=0; $n<$length; $n++)
	{
		$t = ord($string[$n]);
		if( (47<$t && $t<58) || (64<$t && $t<91) || (96<$t && $t<123) || $t==45 || $t==95 || $t>126){}
		else{return false;}
	}
	return true;
}

function form_hash( $var='' )
{
	$cyask_hash_key='fdsaLKUF83Y8DAO8kLKFDSA888FDA';
	return md5($var.$cyask_hash_key);
}

function get_file_extend($file_name) 
{ 
	$retval="";
	$length=strlen($file_name);
	$pt=strrpos($file_name, "."); 
	if ($pt) $retval=substr($file_name, $pt+1, $length - $pt); 
	return $retval; 
}

function get_filetype($filename)
{ 
	if (substr_count($filename, ".") == 0)
	{ 
		return; 
	}
	else if (substr($filename, -1) == ".")
	{
		return; 
	}
	else
	{ 
		$filetype = strrchr ($filename, "."); 
		$filetype = substr($filetype, 1); 
		return $filetype; 
	} 
}

function get_grade($value)
{
	global $tpldir,$styleid;
	include language('templates',$tpldir,$styleid);
	
	if($value<=100)
	{
		$name=$lang['gradename1']; $grade=$lang['grade1'];
	}
	if($value>100 && $value<=500)
	{
		$name=$lang['gradename2']; $grade=$lang['grade2'];
	}
	if($value>500 && $value<=1000)
	{
		$name=$lang['gradename2']; $grade=$lang['grade3'];
	}
	if($value>1000 && $value<=2500)
	{
		$name=$lang['gradename3']; $grade=$lang['grade4'];
	}
	if($value>2500 && $value<=5000)
	{
		$name=$lang['gradename3']; $grade=$lang['grade5'];
	}
	if($value>5000 && $value<=8000)
	{
		$name=$lang['gradename4']; $grade=$lang['grade6'];
	}
	if($value>8000 && $value<=12000)
	{
		$name=$lang['gradename4']; $grade=$lang['grade7'];
	}
	if($value>12000 && $value<=16000)
	{
		$name=$lang['gradename5']; $grade=$lang['grade8'];
	}
	if($value>16000 && $value<=20000)
	{
		$name=$lang['gradename5']; $grade=$lang['grade9'];
	}
	if($value>20000 && $value<=25000)
	{
		$name=$lang['gradename6']; $grade=$lang['grade10'];
	}
	if($value>25000 && $value<=35000)
	{
		$name=$lang['gradename6']; $grade=$lang['grade11'];
	}
	if($value>35000 && $value<=50000)
	{
		$name=$lang['gradename7']; $grade=$lang['grade12'];
	}
	if($value>50000 && $value<=80000)
	{
		$name=$lang['gradename7']; $grade=$lang['grade13'];
	}
	if($value>80000 && $value<=120000)
	{
		$name=$lang['gradename8']; $grade=$lang['grade14'];
	}
	if($value>120000 && $value<=180000)
	{
		$name=$lang['gradename8']; $grade=$lang['grade15'];
	}
	if($value>180000 && $value<=250000)
	{
		$name=$lang['gradename9']; $grade=$lang['grade16'];
	}
	if($value>250000 && $value<=400000)
	{
		$name=$lang['gradename9']; $grade=$lang['grade17'];
	}
	if($value>400000)
	{
		$name=$lang['gradename10']; $grade=$lang['grade18'];
	}
	return array("shenfen"=>$name,"grade"=>$grade);
}

function get_pages($currentpage,$pagecount,$parameter = '')
{
	global $PHP_SELF;
	
	$start = $currentpage-4;
	$end   = $currentpage+5;
	if($start<1) $start=1;
	if($currentpage<5 && $pagecount>=10) $end=10;
	if($end>$pagecount) $end=$pagecount;
	$pagelinks='';
	for($i=$start; $i<=$end; $i++)
	{
		if($currentpage==$i)
		{
			$pagelinks.=$i.'&nbsp;';
		}
		else
		{
			$pagelinks.='<a href="'.$PHP_SELF.'?'.$parameter.'&page='.$i.'">['.$i.']</a>&nbsp;';          
		}
	}
	return $pagelinks;
}

function get_referer($default = './')
{
	$referer='';
	if(isset($_SERVER['HTTP_REFERER']))
	{
		$referer = preg_replace("/([\?&])((sid\=[a-z0-9]{6})(&|$))/i", '\\1', $_SERVER['HTTP_REFERER']);
		$referer = substr($referer, -1) == '?' ? substr($referer, 0, -1) : $referer;
	} 
	else 
	{
		$referer = $default;
	}

	if(!preg_match("/(\.php|[a-z]+(\-\d+)+\.html)/", $referer) || strpos($referer, 'login.php'))
	{
		$referer = $default;
	}
	return $referer;
}

function get_weeks()
{
	global $timestamp;
	$times=$timestamp-1152460800; 
	$weeks=ceil($times/604800);
	return $weeks;
}

function language($file,$tpldir='templates/default',$styleid=1)
{
	global $charset;
	if(!in_array($charset,array('gbk','utf-8')))$charset='gbk';
	$templateid = $styleid;
	
	$languagepack = CYASK_ROOT.'./'.$tpldir.'/'.$file.'.'.$charset.'.lang.php';
	if(file_exists($languagepack))
	{
		return $languagepack;
	}
	return FALSE;
}
function login_check() 
{
	global $dblink, $dbprefix, $onlineip, $timestamp;
	$query = $dblink->query("SELECT count, lastupdate FROM {$dbprefix}failedlogins WHERE ip='$onlineip'");
	if($login = $dblink->fetch_array($query))
	{
		if($timestamp - $login['lastupdate'] > 900) 
		{
			return 3;
		} 
		elseif($login['count'] < 5) 
		{
			return 2;
		} 
		else 
		{
			return 0;
		}
	} 
	else
	{
		return 1;
	}
}

function login_failed($permission) 
{
	global $dblink, $dbprefix, $onlineip, $timestamp;
	switch($permission) 
	{
		case 1:	$dblink->query("REPLACE INTO {$dbprefix}failedlogins (ip, count, lastupdate) VALUES ('$onlineip', '1', '$timestamp')");
			break;
		case 2: $dblink->query("UPDATE {$dbprefix}failedlogins SET count=count+1, lastupdate='$timestamp' WHERE ip='$onlineip'");
			break;
		case 3: $dblink->query("UPDATE {$dbprefix}failedlogins SET count='1', lastupdate='$timestamp' WHERE ip='$onlineip'");
			$dblink->query("DELETE FROM {$dbprefix}failedlogins WHERE lastupdate<$timestamp-901", 'UNBUFFERED');
			break;
	}
}

function quescrypt($questionid, $answer)
{
	return $questionid > 0 && $answer != '' ? substr(md5($answer.md5($questionid)), 16, 8) : '';
}

function rand_code($length, $numeric = 0)
{
	mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}

function set_cookie($var, $value, $life = 0, $prefix = 1)
{
	uc_dsetcookie($var,$value,86400);
}

function show_message($show_message, $url_forward = '')
{
	global $charset,$site_name,$cyask_user,$url,$tpldir,$styleid,$styledir,$syninfo;
	$extrahead = $url_forward ? '<meta http-equiv="refresh" content="3 url='.$url_forward.'">' : '';
	include language('messages',$tpldir,$styleid);
	$show_message= $lang[$show_message] ? $lang[$show_message] : $lang['undefined_action'];
	include template('show_message','messages');
	exit;
}

function template($file, $language='templates')
{
	global $tplrefresh, $tpldir, $styleid, $timestamp;

	$tpldir = $tpldir ? $tpldir : 'templates/default';
	$templateid = $styleid ? $styleid : 1; 

	$tplfile = CYASK_ROOT.'./'.$tpldir.'/'.$file.'.html';
	$objfile = CYASK_ROOT.'./askdata/templates/'.$templateid.'_'.$file.'.tpl.php';
	
	if($tplrefresh == 1 || ($tplrefresh > 1 && substr($timestamp, -1) > $tplrefresh))
	{
		if(@filemtime($tplfile) > @filemtime($objfile))
		{
			require_once CYASK_ROOT.'./include/template.func.php';
			parse_template($file, $language, $tpldir, $templateid);
		}
	}
	return $objfile;
}


function get_score($uid)
{
	global $dblink, $dbprefix,$scorestorage;
	
	$query=$dblink->query("SELECT scores FROM {$dbprefix}members WHERE uid=$uid");
	$score=$dblink->result($query,0);
	$score=intval($score);
	return $score;
}
function add_score($uid, $score)
{
	global $dblink, $dbprefix,$scorestorage;
	$score=intval($score);
	$dblink->query("UPDATE {$dbprefix}members SET scores=scores+{$score} WHERE uid=$uid");
}
function sub_score($uid, $score)
{
	global $dblink, $dbprefix,$scorestorage;
	$score=intval($score);
	$dblink->query("UPDATE {$dbprefix}members SET scores=scores-{$score} WHERE uid=$uid");
}
function create_cache($cachename)
{
	global $dblink,$dbprefix;
	$prefix='cache_';
	$cachedata = '';
	
	if($cachename=='variable')
	{
		$query = $dblink->query("SELECT * FROM {$dbprefix}set");
		$cachedata.="";
		while($row = $dblink->fetch_array($query))
		{
			if($row['number'])
			{
				$cachedata.="\$".$row['variable']." = '".$row['value']."';\n";
			}
			else
			{
				$cachedata.="\$".$row['variable']." = ".intval($row['value']).";\n";
			}
		}
	}
	elseif($cachename=='style')
	{
		$query = $dblink->query("SELECT templateid,name,tpldir,styledir FROM {$dbprefix}tpl ORDER BY templateid");
		$num=$dblink->num_rows($query);
		$cachedata.="\$_DCACHE['style'] = array("."\n";
		$i=1;
		while($row = $dblink->fetch_array($query))
		{
			$cachedata.=$row['templateid']." => array("."\n";
			foreach($row as $key => $val)
			{
				//$val=addslashes($val);
				if($key=='styledir')
				$cachedata .= "'$key' => '$val'"."\n";
				else
				$cachedata .= "'$key' => '$val',"."\n";
			}
			if($i==$num)
				$cachedata .=")\n";
			else
				$cachedata .="),\n";
			$i++;
		}
		$cachedata .=");\n";
	}
	else
	{
		exit('cachename error !');
	}
	$dir = CYASK_ROOT.'./askdata/cache/';
	if(!is_dir($dir))
	{
			@mkdir($dir, 0777);
	}
	if(@$fp = fopen("$dir$prefix$cachename.php", 'w'))
	{
		fwrite($fp, "<?php\n//Cyask cache file\n//Created on ".date("Y-m-d H:i:s")."\n\n$cachedata?>");
		fclose($fp);
	}
	else
	{
		exit('Can not write to cache files, please check directory ./askdata/ and ./askdata/cache/ .');
	}
}

function update_cache($cachename)
{
	
	$dir = CYASK_ROOT.'./askdata/cache/';
	if(!is_dir($dir))
	{
			@mkdir($dir, 0777);
	}
	if(@$fp = fopen("$dir$prefix$cachename.php", 'w'))
	{
		if(flock($fd,LOCK_EX))
		{
			fwrite($fp, "<?php\n//Cyask cache file\n//Created on ".date("Y-m-d H:i:s")."\n\n$cachedata?>");
			flock($fd,LOCK_UN);
		}
		else
		{
			echo 'can not lock cache file';
			exit;
		}
		fclose($fp);
	}
	else
	{
		exit('Can not write to cache files, please check directory ./askdata/ and ./askdata/cache/ .');
	}
}



?>