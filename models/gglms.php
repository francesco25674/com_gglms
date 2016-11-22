<?php

/**
 * WebTV Model
 * 
 * @package    Joomla.Components
 * @subpackage GGLMS
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * GGLMS Model
 *
 * @package    Joomla.Components
 * @subpackage GGLMS
 */
class gglmsModelgglms extends JModel {

    private $_dbg;
    private $_corsi;
    private $_japp;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
    }

    public function __destruct() {
        
    }

    /**
     * Ritorna l'elenco di tutti i corsi pubblicati.
     * 
     * @param string $where Condizione di selezione della query (default: 'pubblicato=1').
     * @param string $limit Numero di righe restiruite (dafault: tutte).
     * @parma string $orderby Criterio di ordinamento (default: 'ordinamento ASC')
     * @return array Un array contenente un array per ogni riga estratta dalla query.
     */
    public function getCorsi($where='pubblicato=1', $limit=null, $orderby='ordinamento ASC,id ASC') {
        try {
            $query = '
                SELECT
                    id,
                    corso
                FROM #__gg_corsi';
            if (isset($where)) {
                $query .= ' WHERE (' . $where . ')';
            }
            if (isset($orderby)) {
                $query .= ' ORDER BY ' . $orderby;
            }
            if (isset($limit)) {
                $query .= ' LIMIT ' . $limit;
            }

            $this->_db->setQuery($query);

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            if (false === ($results = $this->_db->loadAssocList()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

            $this->_corsi = empty($results) ? array() : $results;

            if ($this->_dbg)
                $this->_japp->enqueueMessage('Fetched rows :' . count($this->_corsi));
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
            $this->_corsi = array();
        }
        return $this->_corsi;
    }

}

// ~@:-]