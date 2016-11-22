<?php

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
class gglmsModelcoupon extends JModel {

    private $_dbg;
    private $_japp;
    private $_coupon;
    private $_ausind_confindustria_option;
    protected $_db;
    protected $_db2;
    private $_abilitato;
    private $_userid;
    private $_user;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
        $this->_db = & JFactory::getDbo();
        $this->_user = & JFactory::getUser();
        $this->_userid = $this->_user->get('id');

        if ($this->_user->guest) {
//TODO Personalizzare il messaggio per i non registrati
            $msg = "Per accedere al corso è necessario loggarsi";

//TODO Sistemare per fare in modo che dopo il login torni al corso
            $this->_japp->redirect(JRoute::_('index.php?option=com_comprofiler&task=login'), $msg, 'alert');
        }
    }

    public function __destruct() {
        
    }

    public function check_Coupon($coupon) {
        try {
            $query = '
                SELECT
                    *
                FROM 
                    #__gg_coupon as c
                WHERE 
                    c.coupon = "' . $coupon . '"
                        AND 
                    c.id_utente IS NULL
                ';

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_coupon = empty($results) ? array() : $results;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
            $this->_coupon = array();
        }
        return $this->_coupon;
    }

    /**
     * Inserisce l'utente negli stessi gruppi cui è iscritta la società di appartenenza.
     * Operazione necessaria per l'accesso ai form di discussione.
     * 
     * @param string $coupon 
     * @return bool
     */
    public function set_user_groups($coupon) {
        try {
            if (empty($coupon))
                throw new BadMethodCallException('Parametro non valido: coupon non è impostato', E_USER_ERROR);

            // ottendo l'id della società
            $query = 'SELECT id_societa FROM #__gg_coupon WHERE coupon=\'' . $coupon . '\' LIMIT 1';
            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $company_id = filter_var($results['id_societa'], FILTER_VALIDATE_INT);
            if (empty($company_id))
                throw RuntimeException('Cannot get company ID from database', E_USER_ERROR);

            // aggiorno i gruppi dell'utente
            $query = 'INSERT INTO #__user_usergroup_map (user_id, group_id)
                SELECT ' . $this->_userid . ', g.id AS group_id
                    FROM #__usergroups AS g
                    INNER JOIN #__users AS u ON u.name=g.title
                    WHERE u.id=' . $company_id;
            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        } catch (Exception $e) {
            return false;
        }
    }

    /*
     * Aggiorno la tabella coupon inserendo l'id dell'utente che sta utilizzando quel coupon
     */

    public function assegnaCoupon($coupon) {

        try {
            $query = '
                UPDATE
                    #__gg_coupon 
                SET id_utente = ' . $this->_userid . ', 
                data_utilizzo = NOW()
                WHERE 
                    coupon = "' . $coupon . '"
                ';

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);



            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
        }
        return true;
    }

    /*
     * Iscrive l'utente loggato ai relativi corsi 
     * 
     * paramento id_corsi specificato nella tabella coupon : stringa di id corsi separati da virgola 
     * 
     */

    public function iscriviUtente($id_corsi) {

        //id_corsi potrebbe essere una stringa di id separati da virgola.
        $id_corsi_array = explode(",", $id_corsi);

        foreach ($id_corsi_array as $id_corso) {

            try {
                $query = '
                INSERT IGNORE INTO
                    #__gg_iscrizioni
                    (id_corso,id_utente) 
                VALUE
                    (' . $id_corso . ',' . $this->_userid . ')
                ';

                if ($this->_dbg)
                    $this->_japp->enqueueMessage($query);

                $this->_db->setQuery($query);
                if (false === ($results = $this->_db->query()))
                    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            } catch (Exception $e) {
                jimport('joomla.error.log');
                $log = &JLog::getInstance('com_gglms.log.php');
                $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            }
        }
        return true;
    }

    /*
     * Gli passo una stringa di id corsi e mi restituisce la lista dei corsi con relativi link.
     * 
     */

    public function get_listaCorsiFast($id_corsi) {

        $id_corsi_array = explode(",", $id_corsi);
        if (count($id_corsi_array) > 1)
            $report = "<p><h3>Sei iscritto ai seguenti corsi: </h3></p> ";
        else
            $report = "<p><h3>Sei iscritto al seguente corso: </h3></p> ";

        foreach ($id_corsi_array as $id_corso) {
            try {
                $query = '
                SELECT
                     id,
                     corso
                FROM
                    #__gg_corsi as c
                WHERE 
                    c.id=' . $id_corso . '
                ';

                if ($this->_dbg)
                    $this->_japp->enqueueMessage($query);

                $this->_db->setQuery($query);
                if (false === ($results = $this->_db->loadAssoc()))
                    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                $corso = empty($results) ? array() : $results;
            } catch (Exception $e) {
                jimport('joomla.error.log');
                $log = &JLog::getInstance('com_gglms.log.php');
                $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            }
            $report.='<p><a href="index.php?option=com_gglms&view=corso&id=' . $corso['id'] . '">' . $corso['corso'] . '</a></p>';
        }
        return $report;
    }

    /*
     * Metodo che serviva per verificare l'abilitazione del coupon sul sito di Confindustria.
     * All'attivazione della verifica coupon "passiva" attraverso WEBSERVICE non è più necessaria.
     * La lascio se servisse per connessione a DB esterni.
     * 
     */

    public function verifica_Abilitato($id_iscrizione) {
        $this->_ausind_confindustria_option = array(
            'driver' => 'mysql',
            'host' => '217.133.179.135',
            'user' => 'ausind',
            'password' => 'aus2012db',
            'database' => 'chrausind'
        );
        $this->_db2 = & JDatabase::getInstance($this->_ausind_confindustria_option);

        try {
            $query = '
                SELECT
                    i.cis_tipoPagamento as result
                FROM
                    crsiscrizioni AS i
                WHERE
                    i.cis_id = "' . $id_iscrizione . '"
                ';

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db2->setQuery($query);
            if (false === ($results = $this->_db2->loadAssoc()))
                throw new RuntimeException($this->_db2->getErrorMsg(), E_USER_ERROR);
            $this->_abilitato = empty($results) ? array() : $results;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
            $this->_abilitato = array();
        }
        $this->abilita_Coupon($id_iscrizione);
        return $this->_abilitato;
    }

}
