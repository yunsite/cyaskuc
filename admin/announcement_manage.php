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
if(!$admin_login)
{
	header("location:admin.php?admin_action=login&backaction=$admin_action");
}

if($admin_action=='announcement')
{
	if(!$page) $page=1;
	$pagerow=20;
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}notice"); 
	$notecount=$dblink->result($query,0);
	$pagecount=ceil($notecount/$pagerow);
	if ($page>$pagecount) $_GET['page']=1;
	$start=($page-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}notice ORDER BY time desc limit $start,$pagerow");
	admin_header();
?>
<script type="text/JavaScript">
function disQstate(s)
{ 
	switch (s)
	{
		case 0:var op="<font color=#8b0000><?php echo $lang['ann_private'];?></font>";break;
		case 1:var op="<font color=#006400><?php echo $lang['ann_shared'];?></font>";break;
		default: var op="<?php echo $lang['ann_nuknown'];?>";
	}
	document.write(op);
}
</script>
<table cellspacing="1" cellpadding="0" width="750" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height=23><?echo $lang['ann_manage'];?>&nbsp;(<?php echo $notecount;?>)&nbsp;&nbsp;&nbsp;-&gt<a href="admin.php?admin_action=announcement_add"><u><?php echo $lang['ann_new'];?></u></a></td></tr>
	<tr bgcolor="#f8f8f8">
	<td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width="400" height="26" align="center"><?=$lang['ann_title']?></td>
		<td width="100" align="center"><?=$lang['ann_admin']?></td>
		<td width="150" align=center><?=$lang['ann_edit_time']?></td>
		<td width="50" align=center><?=$lang['edit']?></td>
		<td width="50" align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$row['edittime']=date("y-m-d H:i",$row['time']);
			if(empty($row['url']))
			{
				$manageurl='notice.php?id='.$row['id'];
			}
			else
			{
				$manageurl=$row['url'];
			}
		?>
		<tr bgcolor="#ffffff">
		<td height="26" align="center"><a href="<?php echo $manageurl;?>" target="_blank"><?php echo $row['title'];?></a></td>
		<td align="center"><a href="member.php?username=<?php echo $row['author'];?>" target="_blank"><?php echo $row['author'];?></a></td>
		<td align="center"><?php echo $row['edittime'];?></td>
		<td align="center"><a href="admin.php?admin_action=announcement_edit&id=<?php echo $row['id'];?>&page=<?php echo $page;?>"><?=$lang['edit']?></a></td>
		<td align="center"><a href="admin.php?admin_action=announcement_del&id=<?php echo $row['id'];?>&page=<?php echo $page;?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="5" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $page;?><?=$lang['ye']?>/</=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1"><?=$lang['shouye']?></a>
       <?php
		if($pagecount>1)
		{
			$start = floor($page/10)*10;
			$end = $start+9;
			if($start<1)
			{
				$start=1;
			}
			if($end>$pagecount)
			{
				$end=$pagecount;
			}
			for($i=$start; $i<=$end; $i++)
			{
				if($page==$i)
				{
					echo '&nbsp;<font color="red">'.$i.'</font>';
				}
				else
				{
					echo '<a href="admin.php?admin_action='.$admin_action.'&page='.$i.'">&nbsp;['.$i.']</a>';          
				}
			}
		}
	 ?>                                                                                                                                                                                                                                                                                                                                                                                                               
     <a href="admin.php?admin_action=<?php echo $admin_action;?>&page=<?php echo $pagecount;?>"><?=$lang['weiye']?></a>
		</td></tr>
		</table>
	</td></tr>
	</table>
