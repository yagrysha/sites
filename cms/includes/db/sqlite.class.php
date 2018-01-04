<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
class Sqlite {
	var $dbname = DBNAME;
	var $connection = null;


	function connect(){
		$this->connection = sqlite_open(DATA_DIR.'sqlite/'.$this->dbname, 0666, $sqliteerror);
		if(!$this->connection) Error::fatal($sqliteerror);
	}
	
	function close(){
		sqlite_close($this->connection);
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
				$select.=(($k==0)?'':', ').$v;
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
					$params[$k] = $k.' IN ('.implode(',',$v).')';
				}else{
					$params[$k] = $k."='".$v."'";
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

		if(!$result = sqlite_unbuffered_query($this->connection,$sql)){
			if(DEBUG) echo sqlite_error_string(sqlite_last_error($this->connection)).'<br>';
			return false;
		}

		$return = array();
		if($index){
			while ($row = sqlite_fetch_array($result, SQLITE_ASSOC))	$return[$row[$index]] = $row;
		}else{
			while ($row = sqlite_fetch_array($result, SQLITE_ASSOC))	$return[] = $row;
		}

		if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';

		return $return;
	}

	function sql($sql, $index=false){
		if(DEBUG) {
			$starttime = Utils::getmicrotime();
			echo $sql.'<br>';
		}
		if (!$result = sqlite_unbuffered_query($this->connection,$sql)){
			if(DEBUG) echo sqlite_error_string(sqlite_last_error($this->connection)).'<br>';
			return false;
		}
		$return = array();
		if($index){
			while ($row = sqlite_fetch_array($result, SQLITE_ASSOC))	$return[$row[$index]] = $row;
		}else{
			while ($row = sqlite_fetch_array($result, SQLITE_ASSOC))	$return[] = $row;
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
					$params[$k] = $k.' IN ('.implode(',',$v).')';
				}else{
					$params[$k] = $k."='$v'";
				}
			}
			$sql='DELETE FROM '.$table.' WHERE '.implode(' AND ', $params);
		}elseif(is_string($IN) && !empty($IN) ){
			$sql='DELETE FROM '.$table.' WHERE '.$IN;
		}else{
			$sql = 'TRUNCATE TABLE '.$table;
		}

		if(DEBUG) echo $sql.'<br>';

		if(sqlite_unbuffered_query($this->connection,$sql)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return true;
		}
		if(DEBUG) echo sqlite_error_string(sqlite_last_error($this->connection)).'<br>';
		return false;
	}

	function Insert ($table, $IN){
		if(DEBUG) $starttime = Utils::getmicrotime();
		$values = array();
		$keys = array();
		foreach($IN as $k=>$v){
			$keys[] = $k;
			$values[] = "'$v'";
		}
		$sql='INSERT INTO '.$table.' ('.implode(',', $keys).') VALUES('.implode(',', $values).')';
		if(DEBUG) echo $sql.'<br>';

		if(sqlite_unbuffered_query($this->connection,$sql)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return sqlite_last_insert_rowid($this->connection);
		}
		if(DEBUG) echo sqlite_error_string(sqlite_last_error($this->connection)).'<br>';
		return false;
	}

	function InsertArray ($table, $IN){
		if(DEBUG) $starttime = Utils::getmicrotime();
		$values = array();
		$keys = array();
		foreach ($IN as $it=>$item){
			foreach($item as $k=>$v){
				if($it==0) $keys[] = $k;
				$values[] = "'$v'";
			}
			if($it==0){
				$sql='INSERT INTO '.$table.' ('.implode(',', $keys).') VALUES('.implode(',', $values).')';
			}else{
				$sql.=',('.implode(',', $values).')';
			}
		}
		if(DEBUG) echo $sql.'<br>';

		if(sqlite_unbuffered_query($this->connection,$sql)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return sqlite_last_insert_rowid($this->connection);
		}
		if(DEBUG) echo sqlite_error_string(sqlite_last_error($this->connection)).'<br>';
		return false;
	}

	function Replace ($table, $IN){
		if(DEBUG) $starttime = Utils::getmicrotime();
		$values = array();
		$keys = array();
		foreach($IN as $k=>$v){
			$keys[] = $k;
			$values[] = "'$v'";
		}
		$sql='REPLACE INTO '.$table.' ('.implode(',', $keys).') VALUES('.implode(',', $values).')';
		if(DEBUG) echo $sql.'<br>';

		if(sqlite_unbuffered_query($this->connection,$sql)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return sqlite_last_insert_rowid($this->connection);
		}
		if(DEBUG) echo sqlite_error_string(sqlite_last_error($this->connection)).'<br>';
		return false;
	}

	function Update ($table, $set, $where=''){
		if(DEBUG) $starttime = Utils::getmicrotime();
		if(is_array($set) && sizeof($set)>0){
			$var = array();
			foreach($set as $k=>$v){
				$var[] = $k."='$v'";
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
					$params[$k] = $k.' IN ('.implode(',',$v).')';
				}else{
					$params[$k] = $k."='$v'";
				}
			}
			$params = ' WHERE '.implode(' AND ', $params);
		}elseif(is_string($where) && !empty($where) ){
			$params = ' WHERE '.$where;
		}

		$sql = 'UPDATE '.$table.' SET '.$var.$params;
		if(DEBUG) echo $sql.'<br>';

		if(sqlite_unbuffered_query($this->connection,$sql)){
			if(DEBUG) echo (Utils::getmicrotime()-$starttime).'<br>';
			return true;
		}
		if(DEBUG) echo sqlite_error_string(sqlite_last_error($this->connection)).'<br>';
		return false;
	}
}