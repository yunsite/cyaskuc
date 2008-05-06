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
if($_GET['admin_action']=='collect_list')
{
	if(!$_GET['page']) $_GET['page']=1;
	$pagerow=20;
	if($_GET['sortname'] && $_GET['username'])
	{
		$query=$dblink->query("SELECT count(*) FROM {$dbprefix}collect WHERE username='$_GET[username]' AND sortname='$_GET[sortname]'"); 
		$qcount=$dblink->result($query,0);
		$pagecount=ceil($qcount/$pagerow);
		if ($_GET['page']>$pagecount) $_GET['page']=1;
		$start=($_GET['page']-1)*$pagerow;
		$query=$dblink->query("SELECT * FROM {$dbprefix}collect WHERE username='$_GET[username]' AND sortname='$_GET[sortname]' ORDER BY collecttime desc limit $start,$pagerow");
	}
	else
	{
		$query=$dblink->query("SELECT count(*) FROM {$dbprefix}collect"); 
		$qcount=$dblink->result($query,0);
		$pagecount=ceil($qcount/$pagerow);
		if ($_GET['page']>$pagecount) $_GET['page']=1;
		$start=($_GET['page']-1)*$pagerow;
		$query=$dblink->query("SELECT * FROM {$dbprefix}collect ORDER BY collecttime desc limit $start,$pagerow");
	}
	admin_header();
?>
<script type="text/JavaScript">
function disQstate(s)
{ 
	switch (s)
	{
		case 0:var op="<font color=#8b0000><?=$lang['ann_private']?></font>";break;
		case 1:var op="<font color=#006400><?=$lang['ann_shared']?></font>";break;
		default: var op="<?=$lang['ann_unknown']?>";
	}
	document.write(op);
}
function disCtype(s)
{ 
	switch (s)
	{
		case 1:var op="<font color=#8b0000><?=$lang['col_ques']?></font>";break;
		case 2:var op="<font color=#006400><?=$lang['col_url']?></font>";break;
		default: var op="<?=$lang['ann_unknown']?>";
	}
	document.write(op);
}
</script>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height=23><?=$lang['col_for_user']?>&nbsp;(<?php echo $qcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=400 height=26 align=center><?=$lang['col_title']?></td>
		<td width=100 align=center><?=$lang['col_username']?></td>
		<td width=100 align=center><?=$lang['col_type']?></td>
		<td width=50 align=center><?=$lang['col_attrib']?></td>
		<td width=100 align=center><?=$lang['col_add_time']?></td>
		<td width=50 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
		?>
		<tr bgcolor="#ffffff">
		<td width=400 height=26 align=left><a href="#" title="<?php echo $row['title'];?>"><?php echo $row['title'];?></a></td>
		<td width=100 align=center><a href="member.php?username=<?php echo $row['username'];?>" target="_blank"><?php echo $row['username'];?></a></td>
		<td width=100 align=center><script language="JavaScript">disCtype(<?php echo $row['ctype'];?>);</script></td>
		<td width=50 align=center><script language="JavaScript">disQstate(<?php echo $row['public'];?>);</script></td>
		<td width=100 align=center><?php echo date("y-m-d H",$row['collecttime']);?></td>
		<td width=50 align=center><a href="admin.php?admin_action=collect_del&id=<?php echo $row['id'];?>&backaction=<?php echo $_GET['admin_action'];?>&page=<?php echo $_GET['page'];?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		?>
		<tr bgcolor="#F8F8F8" height=30><td colspan="6" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $_GET[page];?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1"><?=$lang['shouye']?></a>
       <?php
		if($pagecount>1)
		{
			$start = floor($_GET['page']/10)*10;
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
				if($_GET['page']==$i)
				{
					echo '&nbsp;<font color=red>'.$i.'</font>';
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
	exit();
}
elseif($_GET['admin_action']=='collect_share')
{
	if(!$_GET['page']) $_GET['page']=1;
	$pagerow=20;
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}collect WHERE public=1"); 
	$collectcount=$dblink->result($query,0);
	$pagecount=ceil($collectcount/$pagerow);
	if ($_GET['page']>$pagecount) $_GET['page']=1;
	$start=($_GET['page']-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}collect WHERE public=1 ORDER BY collecttime desc limit $start,$pagerow");

	admin_header();
?>
<script type="text/JavaScript">
function disQstate(s)
{ 
	switch (s)
	{
		case 0:var op="<font color=#8b0000><?=$lang['ann_private']?></font>";break;
		case 1:var op="<font color=#006400><?=$lang['ann_shared']?></font>";break;
		default: var op="<?=$lang['ann_unkonwn']?>";
	}
	document.write(op);
}
function disCtype(s)
{ 
	switch (s)
	{
		case 1:var op="<font color=#8b0000><?=$lang['col_ques']?></font>";break;
		case 2:var op="<font color=#006400><?=$lang['col_url']?></font>";break;
		default: var op="<?=$lang['ann_unknown']?>";
	}
	document.write(op);
}
</script>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height=23><?=$lang['col_for_share']?>&nbsp;(<?php echo $collectcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=400 height=26 align=center><?=$lang['col_title']?></td>
		<td width=100 align=center><?=$lang['col_username']?></td>
		<td width=100 align=center><?=$lang['col_type']?></td>
		<td width=50 align=center><?=$lang['col_attrib']?></td>
		<td width=100 align=center><?=$lang['col_add_time']?></td>
		<td width=50 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
		?>
		<tr bgcolor="#ffffff">
		<td width=400 height=26 align=left><a href="#" title="<?php echo $row['title'];?>"><?php echo $row['title'];?></a></td>
		<td width=100 align=center><a href="member.php?username=<?php echo $row['username'];?>" target="_blank"><?php echo $row['username'];?></a></td>
		<td width=100 align=center><script language="JavaScript">disCtype(<?php echo $row['ctype'];?>);</script></td>
		<td width=50 align=center><script language="JavaScript">disQstate(<?php echo $row['public'];?>);</script></td>
		<td width=100 align=center><?php echo date("y-m-d H",$row['collecttime']);?></td>
		<td width=50 align=center><a href="admin.php?admin_action=collect_del&id=<?php echo $row['id'];?>&backaction=<?php echo $_GET['admin_action'];?>&page=<?php echo $_GET['page'];?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		?>
		<tr bgcolor="#F8F8F8" height=30><td colspan="6" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $_GET[page];?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1"><?=$lang['shouye']?></a>
       <?php
		if($pagecount>1)
		{
			$start = floor($_GET['page']/10)*10;
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
				if($_GET['page']==$i)
				{
					echo '&nbsp;<font color=red>'.$i.'</font>';
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
	exit();
}
elseif($_GET['admin_action']=='collect_hidden')
{
	if(!$_GET['page']) $_GET['page']=1;
	$pagerow=20;
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}collect WHERE public=0"); 
	$collectcount=$dblink->result($query,0);
	$pagecount=ceil($collectcount/$pagerow);
	if ($_GET['page']>$pagecount) $_GET['page']=1;
	$start=($_GET['page']-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}collect WHERE public=0 ORDER BY collecttime desc limit $start,$pagerow");

	admin_header();
?>
<script type="text/JavaScript">
function disQstate(s)
{ 
	switch (s)
	{
		case 0:var op="<font color=#8b0000><?=$lang['ann_private']?></font>";break;
		case 1:var op="<font color=#006400><?=$lang['ann_shared']?></font>";break;
		default: var op="<?=$lang['ann_unknown']?>";
	}
	document.write(op);
}
function disCtype(s)
{ 
	switch (s)
	{
		case 1:var op="<font color=#8b0000><?=$lang['col_ques']?></font>";break;
		case 2:var op="<font color=#006400><?=$lang['col_url']?></font>";break;
		default: var op="<?=$lang['ann_unknown']?>";
	}
	document.write(op);
}
</script>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height=23><?=$lang['col_for_private']?>&nbsp;(<?php echo $collectcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=400 height=26 align=center><?=$lang['col_title']?></td>
		<td width=100 align=center><?=$lang['col_username']?></td>
		<td width=100 align=center><?=$lang['col_type']?></td>
		<td width=50 align=center><?=$lang['col_attrib']?></td>
		<td width=100 align=center><?=$lang['col_add_time']?></td>
		<td width=50 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
		?>
		<tr bgcolor="#ffffff">
		<td width=400 height=26 align=left><a href="#" title="<?php echo $row['title'];?>"><?php echo $row['title'];?></a></td>
		<td width=100 align=center><a href="member.php?username=<?php echo $row['username'];?>" target="_blank"><?php echo $row['username'];?></a></td>
		<td width=100 align=center><script language="JavaScript">disCtype(<?php echo $row['ctype'];?>);</script></td>
		<td width=50 align=center><script language="JavaScript">disQstate(<?php echo $row['public'];?>);</script></td>
		<td width=100 align=center><?php echo date("y-m-d H",$row['collecttime']);?></td>
		<td width=50 align=center><a href="admin.php?admin_action=collect_del&id=<?php echo $row['id'];?>&backaction=<?php echo $_GET['admin_action'];?>&page=<?php echo $_GET['page'];?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		?>
		<tr bgcolor="#F8F8F8" height=30><td colspan="6" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $_GET[page];?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1"><?=$lang['shouye']?></a>
       <?php
		if($pagecount>1)
		{
			$start = floor($_GET['page']/10)*10;
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
				if($_GET['page']==$i)
				{
					echo '&nbsp;<font color=red>'.$i.'</font>';
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
	exit();
}
elseif($admin_action=='collect_edit_submit')
{
	$dblink->query("UPDATE {$dbprefix}collect SET tag='$_GET[tag]',orderid='$_GET[orderid]' WHERE tid='$_GET[tid]'");
	header("location:admin.php?admin_action=collect_sort");
}
elseif($admin_action=='collect_del')
{
	$dblink->query("DELETE FROM {$dbprefix}collect WHERE id=$_GET[id]");	
	header("location:admin.php?admin_action=collect_list");
}
else
{
	echo 'collect manage error';
}
?>
