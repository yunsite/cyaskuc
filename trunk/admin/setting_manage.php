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
	header("location:admin.php?admin_action=login&backaction=$_GET[admin_action]");
}
if($admin_action=='var_setting')
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}set");
	admin_header();
?>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?=$lang['set_list']?> &nbsp;&nbsp;>> <a href="admin.php?admin_action=setting_edit"><?=$lang['set_mod']?></a></td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#ffffff">
		<td width=100 align=center><?=$lang['set_var']?></td>
		<td width=100 align=center><?=$lang['set_value']?></td>
		<td width=600 align=center><?=$lang['set_desc']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
		?>
		<tr bgcolor="#ffffff">
		<td width=100><?php echo '$'.$row['variable']?></td>
		<td width=200><?php echo $row['value'];?></td>
		<td width=500><?php echo $lang[$row['variable']];?></td>
		</tr>
		<?php
		}
		?>
		</table>
	</td></tr>
	</table>
</td></tr>
</table>
<?php
  admin_footer();
	exit();
}
elseif($admin_action=='setting_edit')
{
	if(isset($_POST['edit_submit']))
	{
		$query=$dblink->query("SELECT * FROM {$dbprefix}set");
		while($row=$dblink->fetch_array($query))
		{
			$dblink->query("UPDATE {$dbprefix}set SET value='".$_POST[$row[variable]]."' WHERE variable='".$row[variable]."'");
		}
		create_cache('variable');
		header("location:admin.php?admin_action=var_setting");
	}
	else
	{
		$query=$dblink->query("SELECT * FROM {$dbprefix}set");
		admin_header();
?>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?=$lang['set_mod']?></td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<form name="settingform" action="admin.php" method="post">
		<tr bgcolor="#ffffff">
		<td width=100 align=center><?=$lang['set_var']?></td>
		<td width=100 align=center><?=$lang['set_value']?></td>
		<td width=600 align=center><?=$lang['set_desc']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
		?>
		<tr bgcolor="#ffffff">
		<td width=100><?php echo '$'.$row['variable']?></td>
		<td width=200><input type=text name="<?php echo $row['variable'];?>" value="<?php echo $row['value'];?>"></td>
		<td width=500><?php echo $lang[$row['variable']];?></td>
		</tr>
		<?php
		}
		?>
		<tr bgcolor="#f8f8f8">
		<td width=100 colspan=3 height=30>
		<input type=hidden name=edit_submit value="1">
		<input type=hidden name=admin_action value="setting_edit">
		<input type=submit name=submit value="<?=$lang['set_update']?>">&nbsp;&nbsp;
		<input type=button name=submit2 value="<?=$lang['set_cancel']?>" onclick="history.back()" /></td>
		</tr>
		</form>
		</table>
	</td></tr>
	</table>
</td></tr>
</table>
<?php
	admin_footer();
	exit();
	}
}

else
{
	echo 'setting_manage error';
}
?>
