<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once('components/com_gglms/models/libs/errors/debug.class.php');

/**
 * GGlms Attestato Model
 *
 * @package    Joomla.Components
 * @subpackage GGLms
 * @author Diego Brondo <diego@ggallery.it>
 * @version 0.9
 */
class gglmsModelattestato extends JModel {

	private $_user_id;
	//    private $_user;
	private $_quiz_id;
	private $_item_id;

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	public function __destruct() {
		unset($this->_item_id);
		unset($this->_quiz_id);
		unset($this->_user_id);
	}

	/**
	 * Ritorna il certificato dell'utente
	 * La funzione riceve in ingresso l'identificativo dell'utente e dell'elemento di tipo
	 * attestato di cui si vuole il certificato.
	 * Un ulteriore controllo sul superamento del corso viene effettuato.
	 * @param int userid Identificativo dell'utente
	 * @param int $itemid Elemento collegato all'attestato.
	 * @param string $template Template dell'attestato.
	 */
	public function certificate($userid, $itemid, $template = 'attestato.tpl') {
		try {
			$userid = filter_var($userid, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
			if (empty($userid))
				throw new DomainException('Parametro non valido: "' . $userid . '" non sembra un identificativo valido per un utente.', E_USER_ERROR);
			$itemid = filter_var($itemid, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
			if (empty($itemid))
				throw new DomainException('Parametro non valido: "' . $itemid . '" non sembra un identificativo valido per un elemento.', E_USER_ERROR);
			$this->_user_id = $userid;
			$this->_item_id = $itemid;

			$this->_set_quizid();
			if (empty($this->_quiz_id))
				throw new RuntimeException('Impossibile trovare il quiz', E_USER_ERROR);

			if ($this->_check_passed())
				$this->_generate_pdf($template);
		} catch (Exception $e) {
			debug::exception($e);
		}
	}

	/**
	 * Ritorna l'identificativo del quiz che occorre aver superato per poter ottenere l'attestato.
	 */
	private function _set_quizid() {
		try {
			$query = 'SELECT path FROM #__gg_elementi WHERE id=' . $this->_item_id . ' LIMIT 1';
			debug::msg($query);
			$this->_db->setQuery($query);
			if (false === ($results = $this->_db->loadAssoc()))
				throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
			debug::vardump($results);
			$quizid = filter_var($results['path'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
			if (empty($quizid))
				throw new DomainException('Parametro non valido: "' . $quizid . '" non sembra un identificativo di quiz.', E_USER_ERROR);
			$this->_quiz_id = $quizid;
		} catch (Exception $e) {
			debug::exception($e);
			$this->_quiz_id = null;
		}
	}

	/**
	 * Ritorna vero se l'utente $userid ha passato il quiz $quizid
	 * @param int $userid
	 * @param int $quizid
	 * @return int
	 */
	private function _check_passed() {
		try {
			$query = 'SELECT
					COUNT(*) AS passed
					FROM #__quiz_r_student_quiz
					WHERE
					c_student_id=' . $this->_user_id . '  AND
							c_quiz_id=' . $this->_quiz_id . ' AND
									c_passed = 1
									LIMIT 1';
			debug::msg($query);
			$this->_db->setQuery($query);
			if (false === ($results = $this->_db->loadAssoc()))
				throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
			debug::vardump($results);
			return $results['passed'];
		} catch (Exception $e) {
			debug::exception($e);
		}
		return 0;
	}

	private function _generate_pdf($template) {
		try {
			require_once('libs/pdf/certificatePDF.class.php');
			$pdf = new certificatePDF();
			
			if (null === ($datetest = $this->_certificate_datetest()))
				throw new RuntimeException('L\'utente non ha superato l\'esame o lo ha fatto in data ignota', E_USER_ERROR);
			$pdf->set_data($datetest);

			$course_info = $this->_certificate_course_info();
            $pdf->add_data($course_info);
            if (!empty($course_info['attestato']))
                $template = $course_info['attestato'];
			$user_info = $this->_certificate_user_info();
			$pdf->add_data($user_info);

			$certificate_info = $this->_certificate_info();
			$pdf->add_data($certificate_info);

			$this->_set_track();

			$pdf->fetch_pdf_template($template, null, true, false, 0);
			$pdf->Output($course_info['titoloattestato'] . '.pdf', 'D');
			

			return 1;
		} catch (Exception $e) {
			debug::exception($e);
		}
		return 0;
	}

	private function _certificate_datetest() {
		try {
			// la data dell'attestato corrisponde alla data del primo test superato
			$query = 'SELECT
					DATE_FORMAT(c_date_time, \'%d/%m/%Y\') AS datetest
					FROM #__quiz_r_student_quiz
					WHERE
					c_student_id=' . $this->_user_id . '  AND
							c_quiz_id=' . $this->_quiz_id . ' AND
									c_passed = 1
									ORDER BY c_date_time ASC
									LIMIT 1';
			debug::msg($query);
			$this->_db->setQuery($query);
			if (false === ($results = $this->_db->loadAssoc()))
				throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
			debug::vardump($results);
			return $results;
		} catch (Exception $e) {
			debug::exception($e);
			return null;
		}
	}

	private function _certificate_course_info() {
		try {
			$query = 'SELECT
					c.id,
					c.titoloattestato,
                    c.durata,
                    c.attestato
					FROM #__gg_corsi AS c
					INNER JOIN #__gg_corsi_versione ON #__gg_corsi_versione.id_corso = c.id
					INNER JOIN #__gg_moduli ON #__gg_moduli.id_corso = #__gg_corsi_versione.id
					INNER JOIN #__gg_elementi ON #__gg_elementi.id_modulo = #__gg_moduli.id
					WHERE #__gg_elementi.id=' . $this->_item_id . '
							LIMIT 1';
			debug::msg($query);
			$this->_db->setQuery($query);
			if (false === ($results = $this->_db->loadAssoc()))
				throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
			debug::vardump($results);
			return $results;
		} catch (Exception $e) {
			debug::exception($e);
			return array();
		}
	}

	private function _certificate_user_info() {
		try {
			$query = 'SELECT
					p.cb_nome,
					p.cb_cognome,
					DATE_FORMAT(p.cb_datadinascita, \'%d/%m/%Y\') AS cb_datadinascita,
					p.cb_luogodinascita,
					p.cb_provinciadinascita,
					p.cb_societa
					FROM #__users AS u
					INNER JOIN #__comprofiler AS p ON p.user_id = u.id
					WHERE u.id=' . $this->_user_id . '
							LIMIT 1';
			debug::msg($query);
			$this->_db->setQuery($query);
			if (false === ($results = $this->_db->loadAssoc()))
				throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
			$results['cb_provinciadinascita'] = JText::_($results['cb_provinciadinascita']);
			return $results;
		} catch (Exception $e) {
			debug::exception($e);
		}
		return array();
	}

	private function _certificate_info() {
		try {
			$query = 'SELECT
					d.`name`,
					d.dg,
					d.logo
					FROM #__usergroups_details AS d
					INNER JOIN #__gg_coupon AS c ON c.gruppo=d.group_id
					WHERE c.id_utente=' . $this->_user_id;
			debug::msg($query);
			$this->_db->setQuery($query);
			if (false === ($results = $this->_db->loadAssoc()))
				throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
			debug::vardump($results);
			return $results;
			//return array('name' => 'xxx', 'dg' => 'xxx', 'logo' => 'ausind_logo.png');
		} catch (Exception $e) {
			debug::exception($e);
		}
		return array();
	}

	/**
	 * Ritorna vero l'utente è una società
	* @return bool
	* @throws RuntimeException
	*/
	public function is_company() {
		try {
			$query = 'SELECT COUNT(*) FROM #__gg_coupon AS c WHERE c.id_societa=' . $this->_user_id . ' LIMIT 1';
			debug::msg($query);
			$this->_db->setQuery($query);
			if (false === ($results = $this->_db->loadRow()))
				throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
			debug::vardump($results);
			return (bool) $results[0];
		} catch (Exception $e) {
			debug::exception($e);
		}
		return false;
	}

	public function _is_enabled($courseid, $userid) {
		try {
			$query = 'SELECT attestato FROM #__gg_coupon WHERE id_utente=' . $userid . ' AND corsi_abilitati REGEXP \'[[:<:]]' . $courseid . '[[:>:]]\' LIMIT 1';
			if ($this->_dbg)
				$this->_japp->enqueueMessage($query);
			$this->_db->setQuery($query);
			if (false === ($results = $this->_db->loadRow()))
				throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
			if ($this->_dbg)
				$this->_japp->enqueueMessage(var_export($results, true));
			return $results[0];
		} catch (Exception $e) {
			$this->_japp->enqueueMessage($e->getMessage(), $e->getCode());
		}
		return 0;
	}

	public function get_employee_certificate_for_quiz($userid, $quizid) {
		try {
			if (null === ($data_test = $this->_get_datatest($userid, $quizid)))
				throw new RuntimeException('User is not enabled to download certificate', E_USER_ERROR);
			$course_info = $this->_get_course_info($quizid);
			$user_info = $this->_get_user_info($userid);
			$certificate_info = $this->_get_certificate_info($userid);
			$this->_set_track($userid, JRequest::getInt('id', 0));

			$data = array_merge($user_info, $course_info, $data_test, $certificate_info);

			if ($this->_dbg)
				$this->_japp->enqueueMessage(var_export($data, true));
			$this->_generate_pdf($data);
			return 1;
		} catch (Exception $e) {
			$jAp = & JFactory::getApplication();
			$jAp->enqueueMessage($e->getMessage(), $e->getCode());
		}
		return 0;
	}

	public function get_current_user_certificate_for_quiz($quizid) {
		try {
			if (null === ($data_test = $this->_get_datatest($this->_user_id, $quizid)))
				throw new RuntimeException('User is not enabled to download certificate', E_USER_ERROR);
			$course_info = $this->_get_course_info($quizid);

			if (!$this->_is_enabled($course_info['id'], $this->_user_id))
				throw new Exception('Il download dell\'attestato non &egrave; consentito', E_USER_WARNING);

			$user_info = $this->_get_user_info($this->_user_id);
			$certificate_info = $this->_get_certificate_info($this->_user_id);
			$this->_set_track($this->_user_id, JRequest::getInt('id', 0));

			$data = array_merge($user_info, $course_info, $data_test, $certificate_info);
			if ($this->_dbg)
				$this->_japp->enqueueMessage(var_export($data, true));
			$this->_generate_pdf($data);

			return 1;
		} catch (Exception $e) {
			$jAp = & JFactory::getApplication();
			$jAp->enqueueMessage($e->getMessage(), $e->getCode());
		}
		return 0;
	}

	/*
	* Aggiungere voce nella tabella gg_track
	*/

	private function _set_track() {
		try {
			$query = sprintf('INSERT IGNORE INTO #__gg_track VALUES (%d, %d, 1, NOW(), 0)', $this->_item_id, $this->_user_id);
			debug::msg($query);
			$this->_db->setQuery($query);
			$results = $this->_db->query();
		} catch (Exception $e) {
			debug::exception($e);
		}
		return null;
	}

}

// ~@:-]
