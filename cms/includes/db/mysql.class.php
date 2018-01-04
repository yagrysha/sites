<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
class Mysql {
	var $dbname = DBNAME;
	var $dbhost = DBHOST;
	var $dbuser = DBUSER;
	var $dbpassw = DBPASSW;
	var $connection = null;


	function connect(){
		$this->connection = mysql_connect($this->dbhost, $this->dbuser, $this->dbpassw) or Error::fatal( 'Could not connect');
		mysql_select_db($this->dbname, $this->connection) or Error::fatal( 'Could not select database '.$this->dbname);
		if (defined('SET_NAMES')) mysql_query('set names '.constant('SET_NAMES'));
	}
	
	function close(){
		mysql_close($this->connection);
	}

	function getConnection(){
		if ($this->connection == null)	{
			$this->connect();
		}
		return $this->connection;
	}

	function SelectCell($table, $IN=array()){
		return @array_pop($this->SelectOne($table, $IN));
	}

	function SelectOne($table, $IN=array()){
		$IN['limit']=1;
		return @array_pop($this->Select($table, $IN));
	}

	function Select($table, $IN = array(), $index=false){
		if(DEBUG) $starttime = Utils::getmicrotime();
		$select = '';
		if(@is_array($IN['select']) && sizeof($IN['select'])>0){
			foreach ($IN['select'] as $k=>$v){
				$select.=(($k==0)?'':', ').'`'.$v.'`';
			}
		}elseif(@is_string($IN['select']) && strlen($IN['select'])>0 ){
			$select = $IN['select'];
		}else{
			$select='*';
		}

		// get parameters
		$params = '';
		if(@is_array($IN['where']) && sizeof($IN['where'])>0){
			$params = array();
			foreach($IN['where'] as $k=>$v){
				if(is_array($v) && sizeof($where)>0){
					$params[$k] = '`'.$k.'` IN ('.implode(',',$v).')';
				}else{
					$params[$k] = '`'.$k."`='".$v."'";
				}
			}
			$params = ' WHERE '.implode(' AND ', $params);
		}elseif(@is_string($IN['where']) && strlen($IN['where'])>0 ){
			$params = ' WHERE '.$IN['where'];
		}
		if(isset($IN['_xtra'])){
			$params .= ' '.$IN['_xtra']; // AND
		}

		// get limit
		$limit = '';
		if (@(int)$IN['limit']['to']>0){
			$to = (int)$IN['limit']['to'];
			if(@$IN['limit']['page']){
				$from = $IN['limit']['page']*$to-$to;
			}else{
				$from = @(int)$IN['limit']['from'];
			}
			$limit = ' LIMIT '.$from.','.$to;
		}elseif(@(int)$IN['limit']>0){
			$limit = ' LIMIT '.(int)$IN['limit'];
		}

		// get order
		$order = '';
		if(@is_array($IN['order']) && sizeof($IN['order'])>0){
			foreach ($IN['order'] as $k=>$v){
				$order.= (($order)?', ':'').$v.' '.$k;
			}
			$order = ' ORDER BY '.$order;
		}elseif(@is_string($IN['order']) && !empty($IN['order'])){
			$order = ' ORDER BY '.$IN['order'];
		}

		$sql='SELECT '.$select.' FROM '.$table.$params.$order.$limit;

		if(DEBUG) echo $sql.'<br>';

		if(!$result = mysql_query($sql,$this->connection)){
			if(DEBUG) echo mysql_error().'<br>';
			return false;
		}

		$return = array();
		if($index){
			while ($row = mysql_fetch_assoc($result))	$return[$row[$index]] = $row;
		}else{
			while ($row = mysql_fetch_assoc($result))	$return[] = $row;
		}

		if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';

		return $return;
	}

	function sql($sql, $index=false){
		if(DEBUG) {
			$starttime = Utils::getmicrotime();
			echo $sql.'<br>';
		}
		if (!$result = mysql_query($sql,$this->connection)){
			if(DEBUG) echo mysql_error().'<br>';
			return false;
		}
		$return = array();
		if($index){
			while ($row = mysql_fetch_assoc($result))	$return[$row[$index]] = $row;
		}else{
			while ($row = mysql_fetch_assoc($result))	$return[] = $row;
		}
		if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
		return $return;
	}

