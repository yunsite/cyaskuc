<?php
/*
	[CYASK] (C)2007 Cyask.com QQ: 240508015
	Revision: 3.0.0 for Discuz
	Date: 2007/4/23
*/
if(!defined('IN_CYASK') || !preg_match("/[\/\\\\]admin\.php$/", $_SERVER['PHP_SELF']))
{
        exit('Access Denied');
}
$menu_parents= array();
$menu_children= array();
$menu_array=array();

if($cyask_adminid==1)
{
	$menu_parents[0] = array('id'=>0, 'name' => $lang['menu_admin_home'], 'url' => 'admin.php?admin_action=home');
	$menu_parents[1] = array('id'=>1, 'name' => $lang['menu_sort_manage'], 'url' => '');
	$menu_parents[2] = array('id'=>2, 'name' => $lang['menu_ques_manage'], 'url' => '');
	$menu_parents[3] = array('id'=>3, 'name' => $lang['menu_col_manage'], 'url' => '');
	$menu_parents[4] = array('id'=>4, 'name' => $lang['menu_user_manage'], 'url' => '');
	$menu_parents[5] = array('id'=>5, 'name' => $lang['menu_ann_manage'], 'url' => 'admin.php?admin_action=announcement');
	$menu_parents[6] = array('id'=>6, 'name' => $lang['menu_set_manage'], 'url' => 'admin.php?admin_action=var_setting');
	
	$menu_children[1][0] =array('name' => $lang['menu_sort_list'], 'url' => 'admin.php?admin_action=sort_list');
	$menu_children[1][1] =array('name' => $lang['menu_sort_add'], 'url' => 'admin.php?admin_action=sort_add');

	$menu_children[2][0] =array('name' => $lang['menu_ques_sort'], 'url' => 'admin.php?admin_action=ques_sort');
	$menu_children[2][1] =array('name' => $lang['menu_ques_nosolve'], 'url' => 'admin.php?admin_action=ques_nosolve');
	$menu_children[2][2] =array('name' => $lang['menu_ques_solve'], 'url' => 'admin.php?admin_action=ques_solve');
	$menu_children[2][3] =array('name' => $lang['menu_ques_vote'], 'url' => 'admin.php?admin_action=ques_vote');
	$menu_children[2][4] =array('name' => $lang['menu_ques_intro'], 'url' => 'admin.php?admin_action=ques_intro');
	$menu_children[2][5] =array('name' => $lang['menu_ques_close'], 'url' => 'admin.php?admin_action=ques_close');
	$menu_children[2][6] =array('name' => $lang['menu_ques_answer'], 'url' => 'admin.php?admin_action=ques_answer');

	$menu_children[3][0] =array('name' => $lang['menu_col_all'], 'url' => 'admin.php?admin_action=collect_list');
	$menu_children[3][1] =array('name' => $lang['menu_col_shared'], 'url' => 'admin.php?admin_action=collect_share');
	$menu_children[3][2] =array('name' => $lang['menu_col_private'], 'url' => 'admin.php?admin_action=collect_hidden');

	$menu_children[4][0] =array('name' => $lang['menu_user_list'], 'url' => 'admin.php?admin_action=user_list');
	$menu_children[4][1] =array('name' => $lang['menu_user_find'], 'url' => 'admin.php?admin_action=user_find');
}
elseif($cyask_adminid==2)
{
	$menu_parents[0] = array('id'=>0, 'name' => $lang['menu_admin_home'], 'url' => 'admin.php?admin_action=home');
	$menu_parents[1] = array('id'=>1, 'name' => $lang['menu_sort_manage'], 'url' => '');
	$menu_parents[2] = array('id'=>2, 'name' => $lang['menu_ques_manage'], 'url' => '');
	$menu_parents[3] = array('id'=>3, 'name' => $lang['menu_col_manage'], 'url' => '');
	$menu_parents[4] = array('id'=>4, 'name' => $lang['menu_user_manage'], 'url' => '');
	$menu_parents[5] = array('id'=>5, 'name' => $lang['menu_ann_manage'], 'url' => 'admin.php?admin_action=announcement');

	$menu_children[1][0] =array('name' => $lang['menu_sort_list'], 'url' => 'admin.php?admin_action=sort_list');
	$menu_children[1][1] =array('name' => $lang['menu_sort_add'], 'url' => 'admin.php?admin_action=sort_add');

	$menu_children[2][0] =array('name' => $lang['menu_ques_sort'], 'url' => 'admin.php?admin_action=ques_sort');
	$menu_children[2][1] =array('name' => $lang['menu_ques_nosolve'], 'url' => 'admin.php?admin_action=ques_nosolve');
	$menu_children[2][2] =array('name' => $lang['menu_ques_solve'], 'url' => 'admin.php?admin_action=ques_solve');
	$menu_children[2][3] =array('name' => $lang['menu_ques_vote'], 'url' => 'admin.php?admin_action=ques_vote');
	$menu_children[2][4] =array('name' => $lang['menu_ques_intro'], 'url' => 'admin.php?admin_action=ques_intro');
	$menu_children[2][5] =array('name' => $lang['menu_ques_close'], 'url' => 'admin.php?admin_action=ques_close');
	$menu_children[2][6] =array('name' => $lang['menu_ques_answer'], 'url' => 'admin.php?admin_action=ques_answer');

	$menu_children[3][0] =array('name' => $lang['menu_col_all'], 'url' => 'admin.php?admin_action=collect_list');
	$menu_children[3][1] =array('name' => $lang['menu_col_shared'], 'url' => 'admin.php?admin_action=collect_share');
	$menu_children[3][2] =array('name' => $lang['menu_col_private'], 'url' => 'admin.php?admin_action=collect_hidden');
	
	$menu_children[4][0] =array('name' => $lang['menu_user_list'], 'url' => 'admin.php?admin_action=user_list');
	$menu_children[4][1] =array('name' => $lang['menu_user_find'], 'url' => 'admin.php?admin_action=user_find');
}
else
{
	$menu_parents[0] = array('id'=>0, 'name' => $lang['menu_admin_home'], 'url' => 'admin.php?admin_action=home');
}

