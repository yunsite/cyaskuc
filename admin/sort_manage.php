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
if($_GET['admin_action']=='sort_list')
{
	admin_header();
	$sid=intval($_GET['sid']);
	if($grade==1)
	{
?>
<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height="26"><?=$lang['sort_list']?></td></tr>
	<?php
	$query=$dblink->query("SELECT sid,sort1 AS sort,orderid FROM {$dbprefix}sort WHERE grade='1' ORDER BY orderid asc");
	$i=0;
	while($row=$dblink->fetch_array($query))
	{
	?>
	<tr bgcolor="#FFFFFF"><td><?php echo $row['orderid'];?>: <b><?php echo $row['sort'];?></b>
	<a href="admin.php?admin_action=sort_edit&grade=1&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['sort_edit']?></font>]</a>
	<a href="admin.php?admin_action=sort_join&grade=1&sid=<?php echo $row['sid'];?>&sort=<?php echo $row['sort'];?>">[<font color="#FF0000"><?=$lang['sort_merg']?></font>]</a>
	<a href="admin.php?admin_action=sort_del&grade=1&sid=<?php echo $row['sid'];?>" onclick="if( !deleteit() ) return false;">[<font color="#FF0000"><?=$lang['delete']?></font>]</a>
	<a href="admin.php?admin_action=sort_list&grade=2&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['sort_sub_sort']?></font>]</a>
	</td></tr>
	<tr bgcolor="#F8F8F8"><td></td></tr>
	<?php
	$i++;
	}
	if(!$i)
	{
	?>
	<tr bgcolor="#F8F8F8" height=50><td align=center><?=$lang['sort_no_sort']?></td></tr>
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
	?>
<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height="26"><?php echo $sort_row['sort'];?> &nbsp;&nbsp;<?=$lang['ques_cur']?><?php echo $grade;?><?=$lang['ques_ji_sort']?>&nbsp;&nbsp;<a href="admin.php?admin_action=sort_list">[<?=$lang['ques_back_upper']?>]</a></td></tr>
	<?php
	$query=$dblink->query("SELECT sid,sort2 AS sort,orderid FROM {$dbprefix}sort WHERE grade=2 AND sid1='$sort_row[sid]' ORDER BY orderid asc");
	$i=0;
	while($row=$dblink->fetch_array($query))
	{
	?>
	<tr bgcolor="#FFFFFF"><td><?php echo $row['orderid'];?>: <b><?php echo $row['sort'];?></b>
	<a href="admin.php?admin_action=sort_edit&grade=2&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['sort_edit']?></font>]</a>
	<a href="admin.php?admin_action=sort_join&grade=2&sid=<?php echo $row['sid'];?>&sort=<?php echo $row['sort'];?>">[<font color="#FF0000"><?=$lang['sort_merg']?></font>]</a>
	<a href="admin.php?admin_action=sort_del&grade=2&sid=<?php echo $row['sid'];?>&supersid=<?php echo $sid;?>" onclick="if( !deleteit() ) return false;">[<font color="#FF0000"><?=$lang['delete']?></font>]</a>
	<a href="admin.php?admin_action=sort_list&grade=3&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['sort_sub_sort']?></font>]</a>
	</td></tr>
	<tr bgcolor="#F8F8F8"><td></td></tr>
	<?php
	$i++;
	}
	if(!$i)
	{
	?>
	<tr bgcolor="#F8F8F8" height=50><td align=center><?=$lang['sort_no_subsort']?></td></tr>
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
		$query=$dblink->query("SELECT sid,sid1,sort2 AS sort,orderid FROM {$dbprefix}sort WHERE sid=$sid");
		$sort_row=$dblink->fetch_array($query);
	?>
	<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
	<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td height="26"><?php echo $sort_row['sort'];?> &nbsp;&nbsp;<?=$lang['que_cur']?><?php echo $grade;?><?=$lang['ques_ji_sort']?>&nbsp;&nbsp;<a href="admin.php?admin_action=sort_list&grade=2&sid=<?php echo $sort_row['sid1'];?>">[<?=$lang['ques_back_upper']?>]</a></td></tr>
	<?php
	$query=$dblink->query("SELECT sid,sort3 AS sort,orderid FROM {$dbprefix}sort WHERE grade='3' AND sid2='$sort_row[sid]' ORDER BY orderid asc");
	$i=0;
	while($row=$dblink->fetch_array($query))
	{
	?>
	<tr bgcolor="#FFFFFF"><td><?php echo $row['orderid'];?>: <b><?php echo $row['sort'];?></b>
	<a href="admin.php?admin_action=sort_edit&grade=3&sid=<?php echo $row['sid'];?>">[<font color="#FF0000"><?=$lang['sort_edit']?></font>]</a>
	<a href="admin.php?admin_action=sort_join&grade=3&sid=<?php echo $row['sid'];?>&sort=<?php echo $row['sort'];?>">[<font color="#FF0000"><?=$lang['sort_merg']?></font>]</a>
	<a href="admin.php?admin_action=sort_del&grade=3&sid=<?php echo $row['sid'];?>&supersid=<?php echo $sid;?>" onclick="if( !deleteit() ) return false;">[<font color="#FF0000"><?=$lang['delete']?></font>]</a>
	</td></tr>
	<tr bgcolor="#F8F8F8"><td></td></tr>
	<?php
	$i++;
	}
	if(!$i)
	{
	?>
	<tr bgcolor="#F8F8F8" height=50><td align=center><?=$lang['sort_no_ques']?></td></tr>
	<?php
	}
	?>
	</table>
</td></tr>
</table>
<?php
	}
	admin_footer();
	exit();
}
elseif($_GET['admin_action']=='sort_add')
{
	admin_header();
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}sort WHERE grade=1");
	$count1=$dblink->result($query,0);
	$query=$dblink->query("SELECT sid,sort1 FROM {$dbprefix}sort WHERE grade=1 ORDER BY orderid");
	$class1='new Array("0","'.$lang['sort_sort1'].'"),';
	$c=1;
	while($row1=$dblink->fetch_array($query))
	{
		$class1.='new Array("'.$row1[sid].'","'.$row1[sort1].'")';
		if($c==$count1) $class1.="\n"; else $class1.=",\n";
		$c++;
	}
	
	$query=$dblink->query("SELECT count(*) FROM {$dbprefix}sort WHERE grade=2");
	$count2=$dblink->result($query,0);
	$query=$dblink->query("SELECT sid,sid1,sort2 FROM {$dbprefix}sort WHERE grade=2 ORDER BY orderid");
	$class2='';
	$c=1;
	while($row2=$dblink->fetch_array($query))
	{
		$class2.='new Array("'.$row2[sid1].'","'.$row2[sid].'","'.$row2[sort2].'")';
		if($c==$count2) $class2.="\n"; else $class2.=",\n";
		$c++;
	}
?>
<script type="text/javascript">
function check_sortform(f)
{
 	if(f.sortname.value =="")
 	{
  		alert("<?=$lang['sort_input_title']?>");
		f.sortname.focus();
		return false;
 	}
}
</script>
<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td colspan=2><?=$lang['sort_new']?></td></tr>
	<form method="get" action="admin.php" name="sortForm" onSubmit="return check_sortform(this)">
    <tr bgcolor="#F8F8F8"><td colspan=2 height="20"></td></tr>
     <tr bgcolor="#ffffff"> 
      <td width="80" height="25" align="right"><?=$lang['sort_title']?></td>
      <td align="left"><input name="sortname" type="text" size="30" maxlength="30" /></td>
    </tr>
    <tr bgcolor="#F8F8F8"> 
      <td width="80" height="25" align="right"><?=$lang['sort_display_order']?>:</td>
      <td align="left"><input name="orderid" type="text" size="5" maxlength="2" />
    </td>
   </tr>
   <tr bgcolor="#ffffff"> 
      <td width="80" height="25" align="right"><?=$lang['set_father_sort']?>:</td>
      <td align="left">
        <span id=classid>
       <table cellspacing=0 cellpadding=0 border=0>
         <input type="hidden" name="cid" value="0" /> 
         <tr><td><select id=ClassLevel1 style="WIDTH: 125px" size=8 name=ClassLevel1><option></option></select></TD>
          <td width=20><div id=jiantou align=center><B>→</B></div></td>
          <td><select id=ClassLevel2 style="WIDTH: 90px" onchange=getCidValue(); size=8 name=ClassLevel2><option selected></option></select></td>
         </tr>
       </table>
<script language="javascript">
function getCidValue()
{
	var _cl1 = document.sortForm.ClassLevel1;
	var _cl2 = document.sortForm.ClassLevel2;
	var _cid = document.sortForm.cid;
	if(_cl1.value!=0) _cid.value = _cl1.value;
	if(_cl2.value!=0) _cid.value = _cl2.value;
}
var g_ClassLevel1;
var g_ClassLevel2;
var class_level_1=new Array(
<?php echo $class1;?>
);
var class_level_2=new Array(
<?php echo $class2;?>
);
function FillClassLevel1(ClassLevel1)
{
    for(i=0; i<class_level_1.length; i++)
    {
        ClassLevel1.options[i] = new Option(class_level_1[i][1], class_level_1[i][0]);
    }
    ClassLevel1.options[0].selected = true;
    ClassLevel1.length = i+1;
}
function FillClassLevel2(ClassLevel2, class_level_1_id)
{
    ClassLevel2.options[0] = new Option("<?=$lang['sort_sort2']?>", "0");
    count = 1;
    for(i=0; i<class_level_2.length; i++)
    {
		if(class_level_2[i][0].toString() == class_level_1_id)
		{
            ClassLevel2.options[count] = new Option(class_level_2[i][2], class_level_2[i][1]);
            count = count+1;
		}
    }
    ClassLevel2.options[0].selected = true;
    ClassLevel2.length = count;
}

function ClassLevel1_onchange()
{
    getCidValue();
    FillClassLevel2(g_ClassLevel2, g_ClassLevel1.value);
     if (g_ClassLevel2.length <= 1) {  
     g_ClassLevel2.style.display = "none";
	 document.getElementById("jiantou").style.display = "none";
    }
    else {
     g_ClassLevel2.style.display = "";     
	 document.getElementById("jiantou").style.display = "";	 
    }      
    //ClassLevel2_onchange();
	
}
function InitClassLevelList(ClassLevel1, ClassLevel2)
{
    g_ClassLevel1=ClassLevel1;
    g_ClassLevel2=ClassLevel2;
    g_ClassLevel1.onchange = Function("ClassLevel1_onchange();");
    FillClassLevel1(g_ClassLevel1);
    ClassLevel1_onchange();
}
InitClassLevelList(document.sortForm.ClassLevel1, document.sortForm.ClassLevel2);

// auto-select the init class if need
var selected_id_list="0"
var blank_pos = selected_id_list.indexOf(" ");
var find_blank = true;
if (blank_pos == -1) {
    find_blank = false;
    blank_pos = selected_id_list.length;
}
var id_str = selected_id_list.substr(0, blank_pos);
g_ClassLevel1.value = id_str;
ClassLevel1_onchange();

if (find_blank == true)
{
    selected_id_list = selected_id_list.substr(blank_pos + 1, 
    selected_id_list.length - blank_pos - 1);
    blank_pos = selected_id_list.indexOf(" ");
    if (blank_pos == -1)
    {
        find_blank = false;
        blank_pos = selected_id_list.length;
    }
    id_str = selected_id_list.substr(0, blank_pos);
    g_ClassLevel2.value = id_str;
}
</script>
</span>
     </td>
    </tr>
    <tr bgcolor="#F8F8F8"> 
     <td width="80" height="25" align="right">&nbsp;</td>
     <td align="left">
        <input name="admin_action" type="hidden" value="sort_add_submit" />
       <input type="submit" value="<?=$lang['sort_submit']?>" name="B1" /> 
        &nbsp;&nbsp; <input type="reset" value="<?=$lang['sort_reset']?>" name="B2" /> &nbsp;&nbsp;
        <input onclick="javascript:history.back();" type="button" value="<?=$lang['sort_cancel']?>" /> 
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
elseif($_GET['admin_action']=='sort_add_submit')
{
	if($_GET['ClassLevel2'])
	{
		$query=$dblink->query("select * from {$dbprefix}sort where sid='$_GET[ClassLevel2]'");
		$sort_row=$dblink->fetch_array($query);
		$query=$dblink->query("INSERT INTO {$dbprefix}sort SET sid1='$sort_row[sid1]',sid2='$_GET[ClassLevel2]',sort1='$sort_row[sort1]',sort2='$sort_row[sort2]',sort3='$_GET[sortname]',grade='3',orderid='$_GET[orderid]'");
		$sid=$dblink->insert_id();
		header("location:admin.php?admin_action=sort_list&grade=3&sid=$_GET[ClassLevel2]");
	}
	elseif($_GET['ClassLevel1'])
	{
		$query=$dblink->query("select * from {$dbprefix}sort where sid='$_GET[ClassLevel1]'");
		$sort_row=$dblink->fetch_array($query);
		$query=$dblink->query("INSERT INTO {$dbprefix}sort SET sid1='$_GET[ClassLevel1]',sort1='$sort_row[sort1]',sort2='$_GET[sortname]',grade='2',orderid='$_GET[orderid]'");
		$sid=$dblink->insert_id();
		header("location:admin.php?admin_action=sort_list&grade=2&sid=$_GET[ClassLevel1]");
	}
	else
	{
		$query=$dblink->query("INSERT INTO {$dbprefix}sort SET sort1='$_GET[sortname]',grade='1',orderid='$_GET[orderid]'");
		$sid=$dblink->insert_id();
		header("location:admin.php?admin_action=sort_list&grade=1");
	}
}
elseif($_GET['admin_action']=='sort_edit')
{
	if($grade==1)
	{
		$query=$dblink->query("SELECT sort1 AS sort,orderid FROM {$dbprefix}sort WHERE sid='$_GET[sid]'");
	}
	elseif($grade==2)
	{
		$query=$dblink->query("SELECT sort2 AS sort,orderid FROM {$dbprefix}sort WHERE sid='$_GET[sid]'");
	}
	elseif($grade==3)
	{
		$query=$dblink->query("SELECT sort3 AS sort,orderid FROM {$dbprefix}sort WHERE sid='$_GET[sid]'");
	}
	else
	{
		exit("error");
	}
	$sort_row=$dblink->fetch_array($query);
	admin_header();
?>
<script language="javascript">
function check_sortform(f)
{
 	if(f.sortname.value =="")
 	{
  		alert("<?=$lang['sort_input_title']?>");
		f.sortname.focus();
		return false;
 	}
}
</script>
<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td colspan=2><?=$lang['sort_mod']?></td></tr>
	<form method="get" action="admin.php" name="sortForm" onSubmit="return check_sortform(this)">
    <tr bgcolor="#F8F8F8"><td colspan=2 height="20"></td></tr>
     <tr bgcolor="#ffffff"> 
      <td width="80" height="25" align="right"><?=$lang['sort_grade']?>:</td>
      <td align="left"><?php echo $grade;?> <?=$lang['grade']?></td>
    </tr>
     <tr bgcolor="#f8f8f8"> 
      <td width="80" height="25" align="right"><?=$lang['sort_title']?>:</td>
      <td align="left">
	<input name="sortname" type="text" size="30" maxlength="30" value="<?php echo $sort_row['sort'];?>" />
        </td>
    </tr>
     <tr bgcolor="#ffffff"> 
      <td width="80" height="25" align="right"><?=$lang['sort_disply_order']?></td>
      <td align="left">
	<input name="orderid" type="text" size="5" maxlength="2" value="<?php echo $sort_row['orderid'];?>" />
        </td>
    </tr>

    <tr bgcolor="#F8F8F8"> 
     <td width="80" height="25" align="right">&nbsp;</td>
     <td align="left">
        <input name="admin_action" type="hidden" value="sort_edit_submit" />
        <input name="grade" type="hidden" value="<?php echo $grade;?>" />
        <input name="sid" type="hidden" value="<?php echo $_GET['sid'];?>" />
		<input type="submit" value="<?=$lang['sort_submit']?>" name="B1"> &nbsp;&nbsp;
        <input onclick="javascript:history.back();" type="button" value="<?=$lang['sort_cancel']?>" /> 
      </td></tr>
     <tr bgcolor="#ffffff"><td colspan=2 height="20"></td></tr>
  </form>
 </table>
</td></tr>
</table>
<?php
	admin_footer();
	exit();
}
elseif($_GET['admin_action']=='sort_edit_submit')
{
	if($_GET['grade']==1)
	{
		$dblink->query("UPDATE {$dbprefix}sort SET sort1='$_GET[sortname]',orderid='$_GET[orderid]' WHERE sid=$_GET[sid]");
		$dblink->query("UPDATE {$dbprefix}sort SET sort1='$_GET[sortname]' WHERE sid1=$_GET[sid] AND grade=2");
		$dblink->query("UPDATE {$dbprefix}sort SET sort1='$_GET[sortname]' WHERE sid2=$_GET[sid] AND grade=3");
		header("location:admin.php?admin_action=sort_list");
	}
	elseif($_GET['grade']==2)
	{
		$dblink->query("UPDATE {$dbprefix}sort SET sort2='$_GET[sortname]',orderid='$_GET[orderid]' WHERE sid=$_GET[sid]");
		$dblink->query("UPDATE {$dbprefix}sort SET sort2='$_GET[sortname]' WHERE sid2=$_GET[sid] AND grade=3");
		
		$query=$dblink->query("SELECT sid1 FROM {$dbprefix}sort WHERE sid='$_GET[sid]'");
		$sort_row=$dblink->fetch_array($query);
		header("location:admin.php?admin_action=sort_list&grade=2&sid=$sort_row[sid1]");
	}
	elseif($_GET['grade']==3)
	{
		$dblink->query("UPDATE {$dbprefix}sort SET sort3='$_GET[sortname]',orderid='$_GET[orderid]' WHERE sid=$_GET[sid]");
		
		$query=$dblink->query("SELECT sid2 FROM {$dbprefix}sort WHERE sid=$_GET[sid]");
		$sort_row=$dblink->fetch_array($query);
		header("location:admin.php?admin_action=sort_list&grade=3&sid=$sort_row[sid2]");
	}
	else
	{
		exit("error");
	}
}
elseif($_GET['admin_action']=='sort_join')
{
	if($grade==1)
	{
		$query=$dblink->query("SELECT sid,sort1 AS sort FROM {$dbprefix}sort WHERE grade='1' AND sid!='$_GET[sid]' ORDER BY orderid asc");
	}
	elseif($grade==2)
	{
		$query=$dblink->query("SELECT sid1 FROM {$dbprefix}sort WHERE sid='$_GET[sid]'");
		$sid1=$dblink->result($query,0);
		$query=$dblink->query("SELECT sid,sort2 AS sort FROM {$dbprefix}sort WHERE grade='2' AND sid1='$sid1' AND sid!='$_GET[sid]' ORDER BY orderid asc");
	}
	elseif($grade==3)
	{
		$query=$dblink->query("SELECT sid2 FROM {$dbprefix}sort WHERE sid='$_GET[sid]'");
		$sid2=$dblink->result($query,0);
		$query=$dblink->query("SELECT sid,sort3 AS sort FROM {$dbprefix}sort WHERE grade='3' AND sid2='$sid2' AND sid!='$_GET[sid]' ORDER BY orderid asc");
	}
	else
	{
		exit("error");
	}
	admin_header();
?>
<script language="javascript">
function check_sortform(f)
{
 	if(f.jointosid.value =="")
 	{
  		alert("<?=$lang['sort_dest']?>");
		f.jointosid.focus();
		return false;
 	}
}
</script>
<br /><br />
<table cellspacing="1" cellpadding="0" width="85%" align="center" class="tableborder">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr class="header"><td colspan=2><?=$lang['sort_for_merg']?></td></tr>
	<form name="sortForm" action="admin.php" method="get" onSubmit="return check_sortform(this)">
    <tr bgcolor="#F8F8F8"><td colspan=2 height="20"></td></tr>
     <tr bgcolor="#ffffff"> 
     <td width=150 height="25" align="right"><?=$lang['sort_merg']?> <input size=10 value="<?php echo $_GET['sort'];?>" /> <?=$lang['sort_to']?> &nbsp;</td>
      <td align="left" valign="top">
       <select name="jointosid" size="5">
       <option value=""><?=$lang['sort_select']?></option>
      <?php
      while($sort_row=$dblink->fetch_array($query))
      {
		echo '<option value="'.$sort_row['sid'].'">'.$sort_row['sort'].'</option>';
      }
      ?>
      </select>
        </td>
    </tr>
	<tr bgcolor="#F8F8F8"><td colspan=2 height="20"></td></tr>
    <tr bgcolor="#ffffff">
    <td width=150 height="25"></td>
     <td align="left">
        <input name="admin_action" type="hidden" value="sort_join_submit" />
        <input name="grade" type="hidden" value="<?php echo $grade;?>" />
        <input name="sid" type="hidden" value="<?php echo $_GET['sid']?>" />
		<input type="submit" value="<?=$lang['sort_submit']?>" name="B1" />&nbsp;&nbsp;
        <input onclick="javascript:history.back();" type="button" value="<?=$lang['sort_cancel']?>" /> 
      </td>
    </tr>
    <tr bgcolor="#f8f8f8"><td colspan=2 height="10"></td></tr>
  </form>
  </table>
</td></tr>
</table>
<?php
	admin_footer();
	exit();
}
elseif($_GET['admin_action']=='sort_join_submit')
{
	$query=$dblink->query("SELECT * FROM {$dbprefix}sort WHERE sid='$_GET[jointosid]'");
	$jointo=$dblink->fetch_array($query);
	if($_GET['grade']==1)
	{
		$dblink->query("UPDATE {$dbprefix}ques SET sid1='$jointo[sid]' WHERE sid1='$_GET[sid]'");
		$dblink->query("UPDATE {$dbprefix}sort SET sid1='$jointo[sid]',sort1='$jointo[sort1]' WHERE grade='2' AND sid1='$_GET[sid]'");
		$dblink->query("UPDATE {$dbprefix}sort SET sid1='$jointo[sid]',sort1='$jointo[sort1]' WHERE grade='3' AND sid1='$_GET[sid]'");
		$dblink->query("DELETE FROM {$dbprefix}sort WHERE grade='1' AND sid='$_GET[sid]'");
		header("location:admin.php?admin_action=sort_list");
	}
	elseif($_GET['grade']==2)
	{
		$dblink->query("UPDATE {$dbprefix}ques SET sid2='$jointo[sid]' WHERE sid2='$_GET[sid]'");
		$dblink->query("UPDATE {$dbprefix}sort SET sid2='$jointo[sid]',sort2='$jointo[sort2]' WHERE grade='3' AND sid2='$_GET[sid]'");
		$dblink->query("DELETE FROM {$dbprefix}sort WHERE grade='2' AND sid='$_GET[sid]'");
		header("location:admin.php?admin_action=sort_list&grade=2&sid=$jointo[sid1]");
	}
	elseif($_GET['grade']==3)
	{
		$dblink->query("UPDATE {$dbprefix}ques SET sid3='$jointo[sid]' WHERE sid3='$_GET[sid]'");
		$dblink->query("DELETE FROM {$dbprefix}sort WHERE grade='3' AND sid='$_GET[sid]'");
		header("location:admin.php?admin_action=sort_list&grade=3&sid=$jointo[sid2]");
	}
	else
	{
		exit("error");
	}
}
elseif($_GET['admin_action']=='sort_del')
{
	$sid=intval($_GET['sid']);
	$supersid=intval($_GET['supersid']);
	switch($grade)
	{
		case 1:
		$dblink->query("DELETE FROM {$dbprefix}sort WHERE sid=$sid");
		$dblink->query("DELETE FROM {$dbprefix}sort WHERE sid1=$sid");
		$dblink->query("DELETE FROM {$dbprefix}ques WHERE sid1=$sid");
		break;
		case 2:
		$dblink->query("DELETE FROM {$dbprefix}sort WHERE sid=$sid");
		$dblink->query("DELETE FROM {$dbprefix}sort WHERE sid2=$sid");
		$dblink->query("DELETE FROM {$dbprefix}ques WHERE sid2=$sid");
		break;
		case 3:
		$dblink->query("DELETE FROM {$dbprefix}sort WHERE sid=$sid");
		$dblink->query("DELETE FROM {$dbprefix}ques WHERE sid3=$sid");
		break;
	}
	header("location:admin.php?admin_action=sort_list&grade=$grade&sid=$supersid");
}
else
{
	echo 'action error';
}
?>
