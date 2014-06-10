<?php
/*
 * 摘	要: 数据库操作类
 * 作       者: 刘海艇
 * 修改日期: 2013-06-24
 */

class DB
{
	//传回句柄,传回结果集
	var $_conn, $_q, $_r;
	
	/*
	 * 功       能: 数据库连接
	 * 参       数：$p_error, string, 1为错误自动处理否则为手动处理
	 * 作       者: 刘海艇
	 * 修改日期: 2013-06-24
	 */
	function open($p_error="1" )
	{
		global $_CFG;
		if( $p_error == "1" )
		{
			//数据库连接错误自动处理
			$this->_conn = @mysql_connect( $_CFG["DB"]['DB_HOST'].":".$_CFG["DB"]['DB_PORT'], $_CFG["DB"]['DB_USER'], $_CFG["DB"]['DB_PWD'] ) or die( "数据库连接错误.." );
		}
		else
		{
			//数据库连接错误人工处理
			$this->_conn = @mysql_connect( $_CFG["DB"]['DB_HOST'].":".$_CFG["DB"]['DB_PORT'], $_CFG["DB"]['DB_USER'], $_CFG["DB"]['DB_PWD'] );
			if(!$this->_conn)
			{
				return false;
			}
		}
		mysql_select_db($_CFG["DB"]['DB_NAME'], $this->_conn );
		
		if(!isset($_CFG["DB"]['DB_CHARSET'])) $_CFG["DB"]['DB_CHARSET']='utf8';
		mysql_query("SET NAMES '".$_CFG["DB"]['DB_CHARSET']."'"); 
		mysql_query("SET CHARACTER_SET_CLIENT=".$_CFG["DB"]['DB_CHARSET']); 
		mysql_query("SET CHARACTER_SET_RESULTS=".$_CFG["DB"]['DB_CHARSET']);
		return true;
	}

	/*
	 * 功       能: 执行sql语句,返回数据集,而非数组
	 * 参       数：$p_sql, string, sql语句
	 * 作       者: 刘海艇
	 * 修改日期: 2013-06-24
	 */
	function query( $p_sql )
	{
		global $_GET;
		if( $_GET["DB_DEBUG"] == true )
		{
			echo $p_sql."<br>\n";
		}
		return mysql_query( $p_sql, $this->_conn );
	}
	
	/*
	 * 功       能: 将query结果集转化为数组
	 * 参       数：$rs, object, query查询结果集
	 * 作       者: 刘海艇
	 * 修改日期: 2013-06-24
	 */
	function select($rs)
	{
		if(!isset($rs)) return false;
		while($info = $this->fetch($rs))
		{
			$result[] = $info;
		}
		return $result;
	}
	
	/*
	 * 功       能: 获取符合条件的数量
	 * 参       数：$p_tabName, string, 表名（不含前缀）
	 * 		    $p_whereStr, string, 查询条件
	 * 作       者: 刘海艇
	 * 修改日期: 2013-06-24
	 */
	function count($p_tabName, $p_whereStr)
	{
		global $_CFG;
		if(!isset($p_tabName) || !isset($p_whereStr)) return false;
		$p_tabName = $_CFG["DB"]['DB_PREFIX'].$p_tabName;
		$sql = "select count(1) as _count from $p_tabName where $p_whereStr";
		$rs = $this->query($sql);
		$info = $this->fetch($rs);
		return $info['_count'];
	}
	
	/*
	 * 功       能: 执行sql语句,返回一条数组形式数据
	 * 参       数：$p_tabName, string, 表名（不含前缀）
	 * 		    $p_whereStr, string, 查询条件
	 * 作       者: 刘海艇
	 * 修改日期: 2013-07-10
	 */
	function line_one($p_tabName, $p_whereStr)
	{
		global $_CFG;
		if(!isset($p_tabName) || !isset($p_whereStr)) return false;
		$p_tabName = $_CFG["DB"]['DB_PREFIX'].$p_tabName;
		$sql = "select * as _count from $p_tabName where $p_whereStr";
		$rs = $this->query($sql);
		if(mysql_num_rows($rs)){
			$info = $this->fetch($rs);
			return $info;
		}else{
			return false;
		}
	}
	
	/*
	 * 功       能: 执行sql语句,返回对应的字段的数据
	 * 参       数：$p_tabName, string, 表名（不含前缀）
	 * 		    $p_whereStr, string, 查询条件
	 * 作       者: 刘海艇
	 * 修改日期: 2013-07-10
	 */
	function get_field($p_tabName, $p_whereStr, $field)
	{
		global $_CFG;
		if(!isset($p_tabName) || !isset($p_whereStr)) return false;
		$p_tabName = $_CFG["DB"]['DB_PREFIX'].$p_tabName;
		$sql = "select $field from $p_tabName where $p_whereStr";
		$rs = $this->query($sql);
		if(mysql_num_rows($rs)){
			$info = $this->fetch($rs);
			return $info[$field];
		}else{
			return false;
		}
	}

