<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
define('CURSCRIPT', 'select_sort');
$cyask_action = 20;
require_once './include/common.inc.php';

$query=$dblink->query("SELECT sid,sort1 FROM {$dbprefix}sort WHERE grade=1");
$count1=$dblink->num_rows($query);
$class1='';
$c=1;
while($row1=$dblink->fetch_array($query))
{
	$class1.='new Array("'.$row1[sid].'","'.$row1[sort1].'")';
	if($c==$count1) $class1.="\n"; else $class1.=",\n";
	$c++;
}

$query=$dblink->query("SELECT sid,sid1,sort2 FROM {$dbprefix}sort WHERE grade=2");
$count2=$dblink->num_rows($query);
$class2='';
$c=1;
while($row2=$dblink->fetch_array($query))
{
	$class2.='new Array("'.$row2[sid1].'","'.$row2[sid].'","'.$row2[sort2].'")';
	if($c==$count2) $class2.="\n"; else $class2.=",\n";
	$c++;
}
	
$query=$dblink->query("SELECT sid,sid2,sort3 FROM {$dbprefix}sort WHERE grade=3");
$count3=$dblink->num_rows($query);
$class3='';
$c=1;
while($row3=$dblink->fetch_array($query))
{
	$class3.='new Array("'.$row3[sid2].'","'.$row3[sid].'","'.$row3[sort3].'")';
	if($c==$count3) $class3.="\n"; else $class3.=",\n";
	$c++;
}

include template('select_sort');
?>