</td></tr>
</table>
<?php
	admin_footer();
	exit;
}
elseif($admin_action=='announcement_add')
{
	admin_header();
?>
<table cellspacing="1" cellpadding="0" width="750" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height=23><?=$lang['ann_publish']?></td></tr>
	<tr><td bgcolor="#ffffff" height="2">&nbsp;</td></tr>
	<tr bgcolor="#f8f8f8">
	<td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<form name="f1" action="admin.php" method="post">
		<input type="hidden" name="admin_action" value="announcement_add_submit" />
		<tr bgcolor="#f8f8f8">
		<td width="120" height="35" align="center"><?=$lang['ann_title']?>:</td>
		<td width="630" align="left">&nbsp;<input type="text" name="title" size="80" maxlength="50" /></td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="120" height="26" align="center" valign="top"><?=$lang['ann_content']?>:</td>
		<td width="630" align="left">&nbsp;
		<input type="hidden" name="content" value="" />
		<script type="text/javascript" src="cyaskeditor/CyaskEditor_<?=$charset?>.js"></script>
		<script type="text/javascript">
<!--
var editor = new CyaskEditor("editor");
editor.hiddenName = "content";
editor.editorType = "simple";
editor.editorWidth = "500px";
editor.editorHeight = "300px";
editor.show();
function cyaskeditorsubmit(){editor.data();}
-->
	</script>
		</td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="120" height="35" align="center"><?=$lang['redirect']?></td>
		<td width="630" align="left">&nbsp;<input type="text" name="url" size="80" maxlength="125" /></td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="120" height="35" align="center"><?=$lang['ann_publisher']?></td>
		<td width="630" align="left">&nbsp;<input type="text" name="author" size="30" maxlength="18" value="<?php echo $cyask_user;?>" /></td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="120" height="35" align="center">&nbsp;</td>
		<td width="630" align="left">&nbsp;<input type="submit" name="submit" value="<?=$lang['ann_publish']?>" onclick="cyaskeditorsubmit()" /></td>
		</tr>
		</table>
	</td></tr>
	</table>
</td></tr>
</table>
<?php
	admin_footer();
	exit;
}
elseif($admin_action=='announcement_edit')
{
	$id=intval($_GET['id']);
	$page=intval($_GET['page']);
	$query=$dblink->query("SELECT * FROM {$dbprefix}notice WHERE id=$id");
	$row=$dblink->fetch_array($query);
	$row['content']=filters_outcontent($row['content']);
	$row['content']=htmlspecialchars($row['content']);
	admin_header();
?>
<table cellspacing="1" cellpadding="0" width="750" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height=23><?=$lang['ann_mod']?></td></tr>
	<tr><td bgcolor="#ffffff" height="2">&nbsp;</td></tr>
	<tr bgcolor="#f8f8f8">
	<td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<form name="f1" action="admin.php" method="post">
		<input type="hidden" name="admin_action" value="announcement_edit_submit" />
		<input type="hidden" name="id" value="<?php echo $row['id'];?>" />
		<input type="hidden" name="page" value="<?php echo $page;?>" />
		<tr bgcolor="#f8f8f8">
		<td width="120" height="35" align="center"><?=$lang['ann_mod']?>:</td>
		<td width="630" align="left">&nbsp;<input type="text" name="title" size="80" maxlength="50" value="<?php echo $row['title'];?>" /></td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="120" height="26" align="center" valign="top"><?=$lang['ann_content']?></td>
		<td width="630" align="left">&nbsp;
		<input type="hidden" name="content" value="<?php echo $row['content'];?>" />
		<script type="text/javascript" src="cyaskeditor/CyaskEditor_<?=$charset?>.js"></script>
		<script type="text/javascript">
<!--
var editor = new CyaskEditor("editor");
editor.hiddenName = "content";
editor.editorType = "simple";
editor.editorWidth = "500px";
editor.editorHeight = "300px";
editor.show();
function cyaskeditorsubmit(){editor.data();}
-->
	</script>
		</td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="120" height="35" align="center"><?=$lang['redirect']?></td>
		<td width="630" align="left">&nbsp;<input type="text" name="url" size="80" maxlength="125" value="<?php echo $row['url'];?>" /></td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="120" height="35" align="center"><?=$lang['publisher']?></td>
		<td width="630" align="left">&nbsp;<input type="text" name="author" size="30" maxlength="18" value="<?php echo $row['author'];?>" /></td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="120" height="35" align="center"><?=$lang['display_order']?></td>
		<td width="630" align="left">&nbsp;<input type="text" name="orderid" size="5" maxlength="3" value="<?php echo $row['orderid'];?>" /></td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="120" height="35" align="center">&nbsp;</td>
		<td width="630" align="left">&nbsp;<input type="submit" name="submit" value="<?=$lang['ann_mod']?>" onclick="cyaskeditorsubmit()" /></td>
		</tr>
		</table>
	</td></tr>
	</table>
</td></tr>
</table>
<?php
	admin_footer();
	exit;
}
elseif($admin_action=='announcement_add_submit')
{
	$nowtime=time();
	$dblink->query("INSERT INTO {$dbprefix}notice SET author='$_POST[author]',title='$_POST[title]',content='$_POST[content]',time='$nowtime',url='$_POST[url]'");
	header("location:admin.php?admin_action=announcement");
}
elseif($admin_action=='announcement_edit_submit')
{
	$id=intval($_POST['id']);
	$page=intval($_POST['page']);
	$nowtime=time();
	$content=filters_content($_POST['content']);
	$dblink->query("UPDATE {$dbprefix}notice SET author='$_POST[author]',title='$_POST[title]',content='$content',time='$nowtime',orderid='$_POST[orderid]',url='$_POST[url]' WHERE id=$id");
	header("location:admin.php?admin_action=announcement&page=$page");
}
elseif($admin_action=='announcement_del')
{
	$id=intval($_GET['id']);
	$page=intval($_GET['page']);
	$dblink->query("DELETE FROM {$dbprefix}notice WHERE id=$id");	
	header("location:admin.php?admin_action=announcement&page=$page");
}
else
{
	echo 'announcement manage error';
	exit;
}
?>
