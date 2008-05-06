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

class db_sql
{
	var $querynum = 0;
	var $link;

	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0)
	{
		if($pconnect)
		{
			$this->link=@mysql_pconnect($dbhost, $dbuser, $dbpw);
			if(!$this->link)
			{
				$this->halt('Can not connect to MySQL server');
			}
		}
		else
		{
			$this->link=@mysql_connect($dbhost, $dbuser, $dbpw);
			if(!$this->link)
			{
				$this->halt('Can not connect to MySQL server');
			}
		}

		if($this->version() > '4.1')
		{
			global $charset, $dbcharset;
			if(!$dbcharset && in_array(strtolower($charset), array('gbk', 'utf-8')))
			{
				$dbcharset = str_replace('-', '', $charset);
			}

			if($dbcharset)
			{
				mysql_query("SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=binary",$this->link);
			}

			if($this->version() > '5.0.1')
			{
				mysql_query("SET sql_mode=''",$this->link);
			}
		}

		if($dbname)
		{
			mysql_select_db($dbname,$this->link);
		}

	}

	function select_db($dbname)
	{
		return mysql_select_db($dbname,$this->link);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC)
	{
		return mysql_fetch_array($query, $result_type);
	}

	function query($sql, $type = '')
	{
		global $debug, $discuz_starttime, $sqldebug;

		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql,$this->link)) && $type != 'SILENT')
		{
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		return $query;
	}

	function affected_rows()
	{
		return mysql_affected_rows($this->link);
	}

	function error()
	{
		return mysql_error($this->link);
	}

	function errno()
	{
		return intval(mysql_errno($this->link));
	}

	function result($query, $row)
	{
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query)
	{
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query)
	{
		return mysql_num_fields($query);
	}

	function free_result($query)
	{
		mysql_free_result($query);
	}

	function insert_id()
	{
		$id = mysql_insert_id($this->link);
		return $id;
	}

	function fetch_row($query)
	{
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query)
	{
		return mysql_fetch_field($query);
	}

	function version()
	{
		return mysql_get_server_info($this->link);
	}

	function close()
	{
		return mysql_close($this->link);
	}

	function halt($message = '', $sql = '')
	{
		if(defined('DISCUZ_ROOT'))
		require_once DISCUZ_ROOT.'./include/db_mysql_error.php';
		else
		require_once './include/db_mysql_error.php';
		//require_once CYASK_ROOT.'./include/db_mysql_error.php';
	}
}

?>