	function Delete ($table, $IN=array()){
		if(DEBUG) $starttime = Utils::getmicrotime();
		if(is_array($IN) && sizeof($IN)>0){
			$params = array();
			foreach($IN as $k=>$v){
				if(is_array($v) && sizeof($where)>0){
					$params[$k] = '`'.$k.'` IN ('.implode(',',$v).')';
				}else{
					$params[$k] = '`'.$k."`='$v'";
				}
			}
			$sql='DELETE FROM '.$table.' WHERE '.implode(' AND ', $params);
		}elseif(is_string($IN) && !empty($IN) ){
			$sql='DELETE FROM '.$table.' WHERE '.$IN;
		}else{
			$sql = 'TRUNCATE TABLE '.$table;
		}

		if(DEBUG) echo $sql.'<br>';

		if(mysql_query($sql,$this->connection)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return true;
		}
		if(DEBUG) echo mysql_error().'<br>';
		return false;
	}

	function Insert ($table, $IN){
		if(DEBUG) $starttime = Utils::getmicrotime();
		$values = array();
		$keys = array();
		foreach($IN as $k=>$v){
			$keys[] = "`$k`";
			$values[] = "'$v'";
		}
		$sql='INSERT INTO '.$table.' ('.implode(',', $keys).') VALUES('.implode(',', $values).')';
		if(DEBUG) echo $sql.'<br>';

		if(mysql_query($sql,$this->connection)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return mysql_insert_id();
		}
		if(DEBUG) echo mysql_error().'<br>';
		return false;
	}

	function InsertArray ($table, $IN){
		if(DEBUG) $starttime = Utils::getmicrotime();
		$values = array();
		$keys = array();
		foreach ($IN as $it=>$item){
			foreach($item as $k=>$v){
				if($it==0) $keys[] = "`$k`";
				$values[] = "'$v'";
			}
			if($it==0){
				$sql='INSERT INTO '.$table.' ('.implode(',', $keys).') VALUES('.implode(',', $values).')';
			}else{
				$sql.=',('.implode(',', $values).')';
			}
		}
		if(DEBUG) echo $sql.'<br>';

		if(mysql_query($sql,$this->connection)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return mysql_insert_id();
		}
		if(DEBUG) echo mysql_error().'<br>';
		return false;
	}

	function Replace ($table, $IN){
		if(DEBUG) $starttime = Utils::getmicrotime();
		$values = array();
		$keys = array();
		foreach($IN as $k=>$v){
			$keys[] = "`$k`";
			$values[] = "'$v'";
		}
		$sql='REPLACE INTO '.$table.' ('.implode(',', $keys).') VALUES('.implode(',', $values).')';
		if(DEBUG) echo $sql.'<br>';

		if(mysql_query($sql,$this->connection)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return mysql_insert_id();
		}
		if(DEBUG) echo mysql_error().'<br>';
		return false;
	}

	function Update ($table, $set, $where=''){
		if(DEBUG) $starttime = Utils::getmicrotime();
		if(is_array($set) && sizeof($set)>0){
			$var = array();
			foreach($set as $k=>$v){
				$var[] = '`'.$k."`='$v'";
			}
			$var = implode(', ', $var);
		}elseif(is_string($set) && !empty($set)){
			$var = $set;
		}
		$params = '';
		if(is_array($where) && sizeof($where)>0){
			$params = array();
			foreach($where as $k=>$v){
				if(is_array($v) && sizeof($where)>0){
					$params[$k] = '`'.$k.'` IN ('.implode(',',$v).')';
				}else{
					$params[$k] = '`'.$k."`='$v'";
				}
			}
			$params = ' WHERE '.implode(' AND ', $params);
		}elseif(is_string($where) && !empty($where) ){
			$params = ' WHERE '.$where;
		}

		$sql = 'UPDATE '.$table.' SET '.$var.$params;
		if(DEBUG) echo $sql.'<br>';

		if(mysql_query($sql,$this->connection)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return true;
		}
		if(DEBUG) echo mysql_error().'<br>';
		return false;
	}
}