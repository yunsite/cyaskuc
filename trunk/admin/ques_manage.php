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
if($admin_action=='ques_sort')
{
	admin_header();
	$sid=intval($_GET['sid']);

	if($grade==1)
	{
?>
<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td><?=$lang['ques_sort']?></td></tr>
	<?php
	$query=$dblink->query("SELECT sid,sort1 AS sort,orderid FROM {$dbprefix}sort WHERE grade=1 ORDER BY orderid asc");
	$i=0;
	while($row=$dblink->fetch_array($query))
	{
	?>
	<tr bgcolor="#f8f8f8"><td><b><?php echo $row['sort'];?></b>
	<a href="admin.php?admin_action=ques_list&grade=1&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['ques_view']?></font>]</a>
	<a href="admin.php?admin_action=ques_del&grade=1&sid=<?php echo $row['sid'];?>" onclick="if( !deleteall() ) return false;">[<font color="#FF0000"><?=$lang['ques_clear']?></font>]</a>
	<a href="admin.php?admin_action=ques_sort&grade=2&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['ques_sub_sort']?></font>]</a>
	</td></tr>
	<tr bgcolor="#F8F8F8"><td></td></tr>
	<?php
	$i++;
	}
	if(!$i)
	{
	?>
	<tr bgcolor="#F8F8F8" height=50><td align=center><?=$lang['ques_no_sort']?></td></tr>
	<?php
	}
	?>
	</table>
</td></tr>
</table>
<?php
	}
	elseif($grade==2)
	{
		$query=$dblink->query("SELECT sid,sort1 AS sort,orderid FROM {$dbprefix}sort WHERE sid=$sid");
		$sort_row=$dblink->fetch_array($query);
		$query=$dblink->query("SELECT sid,sort2 AS sort,orderid FROM {$dbprefix}sort WHERE grade=2 AND sid1=$sid ORDER BY orderid asc");
	?>
	<br><br>
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td><?php echo $sort_row['sort'];?> &nbsp;&nbsp;<?=$lang['ques_cur']?><?php echo $grade;?><?=$lang['ques_ji_sort']?>&nbsp;&nbsp;<a href="admin.php?admin_action=ques_sort">[<?=$lang['ques_back_upper']?>]</a></td></tr>
	<?php
	$i=0;
	while($row=$dblink->fetch_array($query))
	{
	?>
	<tr bgcolor="#f8f8f8"><td><b><?php echo $row[sort];?></b>
	<a href="admin.php?admin_action=ques_list&grade=2&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['ques_view']?></font>]</a>
	<a href="admin.php?admin_action=ques_del&grade=2&sid=<?php echo $row['sid'];?>" onclick="if( !deleteit() ) return false;">[<font color="#FF0000"><?=$lang['ques_clear']?></font>]</a>
	<a href="admin.php?admin_action=ques_sort&grade=3&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['ques_sub_sort']?></font>]</a>
	</td></tr>
	<tr bgcolor="#f8f8f8"><td></td></tr>
	<?php
	$i++;
	}
	if(!$i)
	{
	?>
	<tr bgcolor="#F8F8F8" height=50><td align=center><?=$lang['ques_no_sort']?></td></tr>
	<?php
	}
	?>
	</table>
</td></tr>
</table>
<?php
	}
	elseif($grade==3)
	{
		$query=$dblink->query("SELECT sid,sid1,sort2 AS sort,orderid FROM {$dbprefix}sort WHERE sid='$_GET[sid]'");
		$sort_row=$dblink->fetch_array($query);
		$query=$dblink->query("SELECT sid,sort3 AS sort,orderid FROM {$dbprefix}sort WHERE grade='3' AND sid2='$_GET[sid]' ORDER BY orderid asc");
	?>
<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td><?php echo $sort_row['sort'];?> &nbsp;&nbsp;<?=$lang['ques_cur']?><?php echo $grade;?><?=$lang['ques_ji_sort']?>&nbsp;&nbsp;<a href="admin.php?admin_action=ques_sort&grade=2&sid=<?php echo $sort_row['sid1'];?>">[<?=$lang['ques_back_upper']?>]</a></td></tr>
	<?php
	$i=0;
	while($row=$dblink->fetch_array($query))
	{
	?>
	<tr bgcolor="#FFFFFF"><td><b><?php echo $row['sort'];?></b>
	<a href="admin.php?admin_action=ques_list&grade=3&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['ques_view']?></font>]</a>
	<a href="admin.php?admin_action=ques_del&grade=3&sid=<?php echo $row['sid'];?>" onclick="if( !deleteit() ) return false;">[<font color="#FF0000"><?=$lang['ques_clear']?></font>]</a>
	</td></tr>
	<tr bgcolor="#F8F8F8"><td></td></tr>
	<?php
	$i++;
	}
	if(!$i)
	{
	?>
	<tr bgcolor="#F8F8F8" height="50"><td align=center><?=$lang['ques_no_sort']?></td></tr>
	<?php
	}
	?>
	</table>
</td></tr>
</table>
<?php
	}
	else
	{
		exit("ques_sort error");
	}
	admin_footer();
	exit();
}
elseif($admin_action=='ques_list')
{
	$sid=intval($_GET['sid']);
	if(!$page) $page=1;
	$pagerow=20;
	$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid=$sid");
	$sort_row=$dblink->fetch_array($query);
	
	if($sort_row['grade']==1)
	{
		$sort_path=$sort_row['sort1'].'&nbsp;&nbsp;<a href="admin.php?admin_action=ques_sort">['.$lang['ques_back_upper'].']</a>';
		$sort_sid=intval($sort_row['sid']);
		$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE sid1=$sort_sid"); 
		$qcount=$dblink->result($query,0);
		$pagecount=ceil($qcount/$pagerow);
		if ($page>$pagecount) $page=1;
		$start=($page-1)*$pagerow;
		$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE sid1=$sort_sid ORDER BY asktime desc limit $start,$pagerow");
	}
	elseif($sort_row['grade']==2)
	{
		$sort_path=$sort_row['sort1'].' -> '.$sort_row['sort2'].'&nbsp;&nbsp;<a href="admin.php?admin_action=ques_sort&grade=2&sid='.$sort_row[sid1].'">['.$lang['ques_back_upper'].']</a>';
		$sort_sid=intval($sort_row['sid']);
		$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE sid2=$sort_sid"); 
		$qcount=$dblink->result($query,0);
		$pagecount=ceil($qcount/$pagerow);
		if ($page>$pagecount) $page=1;
		$start=($page-1)*$pagerow;
		$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE sid2=$sort_sid ORDER BY asktime desc limit $start,$pagerow");
	
	}
	elseif($sort_row['grade']==3)
	{
		$sort_path=$sort_row['sort1'].' -> '.$sort_row['sort2'].' -> '.$sort_row[sort3].'&nbsp;&nbsp;<a href="admin.php?admin_action=ques_sort&grade=3&sid='.$sort_row[sid2].'">['.$lang['ques_back_upper'].']</a>';
		$sort_sid=intval($sort_row['sid']);
		$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE sid3=$sort_sid"); 
		$qcount=$dblink->result($query,0);
		$pagecount=ceil($qcount/$pagerow);
		if ($page>$pagecount) $page=1;
		$start=($page-1)*$pagerow;
		$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE sid3=$sort_sid ORDER BY asktime desc limit $start,$pagerow");
	}
	else
	{
		exit("ques_grade error");
	}
	admin_header();
?>
<script type="text/javascript">
function topit()
{
	if( !confirm("<?=$lang['ques_do_intro']?>")) return false;
	else return true;
}
function disQstate(s)
{ 
	switch (s)
	{
		case 1:var op="<font color=#8b0000><?=$lang['ques_nosovle']?></font>";break;
		case 2:var op="<font color=#006400><?=$lang['ques_sovle']?></font>";break;
		case 3:var op="<font color=#0000ff><?=$lang['ques_vote']?></font>";break;
		case 4:var op="<font color=#a9a9a9><?=$lang['ques_close']?></font>";break;
		default: var op=<?=$lang['ann_unknown']?>;
	}
	document.write(op);
}
</script>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?php echo $sort_path;?></td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=330 height=26 align=center colspan=2><?=$lang['ques_title']?></td>
		<td width=100 align=center><?=$lang['ques_add_time']?></td>
		<td width=50 align=center><?=$lang['ques_score']?></td>
		<td width=50 align=center><?=$lang['ques_answer']?></td>
		<td width=50 align=center><?=$lang['ques_view1']?></td>
		<td width=60 align=center><?=$lang['ques_state']?></td>
		<td width=50 align=center><?=$lang['ques_intro']?></td>
		<td width=45 align=center><?=$lang['edit']?></td>
		<td width=45 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$stitle=cut_str($row['title'],54);
			$introtag = $row['introtime'] ? '<font color="red">'.$lang['ques_intro'].'</font>' : $lang['ques_nointro'];
		?>
		<tr bgcolor="#ffffff">
		<td width=20 align=center>&nbsp;</td>
		<td width=310 height=26 align=left><a href="question.php?qid=<?php echo $row['qid'];?>" title="<?php echo $row['title'];?>" target="_blank"><?php echo $stitle;?></a></td>
		<td width=100 align=center><?php echo date("n-j H:i",$row['asktime'])?></td>
		<td width=50 align=center><?php echo $row['score']?></td>
		<td width=50 align=center><?php echo $row['answercount']?></td>
		<td width=50 align=center><?php echo $row['clickcount']?></td>
		<td width=60 align=center><script language=javascript>disQstate(<?php echo $row['status'];?>);</script></td>
		<td width=50 align=center><a href="admin.php?admin_action=ques_top&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&sid=<?php echo $sid;?>&page=<?php echo $page;?>" onclick="if( !topit() ) return false;"><?php echo $introtag;?></a></td>
		<td width=45 align=center><a href="admin.php?admin_action=ques_edit&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&sid=<?php echo $sid;?>&page=<?php echo $page;?>"><?=$lang['edit']?></a></td>
		<td width=45 align=center><a href="admin.php?admin_action=ques_del&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&sid=<?php echo $sid;?>&page=<?php echo $page;?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		if(!$qcount)
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center"><?=$lang['ques_no']?></td></tr>
		<?php
		}
		else
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $page;?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&sid=<?php echo $sid;?>&page=1" title="<?=$lang['ques_shouye']?>"><?=$lang['shouye']?></a>
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
					echo '<a href="admin.php?admin_action='.$admin_action.'&sid='.$sid.'&page='.$i.'">&nbsp;['.$i.']</a>';          
				}
			}
		}
	 ?>                                                                                                                                                                                                                                                                                                                                                                                                                
     <a href="admin.php?admin_action=<?php echo $admin_action;?>&sid=<?php echo $sid;?>&page=<?php echo $pagecount;?>"><?=$lang['weiye']?></a>
		<?php
		} 
		?>
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
elseif($admin_action=='ques_nosolve')
{
	if(!$page) $page=1;
	$pagerow=20;
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE status=1"); 
	$qcount=$dblink->result($query,0);
	$pagecount=ceil($qcount/$pagerow);
	if ($page>$pagecount) $page=1;
	$start=($page-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE status='1' ORDER BY asktime desc limit $start,$pagerow");
	admin_header();
?>
<script type="text/JavaScript">
function topit()
{
	if( !confirm("<?=$lang['ques_do_intro']?>")) return false;
	else return true;
}
function disQstate(s)
{ 
	switch (s)
	{
		case 1:var op="<font color=#8b0000><?=$lang['ques_nosovle']?></font>";break;
		case 2:var op="<font color=#006400><?=$lang['ques_sovle']?></font>";break;
		case 3:var op="<font color=#0000ff><?=$lang['ques_vote']?></font>";break;
		case 4:var op="<font color=#a9a9a9><?=$lang['ques_close']?></font>";break;
		default: var op=<?=$lang['ann_unknown']?>;
	}
	document.write(op);
}
</script>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?=$lang['ques_for_nosolve']?> &nbsp;(<?php echo $qcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=355 height=26 align=center colspan=2><?=$lang['ques_title']?></td>
		<td width=100 align=center><?=$lang['ques_add_time']?></td>
		<td width=50 align=center><?=$lang['ques_score']?></td>
		<td width=50 align=center><?=$lang['ques_answer']?></td>
		<td width=50 align=center><?=$lang['click']?></td>
		<td width=60 align=center><?=$lang['ques_state']?></td>
		<td width=45 align=center><?=$lang['ques_intro']?></td>
		<td width=50 align=center><?=$lang['edit']?></td>
		<td width=50 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$stitle=cut_str($row['title'],54);
			$introtag = $row['introtime'] ? '<font color="red">'.$lang['ques_introed'].'</font>' :$lang['ques_unintroed'];
		?>
		<tr bgcolor="#ffffff">
		<td width=20 align=center>&nbsp;</td>
		<td width="325" height="26" align="left"><a href="question.php?qid=<?php echo $row['qid'];?>" title="<?php echo $row['title'];?>" target="_blank"><?php echo $stitle;?></a></td>
		<td width=100 align=center><?php echo date("y-n-j H:i",$row['asktime'])?></td>
		<td width=50 align=center><?php echo $row['score']?></td>
		<td width=50 align=center><?php echo $row['answercount']?></td>
		<td width=50 align=center><?php echo $row['clickcount']?></td>
		<td width=60 align=center><script type="text/javascript">disQstate(<?php echo $row['status'];?>);</script></td>
		<td width=45 align=center><a href="admin.php?admin_action=ques_top&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>" onclick="if( !topit() ) return false;"><?php echo $introtag;?></a></td>
		<td width=50 align=center><a href="admin.php?admin_action=ques_edit&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>"><?=$lang['edit']?></a></td>
		<td width=50 align=center><a href="admin.php?admin_action=ques_del&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		if(!$qcount)
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center"><?=$lang['ques_no']?></td></tr>
		<?php
		}
		else
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $page;?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1" title="<?=$lang['shouye']?>"><?=$lang['shouye']?></a>
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
elseif($admin_action=='ques_solve')
{
	if(!$page) $page=1;
	$pagerow=20;

	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE status=2"); 
	$qcount=$dblink->result($query,0);
	$pagecount=ceil($qcount/$pagerow);
	if ($page>$pagecount) $page=1;
	$start=($page-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE status=2 ORDER BY asktime desc limit $start,$pagerow");
	admin_header();
?>
<script type="text/javascript">
function topit()
{
	if( !confirm("<?=$lang['ques_do_intro']?>")) return false;
	else return true;
}
function disQstate(s)
{ 
	switch (s)
	{
		case 1:var op="<font color=#8b0000><?=$lang['ques_nosovle']?></font>";break;
		case 2:var op="<font color=#006400><?=$lang['ques_sovle']?></font>";break;
		case 3:var op="<font color=#0000ff><?=$lang['ques_vote']?></font>";break;
		case 4:var op="<font color=#a9a9a9><?=$lang['ques_close']?></font>";break;
		default: var op=<?=$lang['ann_unknown']?>;
	}
	document.write(op);
}
</script>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?=$lang['ques_for_solve']?> &nbsp;(<?php echo $qcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=270 height=26 align=center colspan="2"><?=$lang['ques_title']?></td>
		<td width=100 align=center><?=$lang['ques_start_time']?></td>
		<td width=100 align=center><?=$lang['ques_end_time']?></td>
		<td width=50 align=center><?=$lang['ques_score']?></td>
		<td width=50 align=center><?=$lang['ques_answer']?></td>
		<td width=60 align=center><?=$lang['click']?></td>
		<td width=60 align=center><?=$lang['ques_state']?></td>
		<td width=45 align=center><?=$lang['ques_intro']?></td>
		<td width=45 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$stitle=cut_str($row['title'],54);
			$introtag = $row['introtime'] ? '<font color="red">'.$lang['ques_introed'].'</font>' : $lang['ques_unintroed'];
		?>
		<tr bgcolor="#ffffff">
		<td width=20 align=center>&nbsp;</td>
		<td width=250 height=26 align=left><a href="question.php?qid=<?php echo $row['qid'];?>" title="<?php echo $row['title'];?>" target="_blank"><?php echo $stitle;?></a></td>
		<td width=100 align=center><?php echo date("n-j H:i",$row['asktime'])?></td>
		<td width=100 align=center><?php echo date("n-j H:i",$row['endtime'])?></td>
		<td width=50 align=center><?php echo $row['score']?></td>
		<td width=50 align=center><?php echo $row['answercount']?></td>
		<td width=60 align=center><?php echo $row['clickcount']?></td>
		<td width=60 align=center><script language=javascript>disQstate(<?php echo $row['status'];?>);</script></td>
		<td width=45 align=center><a href="admin.php?admin_action=ques_top&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>" onclick="if( !topit() ) return false;"><?php echo $introtag;?></a></td>
		<td width=45 align=center><a href="admin.php?admin_action=ques_del&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		if(!$qcount)
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center"><?=$lang['ques_no']?></td></tr>
		<?php
		}
		else
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $page;?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1" title="<?=$lang['shouye']?>"><?=$lang['shouye']?></a>
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
elseif($admin_action=='ques_vote')
{
	if(!$page) $page=1;
	$pagerow=20;
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE status=3"); 
	$qcount=$dblink->result($query,0);
	$pagecount=ceil($qcount/$pagerow);
	if ($page>$pagecount) $page=1;
	$start=($page-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE status=3 ORDER BY asktime desc limit $start,$pagerow");
	admin_header();
?>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height=22><?=$lang['ques_for_vote']?> &nbsp;(<?php echo $qcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=420 height="26" align="center" colspan="2"><?=$lang['ques_title']?></td>
		<td width=120 align=center><?=$lang['ques_add_time']?></td>
		<td width=50 align=center><?=$lang['ques_score']?></td>
		<td width=60 align=center><?=$lang['ques_answer']?></td>
		<td width=60 align=center><?=$lang['click']?></td>
		<td width=45 align=center><?=$lang['edit']?></td>
		<td width=45 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$stitle=cut_str($row['title'],54);
		?>
		<tr bgcolor="#ffffff">
		<td width=20 align=center>&nbsp;</td>
		<td width=400 height=26 align=left><a href="question.php?qid=<?php echo $row['qid'];?>" title="<?php echo $row['title'];?>" target="_blank"><?php echo $stitle;?></a></td>
		<td width=120 align=center><?php echo date("y-n-j H:i",$row['asktime'])?></td>
		<td width=50 align=center><?php echo $row['score']?></td>
		<td width=60 align=center><?php echo $row['answercount']?></td>
		<td width=60 align=center><?php echo $row['clickcount']?></td>
		<td width=45 align=center><a href="admin.php?admin_action=ques_edit&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>"><?=$lang['edit']?></a></td>
		<td width=45 align=center><a href="admin.php?admin_action=ques_del&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		if(!$qcount)
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center"><?=$lang['ques_no']?></td></tr>
		<?php
		}
		else
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $page;?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1" title="<?=$lang['shouye']?>"><?=$lang['shouye']?></a>
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
elseif($admin_action=='ques_intro')
{
	if(!$page) $page=1;
	$pagerow=20;
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE introtime !=0"); 
	$qcount=$dblink->result($query,0);
	$pagecount=ceil($qcount/$pagerow);
	if ($page>$pagecount) $page=1;
	$start=($page-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE introtime !=0 ORDER BY introtime desc limit $start,$pagerow");
	admin_header();
?>
<script type="text/javascript">
function topit()
{
	if( !confirm("<?=$lang['ques_cancel_intro']?>")) return false;
	else return true;
}
function disQstate(s)
{ 
	switch (s)
	{
		case 1:var op="<font color=#8b0000><?=$lang['ques_nosovle']?></font>";break;
		case 2:var op="<font color=#006400><?=$lang['ques_sovle']?></font>";break;
		case 3:var op="<font color=#0000ff><?=$lang['ques_vote']?></font>";break;
		case 4:var op="<font color=#a9a9a9><?=$lang['ques_close']?></font>";break;
		default: var op=<?=$lang['ann_unknown']?>;
	}
	document.write(op);
}
</script>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?=$lang['ques_for_intro']?> &nbsp;(<?php echo $qcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=350 height="26" align="center" colspan="2"><?=$lang['ques_title']?></td>
		<td width=120 align=center><?=$lang['ques_add_time']?></td>
		<td width=50 align=center><?=$lang['ques_score']?></td>
		<td width=60 align=center><?=$lang['ques_answer']?></td>
		<td width=60 align=center><?=$lang['click']?></td>
		<td width=60 align=center><?=$lang['ques_state']?></td>
		<td width=50 align=center><?=$lang['ques_intro']?></td>
		<td width=50 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$stitle=cut_str($row['title'],54);
		?>
		<tr bgcolor="#ffffff">
		<td width=20 align=center>&nbsp;</td>
		<td width=330 height=26 align=left><a href="question.php?qid=<?php echo $row['qid'];?>" title="<?php echo $row['title'];?>" target="_blank"><?php echo $stitle;?></a></td>
		<td width=120 align=center><?php echo date("y-n-j H:i",$row['asktime'])?></td>
		<td width=50 align=center><?php echo $row['score']?></td>
		<td width=60 align=center><?php echo $row['answercount']?></td>
		<td width=60 align=center><?php echo $row['clickcount']?></td>
		<td width=60 align=center><script language=javascript>disQstate(<?php echo $row['status'];?>);</script></td>
		<td width=50 align=center><a href="admin.php?admin_action=ques_top&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>" onclick="if( !topit() ) return false;"><?=$lang['cancel']?></a></td>
		<td width=50 align=center><a href="admin.php?admin_action=ques_del&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		if(!$qcount)
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center"><?=$lang['ques_no']?></td></tr>
		<?php
		}
		else
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $_GET[page];?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1" title="<?=$lang['shouye']?>"><?=$lang['shouye']?></a>
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
elseif($admin_action=='ques_close')
{
	if(!$page) $page=1;
	$pagerow=20;
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}ques WHERE status=4"); 
	$qcount=$dblink->result($query,0);
	$pagecount=ceil($qcount/$pagerow);
	if ($page>$pagecount) $page=1;
	$start=($page-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}ques WHERE status=4 ORDER BY asktime desc limit $start,$pagerow");
	admin_header();
?>
<table cellspacing="1" cellpadding="0" width="800" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?=$lang['ques_for_close']?> &nbsp;(<?php echo $qcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width=540 height="26" align="center" colspan="2"><?=$lang['ques_title']?></td>
		<td width=100 align=center><?=$lang['ques_add_time']?></td>
		<td width=50 align=center><?=$lang['ques_score']?></td>
		<td width=80 align=center><?=$lang['ques_answer']?></td>
		<td width=80 align=center><?=$lang['click']?></td>
		<td width=50 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$stitle=cut_str($row['title'],60);
		?>
		<tr bgcolor="#ffffff">
		<td width=20 align=center>&nbsp;</td>
		<td width=520 height="26" align="left"><a href="question.php?qid=<?php echo $row['qid'];?>" title="<?php echo $row['title'];?>" target="_blank"><?php echo $stitle;?></a></td>
		<td width=100 align=center><?php echo date("n-j H:i",$row['asktime'])?></td>
		<td width=50 align=center><?php echo $row['score']?></td>
		<td width=80 align=center><?php echo $row['answercount']?></td>
		<td width=80 align=center><?php echo $row['clickcount']?></td>
		<td width=50 align=center><a href="admin.php?admin_action=ques_del&qid=<?php echo $row['qid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $page;?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		if(!$qcount)
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center"><?=$lang['ques_no']?></td></tr>
		<?php
		}
		else
		{
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="11" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $page;?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
		<a href="admin.php?admin_action=<?php echo $admin_action;?>&page=1" title="<?=$lang['shouye']?>"><?=$lang['shouye']?></a>
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
elseif($admin_action=='ques_edit')
{
	if($_POST['ctype']=='edit_submit')
	{
		$qid=intval($_POST['qid']);
		$sid=intval($_POST['sid']);
		$page=intval($_POST['page']);
		$title=filters_title($_POST['title']);
		$content=filters_content($_POST['content']);
		$dblink->query("UPDATE {$dbprefix}ques SET title='$title',content='$content' where qid=$qid");
		header("location:admin.php?admin_action=$backaction&sid=$sid&page=$page");
	}
	else
	{
		$qid=intval($_GET['qid']);
		$query=$dblink->query("SELECT qid,title,content FROM {$dbprefix}ques WHERE qid=$qid");
		$row=$dblink->fetch_array($query);
		$row['content']=filters_outcontent($row['content']);
		$row['content']=htmlspecialchars($row['content']);
		admin_header();
?>
<table cellspacing="1" cellpadding="0" width="760" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height="22"><?=$lang['ques_edit']?></td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		 <form name=Form1 action="admin.php" method="post">
		 <tr bgcolor="#f8f8f8">
		<td width="100" align="center"><?=$lang['ques_title']?>:</td>
		<td width="600" height="26" align="left">
		<input type="text" name="title" size="80" maxlength="50" value="<?php echo $row['title'];?>" />
		</td></tr>
		<tr bgcolor="#f8f8f8">
		<td width="100" align="center"><?=$lang['ques_addtion']?>:</td>
		<td width="600" height="26" align="left">
		<input type="hidden" name="content" value="<?php echo $row['content'];?>" />
		<script type="text/javascript" src="cyaskeditor/CyaskEditor_<?=$charset?>.js"></script>
		<script type="text/javascript">
<!--
var editor = new CyaskEditor("editor");
editor.hiddenName = "content";
editor.editorType = "simple";
editor.editorWidth = "600px";
editor.editorHeight = "300px";
editor.show();
function cyaskeditorsubmit(){editor.data();}
-->
	</script>
		</td>
		</tr>
		<tr bgcolor="#f8f8f8">
		<td width="100" align="center">&nbsp;</td>
		<td width="600" height="26" align="left">
		 <input type="hidden" name="admin_action" value="ques_edit" />
		 <input type="hidden" name="ctype" value="edit_submit" />
		 <input type="hidden" name="qid" value="<?php echo $row['qid'];?>" />
		 <input type="hidden" name="backaction" value="<?php echo $backaction;?>" />
		 <input type="hidden" name="sid" value="<?php echo $sid;?>" />
		 <input type="hidden" name="page" value="<?php echo $page;?>" />
		 <input type="submit" name="submit" value="<?=$lang['edit']?>" onclick="cyaskeditorsubmit()" />&nbsp;&nbsp;
		 <input type="button" name="submit2" value="<?=$lang['ques_nomod']?>" onclick="history.back();" />
		 </td>
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
elseif($admin_action=='ques_top')
{
	$qid=intval($_GET['qid']);
	$sid=intval($_GET['sid']);
	$page=intval($_GET['page']);
	$query=$dblink->query("SELECT introtime FROM {$dbprefix}ques where qid=$qid");
	$introtime=$dblink->result($query,0);
	$introtime = $introtime ? 0 : time();
	$dblink->query("UPDATE {$dbprefix}ques SET introtime='$introtime' where qid=$qid");
	header("location:admin.php?admin_action=$backaction&sid=$sid&page=$page");
}
elseif($admin_action=='ques_del')
{
	$grade=intval($_GET['grade']);
	$sid=intval($_GET['sid']);
	$page=intval($_GET['page']);
	if($grade)
	{
		$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid=$sid");
		$row=$dblink->fetch_array($query);
		switch($grade)
		{
			case 1:
			$dblink->query("DELETE FROM {$dbprefix}ques where sid1=$sid");
			break;
			case 2:
			$dblink->query("DELETE FROM {$dbprefix}ques where sid2=$sid");
			$sid=$row['sid1'];
			break;
			case 3:
			$dblink->query("DELETE FROM {$dbprefix}ques where sid3=$sid");
			$sid=$row['sid2'];
			break;
		}
		header("location:admin.php?admin_action=ques_sort&grade=$grade&sid=$sid&page=$page");
	}
	else
	{
		$ctype=intval($_GET['ctype']);
		if($ctype==1)
		{
			//$dblink->query("DELETE FROM {$dbprefix}ques where qid=$qid");
		}
		else
		{
			$qid=intval($_GET['qid']);
			$dblink->query("DELETE FROM {$dbprefix}ques where qid=$qid");
		}
		header("location:admin.php?admin_action=$backaction&sid=$sid&page=$page");
	}
}
else
{
	echo 'ques_manage error';
}
?>
