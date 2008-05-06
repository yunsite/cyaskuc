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
if($_GET['admin_action']=='user_list')
{
	if(!$_GET['page']) $_GET['page']=1;
	$pagerow=20;
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}members"); 
	$mcount=$dblink->result($query,0);
	$pagecount=ceil($mcount/$pagerow);
	if ($_GET['page']>$pagecount) $_GET['page']=1;
	$start=($_GET['page']-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}members order by uid DESC limit $start,$pagerow");
	admin_header();
?>
<script type="text/JavaScript">
function deleteuser()
{
	if( !confirm("<?=$lang['user_confirm_delete']?>")) return false;
	else return true;
}
</script>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?=$lang['user_reg']?>&nbsp;(<?php echo $mcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=100 height=26 align=center><?=$lang['user_username']?></td>
		<td width=45 align=center><?=$lang['gender']?></td>
		<td width=80 align=center><?=$lang['user_rettime']?></td>
		<td width=100 align=center>email</td>
		<td width=100 align=center>qq</td>
		<td width=100 align=center>msn</td>
		<td width=100 align=center><?=$lang['user_bdate']?></td>
		<td width=45 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$row['gender']=($row['gender']==0) ? $lang['male']:$lang['female'];
			$row['qq']=$row['qq'] ? $row['qq']:'n/a';
			$row['msn']=$row['msn'] ? $row['msn']:'n/a';
		?>
		<tr bgcolor="#ffffff">
		<td width=100 align=center><a href="member.php?uid=<?php echo $row['uid'];?>" title="<?php echo $row['email'];?>" target="_blank"><?php echo $row['username'];?></a></td>
		<td width=45 align=center><?php echo $row['gender']?></td>
		<td width=80 align=center><?php echo date("Y-m-d",$row['regdate']);?></td>
		<td width=100 align=center><?php echo $row['email'];?></td>
		<td width=100 align=center><?php echo $row['qq'];?></td>
		<td width=100 align=center><?php echo $row['msn'];?></td>
		<td width=100 align=center><?php echo $row['bday'];?></td>
		<td width=45 align=center><a href="admin.php?admin_action=user_del&uid=<?php echo $row['uid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $_GET['page'];?>" onclick="if( !deleteuser() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		?>
		<tr bgcolor="#F8F8F8" height=30><td colspan=12 align=center>
		<font color="#000080"><?=$lang['di']?><?php echo $_GET[page];?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1" title="<?=$lang['shouye']?>"><?=$lang['shouye']?></a>
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

elseif($admin_action=='user_find')
{
	if($_GET['ctype']=='find_submit')
	{
		if(!$_GET['page']) $_GET['page']=1;
		$pagerow=20;
		$username=trim($_GET['username']);
		
		$query=$dblink->query("SELECT count(*) FROM {$dbprefix}members WHERE username LIKE '%$username%'"); 
		$mcount=$dblink->result($query,0);
		$pagecount=ceil($mcount/$pagerow);
		if ($_GET['page']>$pagecount) $_GET['page']=1;
		$start=($_GET['page']-1)*$pagerow;
		$query=$dblink->query("SELECT uid,username,regdate,email,bday,qq,msn FROM {$dbprefix}members WHERE username LIKE '%$username%'  ORDER BY regdate desc limit $start,$pagerow");
		
	admin_header();
?>
<script type="text/JavaScript">
function deleteuser()
{
	if( !confirm("<?=$lang['user_confirm_delete']?>")) return false;
	else return true;
}
</script>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?=$lang['user_fined']?>&nbsp;(<?php echo $mcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=100 height=26 align=center><?=$lang['user_username']?></td>
		<td width=45 align=center><?=$lang['gender']?></td>
		<td width=80 align=center><?=$lang['user_rettime']?></td>
		<td width=100 align=center>email</td>
		<td width=100 align=center>qq</td>
		<td width=100 align=center>msn</td>
		<td width=100 align=center><?=$lang['user_bdate']?></td>
		<td width=45 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$row['gender']=($row['gender']==0) ? $lang['male']:$lang['female'];
			$row['qq']=$row['qq'] ? $row['qq']:'n/a';
			$row['msn']=$row['msn'] ? $row['msn']:'n/a';
		?>
		<tr bgcolor="#ffffff">
		<td width=100 align=center><a href="member.php?uid=<?php echo $row['uid'];?>" title="<?php echo $row['email'];?>" target="_blank"><?php echo $row['username'];?></a></td>
		<td width=45 align=center><?php echo $row['gender']?></td>
		<td width=80 align=center><?php echo date("y-n-j",$row['regdate']);?></td>
		<td width=100 align=center><?php echo $row['email'];?></td>
		<td width=100 align=center><?php echo $row['qq'];?></td>
		<td width=100 align=center><?php echo $row['msn'];?></td>
		<td width=100 align=center><?php echo $row['bday'];?></td>
		<td width=45 align=center><a href="admin.php?admin_action=user_del&uid=<?php echo $row['uid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $_GET['page'];?>" onclick="if( !deleteuser() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		?>
		<tr bgcolor="#F8F8F8" height=30><td colspan=12 align=center>
		<font color="#000080"><?=$lang['di']?><?php echo $_GET[page];?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&ctype=find_submit&username=<?php echo $username;?>&page=1" title=""><?=$lang['shouye']?></a>
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
					echo '<a href="admin.php?admin_action='.$admin_action.'&ctype=find_submit&username='.$username.'&page='.$i.'">&nbsp;['.$i.']</a>';          
				}
			}
		}
	 ?>                                                                                                                                                                                                                                                                                                                                                                                                                
     <a href="admin.php?admin_action=<?php echo $admin_action;?>&ctype=find_submit&username=<?php echo $username;?>&page=<?php echo $pagecount;?>"><?=$lang['weiye']?></a>
		</td></tr>
		</table>
	</td></tr>
	</table>
</td></tr>
</table>
<?php
	}
	else
	{
		admin_header();
?>
<script language="javascript">
function check_sortform(f)
{
 	if(f.username.value =="")
 	{
  		alert("<?=$lang['user_find_username']?>");
		f.username.focus();
		return false;
 	}
}
</script>
<br><br>
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td colspan=2 height="22"><?=$lang['user_find']?></td></tr>
	<form method="get" action="admin.php" name="sortForm" onSubmit="return check_sortform(this)">
    <tr bgcolor="#F8F8F8"><td colspan="2" height="20"></td></tr>
     <tr bgcolor="#ffffff"> 
      <td width="80" height="25" align="right"><?=$lang['user_username']?>:</td>
      <td align="left">
	<input name="username" type="text" size="22" maxlength="18">
     </td>
    </tr>
    <tr bgcolor="#F8F8F8"> 
     <td width="80" height="25" align="right">&nbsp;</td>
     <td align="left">
      <input name="admin_action" type="hidden" value="user_find">
      <input name="ctype" type="hidden" value="find_submit">
      <input type="submit" value="<?=$lang['user_submit']?>" name="B1">&nbsp;&nbsp;
      <input onclick="javascript:history.back();" type="button" value="<?=$lang['user_back']?>"> 
      </td>
    </tr>
     <tr bgcolor="#ffffff"><td colspan=2 height="20"></td></tr>
  </form>
	</table>
</td></tr>
</table>
<?php
	}
	admin_footer();
	exit();
}
elseif($admin_action=='user_grade_manage')
{
	if($_GET['ctype']=='grade_submit')
	{
		$new_adminid=intval($_GET['adminid']);
		$dblink->query("UPDATE $dbprefix"."members SET adminid='$new_adminid' WHERE username='$_GET[username]'");
		header("location:admin.php?admin_action=$_GET[backaction]&page=$_GET[page]");
	}
	else
	{
		$query=$dblink->query("SELECT adminid FROM $dbprefix"."members WHERE username='$_GET[username]'");
		$row=$dblink->fetch_array($query);
		admin_header();
?>
<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td colspan=2 height="22"><?=$lang['user_pri_mod']?></td></tr>
	<form method="get" action="admin.php" name="sortForm" onSubmit="return check_sortform(this)">
    <tr bgcolor="#F8F8F8"><td colspan="2" height="20"></td></tr>
     <tr bgcolor="#ffffff"> 
      <td width="80" height="25" align="right"><?=$lang['user_user_name']?></td>
      <td align="left"><?php echo $_GET['username'];?></td>
    </tr>
      <tr bgcolor="#ffffff"> 
     <td width="80" height="25" align="right"><?=$lang['user_pri_group']?></td>
     <td align="left">
	<select name="adminid" size="5">
	<option value="0"><?=$lang['normal']?></option>
	<option value="4"><?=$lang['banned']?></option>
	<option value="3"><?=$lang['modopt']?></option>
	<option value="2"><?=$lang['admin']?></option>
	<option value="1"><?=$lang['suadmin']?></option>

	</select>
     </td>
    </tr>
    <tr bgcolor="#F8F8F8"> 
     <td width="80" height="25" align="right">&nbsp;</td>
     <td align="left">
      <input name="admin_action" type="hidden" value="user_grade_manage" />
      <input name="ctype" type="hidden" value="grade_submit" />
      <input name="username" type="hidden" value="<?php echo $_GET['username'];?>" />
      <input name="backaction" type="hidden" value="<?php echo $_GET['backaction'];?>" />
       <input name="page" type="hidden" value="<?php echo $_GET['page'];?>" />
      <input type="submit" value="<?=$lang['user_submit']?>" name="B1">&nbsp;&nbsp;
      <input onclick="javascript:history.back();" type="button" value="<?=$lang['user_back']?>"> 
      </td>
    </tr>
     <tr bgcolor="#ffffff"><td colspan=2 height="20"></td></tr>
  </form>
	</table>
</td></tr>
</table>
<?php
	admin_footer();
	exit();
	}
}
elseif($admin_action=='user_score_manage')
{
	if($_GET['ctype']=='score_submit')
	{
		$score=intval($_GET['score']);
		$dblink->query("UPDATE $dbprefix"."members SET scores='$score' WHERE username='$_GET[username]'");
		header("location:admin.php?admin_action=$_GET[backaction]&page=$_GET[page]");
	}
	else
	{
		$query=$dblink->query("SELECT scores AS score FROM $dbprefix"."members WHERE username='$_GET[username]'");
		$row=$dblink->fetch_array($query);
		admin_header();
?>
<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td colspan=2 height="22"><?=$lang['user_mod_score']?></td></tr>
	<form method="get" action="admin.php" name="sortForm">
    <tr bgcolor="#F8F8F8"><td colspan="2" height="20"></td></tr>
     <tr bgcolor="#ffffff"> 
      <td width="80" height="25" align="right"><?=$lang['user_username']?></td>
      <td align="left"><?php echo $_GET['username'];?></td>
    </tr>
     <tr bgcolor="#ffffff"> 
     <td width="80" height="25" align="right"><?=$lang['user_score']?>:</td>
     <td align="left">
	<input name="score" type="text" size="10" maxlength="10" value="<?php echo $row['score'];?>" />
     </td>
    </tr>
    <tr bgcolor="#F8F8F8"> 
     <td width="80" height="25" align="right">&nbsp;</td>
     <td align="left">
      <input name="admin_action" type="hidden" value="user_score_manage" />
      <input name="ctype" type="hidden" value="score_submit" />
      <input name="username" type="hidden" value="<?php echo $_GET['username'];?>" />
      <input name="backaction" type="hidden" value="<?php echo $_GET['backaction'];?>" />
       <input name="page" type="hidden" value="<?php echo $_GET['page'];?>" />
      <input type="submit" value="<?=$lang['user_submit']?>" name="B1" />&nbsp;&nbsp;
      <input onclick="javascript:history.back();" type="button" value="<?=$lang['user_back']?>" /> 
      </td>
    </tr>
     <tr bgcolor="#ffffff"><td colspan=2 height="20"></td></tr>
  </form>
	</table>
</td></tr>
</table>
<?php
	admin_footer();
	exit();
	}
}
elseif($admin_action=='user_del')
{
	$dblink->query("DELETE FROM $dbprefix"."members where uid='$_GET[uid]'");
	header("location:admin.php?admin_action=$_GET[backaction]&page=$_GET[page]");

}
else
{
	echo 'user_manage error';
}
?>
