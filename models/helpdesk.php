<?php

/**
 * GGLms HELPDESK Model
 * 
 * @package    Joomla.Components
 * @subpackage HELPDESK
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * GGLMS HELPDESK Model
 * 
 * @package    Joomla.Components
 * @subpackage HELPDESL
 */
class gglmsModelhelpdesk extends JModel {

    private $_dbg;
    private $_japp;
    protected $_db;
    private $_userid;
    private $_user;
    private $_Associati;
    private $_idAssociazione;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
        $this->_db = & JFactory::getDbo();
        $this->_user = & JFactory::getUser();
        $this->_userid = $this->_user->get('id');

//        if ($this->_user->guest) {
//            $msg = "Per accedere al corso è necessario loggarsi";
//            $this->_japp->redirect(JRoute::_('index.php?option=com_comprofiler&task=login'), $msg, 'alert');
//        }
    }

    public function __destruct() {
        
    }

    public function get_HelpAccount() {
        try {
            $query = '
                SELECT
                    d.email_tutor AS `didattico`
                FROM
                    #__user_usergroup_map AS m
                Left Join #__usergroups_details AS d ON d.group_id = m.group_id
                WHERE
                    m.user_id =  ' . $this->_userid . '
                ';

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_HelpAccount = empty($results) ? array() : $results;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
            $this->_HelpAccount = array();
        }

        $this->_HelpAccount['tecnico'] = 'support@ausindfad.it';
        return $this->_HelpAccount;
    }

    public function get_AssocDetails($id) {
        try {
            $query = '
                SELECT
                    *
                FROM
                    #__usergroups_details AS d
                WHERE
                    d.attivo =  1 and
                    d.group_id= ' . $id . '
                ';

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_AssocDetails = empty($results) ? array() : $results;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
            $this->_AssocDetails = array();
        }
        return $this->_AssocDetails;
    }
    
    /*
     * Funzione che ritorna la lista delle Associazioni disponibili in caso non sia specificato un id associazione o l'utente collegato non appartenga a un gruppo.
     * 
     */
    public function get_Associati() {
        try {
            $query = '
                SELECT
                    *
                FROM
                    #__usergroups_details AS d
                WHERE
                    d.attivo =  1 
                ';
            debug::msg($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssocList()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            debug::vardump($results);
            $this->_Associati = empty($results) ? array() : $results;
        } catch (Exception $e) {
            debug::exception($e);
            $this->_Associati = array();
        }
        return $this->_Associati;
    }

    /*
     * Verifico se l'utente appartiene a una delle associazioni
     * Non mi affido a get_UserDetails perchè l'utente potrebbe appartene a piu gruppi e potrebbe non risultare quello dell'associazione 
     * 
     */

    public function get_IdAssociazione() {
        try {
            $query = '
                        SELECT
                            c.gruppo as id 
                        FROM
                            #__gg_coupon AS c
                        WHERE
                            c.id_utente = ' . $this->_userid . '
                        LIMIT 1
                ';
            debug::msg($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadResult('id')))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            debug::vardump($results);
            $this->_idAssociazione = empty($results) ? 0 : $results;
        } catch (Exception $e) {
            debug::exception($e);
            $this->_idAssociazione = array();
        }

        if (!$this->_idAssociazione) {
            $this->_idAssociazione = JRequest::getInt('id', 0);
        }
        return $this->_idAssociazione;
    }

    public function get_UserDetails() {
        try {
            $query = '
                        SELECT
                            u.id AS idutente,
                            u.`name`,
                            u.username,
                            u.email,
                            c.cb_nome,
                            c.cb_cognome,
                            CONCAT(c.cb_nome," ",c.cb_cognome) AS nominativo
                        FROM
                            ihyb8_users AS u
                            Inner Join ihyb8_comprofiler AS c ON c.user_id = u.id
                        WHERE u.id = '.$this->_userid.'
                        LIMIT 1

                ';

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_UserDetails = empty($results) ? array() : $results;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
            $this->_UserDetails = array();
        }

        return $this->_UserDetails;
    }

}
