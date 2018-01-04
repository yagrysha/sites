<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.10.2007
 * 
 */
//mysql
class Mysql {
	var $dbname = DBNAME;
	var $dbhost = DBHOST;
	var $dbuser = DBUSER;
	var $dbpassw = DBPASSW;
	var $charset = 'cp1251';
	var $connection=null;

	
	function connect(){
		$this->connection = mysql_connect($this->dbhost, $this->dbuser, $this->dbpassw) or Error::fatal( 'Could not connect');
		mysql_select_db($this->dbname, $this->connection) or Error::fatal( 'Could not select database');
		if (SET_NAMES) mysql_query('set names '.$this->charset);
	}

	function SelectCell($table, $IN){
		return array_pop($this->SelectOne($table,$IN));
	}
	
	
	function SelectOne($table, $IN){
		$IN['limit']=array('from'=>0,'to'=>1);
		return @array_pop($this->Select($table,$IN));
	}
	
	/**
    *'select'=>'','where'=>array(), 'limit'=>array('from'=>0,'to'=>0),'order'=>'','_xtra'=>''
    */
	function Select($table, $IN = array()){
		if(@is_array($IN['select']) && sizeof($IN['select'])>0){
			$select = implode(' , ', $IN['select']);
		}elseif(@is_string($IN['select']) && strlen(trim($IN['select']))>0 ){
			$select = $IN['select'];
		}else{
			$select='*';
		}

		// get parameters
		if(@is_array($IN['where']) && sizeof($IN["where"])>0){
			$params = array();
			foreach($IN['where'] as $k=>$v){
				if(strpos($v,',') && !strpos($v,'\'') && !strpos($v,'"')){
					$params[$k] = $k.' IN ('.$v.')';
				}else{
					$params[$k] = $k."='".$v."'";
				}
			}
			$params = implode(' AND ', $params);
			$params = ' WHERE '.$params;
		}elseif(@is_string($IN['where']) && !empty($IN['where']) ){
			$params = ' WHERE '.$IN['where'];
		}else{
			$params = '';
		}
		if(isset($IN['_xtra'])){
			if($params){
				$params .= ' '.$IN['_xtra']; // AND
			}else{
				$params = ' WHERE '.$IN['_xtra'];
			}
		}
		// get limit
		$sql_limit['from'] = (isset($IN['limit']['page']) && isset($IN['limit']['to']))
			?($IN['limit']['page']*$IN['limit']['to']-$IN['limit']['to'])
			:@(int)$IN['limit']['from'];
		$sql_limit['to'] = @(int)$IN['limit']['to'];
		if(empty($sql_limit['to']) && empty($sql_limit['from'])){
			$limit = '';
		}elseif(empty($sql_limit["to"])){
			$limit = ' limit '.$sql_limit['from'].','.NODESIZE;
		}else{
			$limit = ' limit '.$sql_limit['from'].','.$sql_limit['to'];
		}
		// get order
		$order = '';
		if(@is_array($IN['order'])){
			$key = array_keys($IN['order']);
			$key = $key[0];
			$order = ' order by '.$IN['order'][$key].' '.$key;
		}

		$sql='SELECT '.$select.' FROM '.$table.$params.$order.$limit;

		if(DEBUG) {
			$starttime = Utils::getmicrotime();
			echo $sql.'<br>';
		}

		if(!$result = mysql_query($sql,$this->connection)){
			if(DEBUG) echo mysql_error().'<br>';
			return false;
		}
		
		$return = array();
		while ($row = mysql_fetch_assoc($result)) $return[] = $row;
		if(DEBUG) {
			$endtime = Utils::getmicrotime();
			echo ($endtime-$starttime).'<br>';
		}
		return $return;
	}

	function sql($sql){
		if(DEBUG) {
			$starttime = Utils::getmicrotime();
			echo $sql.'<br>';
		}
		if (!$result = mysql_query($sql,$this->connection)){
			if(DEBUG) echo mysql_error().'<br>';
			return false;
		}
		$return = array();
		while ($row = mysql_fetch_assoc($result))
	   	   $return[] = $row;
		if(DEBUG) {
			$endtime = Utils::getmicrotime();
			echo ($endtime-$starttime).'<br>';
		}
		return $return;
	}

	function Delete ($table, $IN=array()){
		if(is_array($IN) && sizeof($IN)>0){
			$params = array();
			foreach($IN as $k=>$v){
				if(strpos($v,',') && !strpos($v,'\'') && !strpos($v,'"')){
					$params[$k] = $k.' IN ('.$v.')';
				}else{
					$params[$k] = $k."='".$v."'";
				}
			}
			$params = implode(' and ', $params);
		}elseif(is_string($IN) && !empty($IN) ){
			$params = $IN;
		}else{
			$params = '';
		}
		$sql='DELETE FROM '.$table.' WHERE '.$params;
		if(DEBUG) {
			$starttime = Utils::getmicrotime();
			echo $sql.'<br>';
		}
		if(mysql_query($sql,$this->connection)){
			if(DEBUG) {
				$endtime = Utils::getmicrotime();
				echo ($endtime-$starttime).'<br>';
			}
			return true;
		}
		if(DEBUG) echo mysql_error().'<br>';
		return false;
	}

	function Insert ($table, $IN){
		$values = array();
		$keys = array();
		foreach($IN as $k=>$v){
			$keys[] = "`$k`";
			$values[] = "'".$v."'";
		}
		$keys = implode(',', $keys);
		$values = implode(',', $values);
		$sql='INSERT INTO '.$table.'('.$keys.') values('.$values.')';
		if(DEBUG) {
			$starttime = Utils::getmicrotime();
			echo $sql.'<br>';
		}
		if(mysql_query($sql,$this->connection)){
			if(DEBUG) {
				$endtime = Utils::getmicrotime();
				echo ($endtime-$starttime).'<br>';
			}
			return mysql_insert_id();
		}
		if(DEBUG) {
			echo mysql_error().'<br>';
		}
		return false;
	}

	function Update ($table, $set, $where=''){
		if(is_array($set) && sizeof($set)>0){
			$var = array();
			foreach($set as $k=>$v){
				$var[] = '`'.$k."`='".$v."'";
			}
			$var = implode(', ', $var);
		}elseif(is_string($set)&&!empty($set)){
			$var = $set;
		}
		$params = '';
		if(is_array($where) && sizeof($where)>0){
			$params = array();
			foreach($where as $k=>$v){
				if(strpos($v,',') && !strpos($v,'\'') && !strpos($v,'"')){
					$params[$k] = '`'.$k.'` IN ('.$v.')';
				}else{
					$params[$k] = '`'.$k."`='".$v."'";
				}
			}
			$params = implode(' AND ', $params);
			$params = ' WHERE '.$params;
		}elseif(is_string($where) && !empty($where) ){
			$params = ' WHERE '.$params;
		}

		$sql = 'UPDATE '.$table.' SET '.$var.$params;
		if(DEBUG) {
			$starttime = Utils::getmicrotime();
			echo $sql.'<br>';
		}
		if(mysql_query($sql,$this->connection)){
			if(DEBUG) {
				$endtime = Utils::getmicrotime();
				echo ($endtime-$starttime).'<br>';
			}
			return true;
		}
		if(DEBUG) echo mysql_error().'<br>';
		return false;
	}
}
?>