	/*
	 * 功       能: 插入数据
	 * 参       数：$p_tabName, string, 表名（不含前缀）
	 * 		    $p_tabVar, array, 插入的数据
	 * 作       者: 刘海艇
	 * 修改日期: 2013-06-24
	 */
	function insert( $p_tabName , $p_tabVar )
	{
		global $_CFG;
		foreach($p_tabVar as $key => $var)
		{
			$sql_addsub_name.= " `".$key."` ,";
			$sql_add_var_name.= " '".mysql_real_escape_string($var)."' ,";
		}
		$sql_addsub_name = substr($sql_addsub_name, 0, -1);
		$sql_add_var_name = substr($sql_add_var_name, 0, -1);

		$sqlAdd = "INSERT INTO ".$_CFG["DB"]['DB_PREFIX'].$p_tabName." ( ".$sql_addsub_name.") VALUES (".$sql_add_var_name.")";
		return $this->query( $sqlAdd );
	}


	/*
	 * 功       能: 替换唯一数据
	 * 参       数：$p_tabName, string, 表名（不含前缀）
	 * 		    $p_tabVar, array, 替换的数据的数据
	 * 作       者: 刘海艇
	 * 修改日期: 2013-06-24
	 */
	function replace( $p_tabName , $p_tabVar )
	{
		global $_CFG;
		foreach($p_tabVar as $key => $var)
		{
			$sql_addsub_name.= " `".$key."` ,";
			$sql_add_var_name.= " '".mysql_real_escape_string($var)."' ,";
		}
		$sql_addsub_name = substr($sql_addsub_name, 0, -1);
		$sql_add_var_name = substr($sql_add_var_name, 0, -1);

		$sqlAdd = "REPLACE INTO ".$_CFG["DB"]['DB_PREFIX'].$p_tabName." ( ".$sql_addsub_name.") VALUES (".$sql_add_var_name.")";

		return $this->query( $sqlAdd );
	}

	/*
	 * 功       能: 删除数据
	 * 参       数：$p_tabName, string, 表名（不含前缀）
	 * 		    $p_whereStr, string, 查询条件
	 * 作       者: 刘海艇
	 * 修改日期: 2013-06-24
	 */
	function delete( $p_tabName , $p_whereStr )
	{
		global $_CFG;
		$sqlDel = "DELETE FROM ".$_CFG["DB"]['DB_PREFIX'].$p_tabName." WHERE ".$p_whereStr;
		return $this->query( $sqlDel );
	}


	/*
	 * 功       能: 更新数据
	 * 参       数：$p_tabName, string, 表名（不含前缀）
	 * 		    $p_setVarArray, array, 替换数据
	 * 		    $p_whereStr, string, 查询条件
	 * 作       者: 刘海艇
	 * 修改日期: 2013-06-24
	 */
	function update( $p_tabName , $p_setVarArray , $p_whereStr )
	{
		global $_CFG;
		foreach($p_setVarArray as $key => $var)
		{
			$edit_sql.= "`".$key."` = "."'".mysql_real_escape_string($var)."' ,";
		}
		$edit_sql = substr($edit_sql, 0, -1);
		$sqlEdit = "UPDATE `".$_CFG["DB"]['DB_PREFIX'].$p_tabName."` SET ".$edit_sql." WHERE ".$p_whereStr;

		return $this->query( $sqlEdit );
	}

	//返回结果集
	function fetch( $p_rs, $p_type = "array" )
	{
		if( $p_type == "array" )
		{
			return mysql_fetch_array( $p_rs , MYSQL_ASSOC);
		}
		else if( $p_type == "object" )
		{
			return mysql_fetch_object( $p_rs );
		}
		else
		{
			return mysql_fetch_row( $p_rs );
		}
	}

	//获取最后一次记录ID
	function getInsertID()
	{
		return mysql_insert_id($this->_conn);
	}

	//返回最后操作的影响列数
	function getAffectedRows( )
	{
		return mysql_affected_rows();
	}

	//返回结果集列数
	function getNumRows( $p_rs )
	{
		return mysql_num_rows($p_rs);
	}

	//定位结果集指针
	function seek( $p_rs , $p_num )
	{
		return mysql_field_seek( $p_rs, $p_num );
	}

	//释放结果集
	function free( $p_rs )
	{
		return mysql_free_result( $p_rs );
	}

	//关闭数据库连接
	function close()
	{
		return mysql_close( $this->_conn );
	}
}
?>