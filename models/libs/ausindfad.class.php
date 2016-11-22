<?php
define('SMARTY_DIR', 'libs/smarty/smarty/');
define('SMARTY_COMPILE_DIR', 'cache/compile/');
define('SMARTY_CACHE_DIR', 'cache/');
define('SMARTY_TEMPLATE_DIR', 'templates/');
define('SMARTY_CONFIG_DIR', './');
define('SMARTY_PLUGINS_DIRS', 'libs/smarty/extras/');

require_once('ausind.class.php');

class ausindfad {

	const AUSINDFAD_HOST = 'localhost';
	const AUSINDFAD_USER = 'ausi1';
	const AUSINDFAD_PASS = 'ausi2';
	const AUSINDFAD_DB   = 'confi';
	const AUSINDFAD_PREFIX = 'ihyb8_';

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
			if (false === ($this->_link = mysql_connect(self::AUSINDFAD_HOST, self::AUSINDFAD_USER, self::AUSINDFAD_PASS)))
				throw new RuntimeException(mysql_errno(), E_USER_ERROR);
			if (false === mysql_select_db(self::AUSINDFAD_DB, $this->_link))
				throw new RuntimeException(mysql_errno($this->_link), E_USER_ERROR);
			logger('connected to ausindfad');
		} catch(Exception $e) {
			logger($e->getMessage());
			$this->_error = $e;
		}
	}

	public function create_new_company_user($data) {
		try {
			$query = 'SELECT 1 AS present FROM #__users WHERE username=\''.mysql_real_escape_string($data['usr_user'], $this->_link).'\' LIMIT 1';
			$results = $this->_query($query, false);
			logger($results);
			if (empty($results)) {
				// creo nuovo utente
				$query = sprintf("INSERT INTO #__users (name, username, password, email, sendEmail, registerDate, activation) VALUES ('%s', '%s', MD5('%s'), '%s', 1, NOW(), 1)",
						$data['usr_ragionesociale'], $data['usr_user'], $data['usr_password'], $data['usr_email']);
				$this->_query($query);
				// get last id
				$query = 'SELECT LAST_INSERT_ID() AS id FROM #__users';
				$results = $this->_query($query, false);
				$id = filter_var($results[0]['id'], FILTER_VALIDATE_INT, array('options'=>array('min_range'=>1)));
				if (empty($id))
					throw new Exception('Cannot get last insert id', E_USER_ERROR);

				// insert in map user [13 = company]
				$query = 'INSERT INTO #__user_usergroup_map VALUES ('.$id.', 13)';
				return $this->_query($query, true);
			}
			return true;
		} catch (Exception $e) {
			$this->_error = $e;
			logger($e->getMessage());
		}
		return false;
	}

	private function _generate_coupon($usr_ragionesociale) {
		return str_replace(' ', '_', substr($usr_ragionesociale, 0, 3)).str_replace('0','k', md5(uniqid('', true)));
	}

	public function insert_coupons($coupon_number, $subscription_id, $course_id) {
		try {
			// ottengo info sull'azienda
			$ausind = new ausind();
			if ($ausind->is_error())
				throw $ausind->error();
			if (false === ($ausind_data = $ausind->get($subscription_id)))
				throw $ausind->error();

			// creo utente azienda su ausindfad
			if (!$this->create_new_company_user($ausind_data))
				throw $this->_error;

			// creo i coupon
			$coupons = array();
			$values = array();
			for ($i=0; $i<$coupon_number; $i++) {
				$coupons[$i] = $this->_generate_coupon($ausind_data['usr_ragionesociale']);
				$values[] = sprintf("('%s', %d, NULL, 11, 0, %d)", $coupons[$i], $course_id, $subscription_id);
			}			
			// li inserisco nel DB
			$query = 'INSERT INTO #__gg_coupon VALUES '.join(',', $values);
			$this->_query($query);
						
			// invio la mail all'azienda con i coupon inseriti
			require_once('libs/sendmail.class.php');
			require_once('libs/smarty/EasySmarty.class.php');
			$mail = new sendmail(MIME_HTML);
			$mail->from('AusindFAD', 'info@ausindfad.it');
			$mail->to($ausind_data['usr_email'], RCPT_TO);
			$mail->to('tony@ggallery.it', RCPT_BCC);
			$mail->to('diego@ggallery.it', RCPT_BCC);
			$mail->subject('Coupon corso AusidFAD');
			$smarty = new EasySmarty();
			$smarty->assign('ausind', $ausind_data);
			$smarty->assign('coupon_number', $coupon_number);
			$smarty->assign('coupons', $coupons);
			$mail->body($smarty->fetch_template('coupons_mail.tpl', null, true, false, 0));
			$mail->send();
			
			// e buona notte al secchio
			return true;
		} catch (Exception $e) {
			logger($e->getMessage());
			$this->_error = $e;
		}
	}

	private function _query($query, $exec=true) {
		if (is_null($this->_link))
			throw new RuntimeException('Cannot get AusindFAD Data', E_USER_ERROR, $this->_error);
		$query = str_replace('#__', self::AUSINDFAD_PREFIX, $query);
		logger($query);
		
		if (false === ($results = mysql_query($query, $this->_link)))
			throw new RuntimeException(mysql_error($this->_link), E_USER_ERROR);
		if ($exec)
			return true;
		if (0 == mysql_num_rows($results))
			return array();
		$rows = array();
		while ($row = mysql_fetch_assoc($results))
			$rows[] = $row;
		mysql_free_result($results);
		return $rows;
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