$count_parents=sizeof($menu_parents);
if(empty($status_list))
{
	if($status=='allopen')
	{
		for($i=0;$i<$count_parents;$i++)
		{
			$menu_array[$i]='1';
		}
		$status_list=implode('_', $menu_array);
	}
	
	else
	{
		for($i=0;$i<$count_parents;$i++)
		{
			$menu_array[$i]='0';
		}
		$status_list=implode('_', $menu_array);
	}
}
else
{
	$menu_array = explode('_', $status_list);
	$count_array=sizeof($menu_array);
	if($count_array!=$count_parents)
	{
		for($i=0;$i<sizeof($count_parents);$i++)
		{
			$menu_array[$i]='0';
		}
		$status_list=implode('_', $menu_array);
	}
	else
	{
		for($i=0;$i<$count_array;$i++)
		{
			if($i==$status)
			{
				$menu_array[$i]=$menu_array[$i] ? 0:1;
			}
		}
		$status_list = implode('_', $menu_array);
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset?>" />

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
.tableborder		{ background: #D6E0EF; border: 1px solid #698CC3 } 
.singleborder		{ font-size: 0px; line-height: 1px; padding: 0px; background-color: #F8F8F8 }
.smalltxt		{ font: 11px Arial, Tahoma }
.outertxt		{ font: 12px Tahoma, Verdana; color: #000000 }
.outertxt a		{ color: #000000 }
.bold			{ font-weight: bold }
.altbg1			{ background: #F8F8F8 }
.altbg2			{ background: #FFFFFF }
.maintable		{ width: 98%; background-color: #FFFFFF }
-->
</style>
</head>

<body leftmargin="3" topmargin="3">
<br /><table cellspacing="0" cellpadding="0" border="0" width="100%" align="center" style="table-layout: fixed">
<tr><td bgcolor="#698CC3">
<table width="100%" border="0" cellspacing="1" cellpadding="0">
<tr><td bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="3" cellpadding="4" class="smalltxt">
<tr><td bgcolor="#F8F8F8" align="center">
<a href="admin.php?admin_action=menu&status=allopen"><?=$lang['expand']?><b>+</b></a> &nbsp; <a href="admin.php?admin_action=menu&status=allclose"><?=$lang['close']?><b>-</b></a></td></tr>
<tr><td bgcolor="#F8F8F8">
<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
<?php
$open_type = explode('_', $status_list);
$i=0;
while($menu_parents[$i])
{
	$menu_img=$open_type[$i] ? 'minus.gif': 'plus.gif';
?>
<tr><td bgcolor="#F8F8F8"><a name="#<?php echo $i?>"></a>
<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
<tr><td width="100%" class="header"><img src="images/common/<?php echo $menu_img;?>">
<?php
if(empty($menu_parents[$i]['url']))
echo '<a href="admin.php?admin_action=menu&status_list='.$status_list.'&status='.$i.'#'.$i.'" style="color: #FFFFFF">'.$menu_parents[$i]['name'].'</a>';
else echo '<a href="'.$menu_parents[$i]['url'].'" target="main">'.$menu_parents[$i]['name'].'</a>';
?>
</td></tr>
<?php
if($open_type[$i])
{
	
	$count_children=sizeof($menu_children[$menu_parents[$i]['id']]);
	for($j=0;$j<$count_children;$j++)
	{
?>
<tr><td bgcolor="#FFFFFF" align="center" onMouseOver="this.style.backgroundColor='#F8F8F8'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
<a href="<?php echo $menu_children[$i][$j]['url'];?>" target="main"><?php echo $menu_children[$i][$j]['name'];?></a></td></tr>
<?php
	}
}
?>
</table></td></tr>
<?php
$i++;
}
?>

<tr><td bgcolor="#F8F8F8"><a name="#logout"></a>
<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
<tr><td width="100%" class="header"><img src="images/common/plus.gif">
<a href="admin.php?admin_action=logout_sys" target="main" style="color: #FFFFFF"><?php echo $lang['menu_logout_sys'];?></a></td></tr>
</table></td></tr>
</table>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
