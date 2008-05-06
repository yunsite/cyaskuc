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
if($admin_action=='ques_answer')
{
	if(!$_GET['page']) $_GET['page']=1;
	$pagerow=20;
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}answer"); 
	$qcount=$dblink->result($query,0);
	$pagecount=ceil($qcount/$pagerow);
	if ($_GET['page']>$pagecount) $_GET['page']=1;
	$start=($_GET['page']-1)*$pagerow;
	$query=$dblink->query("SELECT * FROM {$dbprefix}answer ORDER BY answertime desc limit $start,$pagerow");
	admin_header();
?>
<table cellspacing="1" cellpadding="0" width="820" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="header"><td height="22"><?=$lang['ans_header']?>&nbsp;(<?php echo $qcount;?>)</td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		<tr bgcolor="#f8f8f8">
		<td width="50" align="center"><?=$lang['ans_id']?></td>
		<td width="370" height="26" align="center"><?=$lang['ans_abs']?></td>
		<td width="100" height="26" align="center"><?=$lang['ans_replier']?></td>
		<td width=80 align=center><?=$lang['ans_add_time']?></td>
		<td width=80 align=center><?=$lang['ans_adopt_time']?></td>
		<td width=50 align=center><?=$lang['ans_vote']?></td>
		<td width=45 align=center><?=$lang['ans_mod']?></td>
		<td width=45 align=center><?=$lang['delete']?></td>
		</tr>
		<?php
		while($row=$dblink->fetch_array($query))
		{
			$row['answer']=strip_tags($row['answer']);
			$row['answer']=str_replace("\r\n","",$row['answer']);
			$row['answer']=str_replace(" ","",$row['answer']);
			$row['answer']=cut_str($row['answer'],60);
			$row['adopttime']=$row['adopttime'] ? date("n-j H:i",$row['adopttime']) :$lang['ans_unadopted'];
			$row['joinvote']=$row['joinvote'] ? $row['votevalue'] : $lang['ans_unvote'];
	
		?>
		<tr bgcolor="#ffffff">
		<td width="50" align="center"><a href="question.php?qid=<?php echo $row['qid'];?>" target="_blank"><?php echo $row['qid'];?></a></td>
		<td width="370" height="26" align="left"><a href="response.php?aid=<?php echo $row['aid'];?>" target="_blank"><?php echo $row['answer'];?></a></td>
		<td width="100" align="center"><a href="member.php?username=<?php echo $row['username'];?>" target="_blank"><?php echo $row['username'];?></a></td>
		<td width="80" align="center"><?php echo date("n-j H:i",$row['answertime']);?></td>
		<td width="80" align="center"><?php echo $row['adopttime'];?></td>
		<td width="50" align="center"><?php echo $row['joinvote'];?></td>
		<td width="45" align="center"><a href="admin.php?admin_action=answer_edit&aid=<?php echo $row['aid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $_GET['page'];?>"><?=$lang['edit']?></a></td>
		<td width="45" align="center"><a href="admin.php?admin_action=answer_del&aid=<?php echo $row['aid'];?>&backaction=<?php echo $admin_action;?>&page=<?php echo $_GET['page'];?>" onclick="if( !deleteit() ) return false;"><?=$lang['delete']?></a></td>
		</tr>
		<?php
		}
		?>
		<tr bgcolor="#F8F8F8" height="30"><td colspan="8" align="center">
		<font color="#000080"><?=$lang['di']?><?php echo $_GET['page'];?><?=$lang['ye']?>/<?=$lang['gong']?><?php echo $pagecount;?><?=$lang['ye']?></font>
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
elseif($admin_action=='answer_edit')
{
	if($_POST['ctype']=='edit_submit')
	{
		$answer=filters_content($_POST['answer']);
		$aid=intval($_POST['aid']);
		$dblink->query("UPDATE {$dbprefix}answer SET answer='$answer' where aid=$aid");
		header("location:admin.php?admin_action=$_POST[backaction]&page=$_POST[page]");
	}
	else
	{
		$aid=intval($_GET['aid']);
		$query=$dblink->query("SELECT * FROM {$dbprefix}answer WHERE aid=$aid");
		$row=$dblink->fetch_array($query);
		$row['answer']=filters_outcontent($row['answer']);
		$row['answer']=htmlspecialchars($row['answer']);
		admin_header();
?>
<table cellspacing="1" cellpadding="0" width="760" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height="22"><?=$lang['ans_do_mod']?></td></tr>
	<tr bgcolor="#f8f8f8"><td>
		<table border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0" width="100%">
		 <form name=Form1 action="admin.php" method="post">
		<tr bgcolor="#f8f8f8">
		<td width="100" align="center"><?=$lang['ans_do_mod']?></td>
		<td width="600" height="26" align="left">
		<input type="hidden" name="answer" value="<?php echo $row['answer'];?>" />
		<script type="text/javascript" src="cyaskeditor/CyaskEditor_<?=$charset?>.js"></script>
		<script type="text/javascript">
<!--
var editor = new CyaskEditor("editor");
editor.hiddenName = "answer";
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
		 <input type="hidden" name="admin_action" value="answer_edit" />
		 <input type="hidden" name="ctype" value="edit_submit" />
		 <input type="hidden" name="aid" value="<?php echo $row['aid'];?>" />
		 <input type="hidden" name="backaction" value="<?php echo $_GET['backaction'];?>" />
		 <input type="hidden" name="page" value="<?php echo $_GET['page'];?>" />
		 <input type="submit" name="submit" value="<?=$lang['ans_mod']?>" onclick="cyaskeditorsubmit()" />
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
elseif($admin_action=='answer_del')
{
	$dblink->query("DELETE FROM {$dbprefix}answer where aid='$_GET[aid]'");
	header("location:admin.php?admin_action=$_GET[backaction]&page=$_GET[page]");
}
else
{
	echo 'answer_manage error';
}
?>
