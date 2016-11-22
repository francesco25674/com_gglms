<?php
class ausind {

	const AUSIND_HOST = '217.133.179.135';
	const AUSIND_USER = 'ausind';
	const AUSIND_PASS = 'aus2012db';
	const AUSIND_DB   = 'chrausind'; 
	
	private $_link;
	private $_error;
	
	public function __construct() {
		$this->_link = null; 
		$this->_error = null;
		$this->_connect();
	}
	
	public function __destruct() {
		if ($this->_link)
			mysql_close($this->_link);
		unset($this->_link);
		unset($this->_error);
	}
	
	private function _connect() {
		try {
			if (false === ($this->_link = mysql_connect(self::AUSIND_HOST, self::AUSIND_USER, self::AUSIND_PASS))) 
				throw new RuntimeException(mysql_errno(), E_USER_ERROR);
			if (false === mysql_select_db(self::AUSIND_DB, $this->_link))
				throw new RuntimeException(mysql_errno($this->_link), E_USER_ERROR);
			logger('connect to ausind');
		} catch(Exception $e) {
			logger($e->getMessage());
			$this->_error = $e;			
		}
	}
	
	public function get($cis_id) {
		/** @todo sistemare il campo attestato */
		$query = 'SELECT u.usr_id, u.usr_user, u.usr_password, u.usr_ragionesociale, u.usr_partitaiva, u.usr_codicefiscale, u.usr_email, 1 AS cis_attestato 
			FROM users AS u 
			INNER JOIN crsiscrizioni AS i ON u.usr_id=i.cis_refUsers 
			WHERE i.cis_id='.$cis_id. ' LIMIT 1';
		logger($query);
		try {
			if (is_null($this->_link))
				throw new RuntimeException('Cannot get Ausind Data', E_USER_ERROR, $this->_error);
			if (false === ($results = mysql_query($query, $this->_link)))
				throw new RuntimeException(mysl_error($this->_link), E_USER_ERROR);
			if (0 == mysql_num_rows($results))
				return false;
			$rows = mysql_fetch_assoc($results);
			mysql_free_result($results);
			return $rows;
		} catch(Exception $e) {
			$this->_error = $e;			
		}
		return false;
	}
	
	public function is_error() {
		return !is_null($this->_error);
	}
	
	public function error() {
		return $this->_error;
	}
}
// ~@:-]
?>