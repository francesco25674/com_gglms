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
class gglmsModelelemento extends JModel {

    private $_dbg;
    private $_japp;
    private $_elemento;
    private $_user;
    private $_user_id;
    private $_track;
    private $_id;
    private $_iscrizione;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
        $this->_db = & JFactory::getDbo();
        $user = & JFactory::getUser();
        $this->_user_id = $user->get('id');
        $this->_id = JRequest::getInt('id', 0);

        $this->_user = & JFactory::getUser();
        if ($this->_user->guest) {
            //TODO Personalizzare il messaggio per i non registrati
            $msg = "Per accedere al corso è necessario loggarsi";

            //TODO Sistemare per fare in modo che dopo il login torni al corso
            $this->_japp->redirect(JRoute::_('index.php?option=com_comprofiler&task=login'), $msg);
        }

        $this->checkIscrizione();
        $this->setTrack();
    }

    public function __destruct() {
        
    }

    /**
     * Ritorna tutti gli elementi dell'id corso passato in url (idc). 
     * L'id del contenuto viene letto da URL e deve essere un intero valido.
     * 
     * @return array
     */
    public function getElemento() {
        try {
            $query = 'SELECT
                    e.*,
                    m.id AS idmodulo,
                    c.id AS idcorso,
                    c.corso AS nomecorso
                FROM
                    #__gg_elementi AS e
                Left Join #__gg_moduli AS m ON m.id = e.id_modulo
                Left Join #__gg_corsi_versione AS v ON m.id_corso = v.id 
                Left Join #__gg_corsi AS c ON c.id = v.id_corso
                WHERE
                    e.id=' . $this->_id . ' AND (tipologia=\'contenuto\' OR tipologia=\'riepilogo\') 
                LIMIT 1';
            debug::msg($query);
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_elemento = empty($results) ? array() : $results;
            if (!empty($results)) {
                if (!empty($results['propedeuticita'])) {
                    // controllo propedeuticità
                    $check = $this->_chek_prerequisites($this->_user_id, $results['propedeuticita']);
                    if (!$check) {
                        $this->_japp->redirect('index.php?option=com_gglms&view=corso&id=' . $results['idcorso'], 'Propedeuticità non soddisfatta per accedere a questo contenuto', 'error');
                        $this->_elemento = array();
                    }
                }
            } else {
                $this->_japp->redirect('index.php', 'Contenuto inesistente', 'error');
            }
        } catch (Exception $e) {
            debug::exception($e);
            $this->_elemento = array();
        }
        $this->_elemento['track'] = $this->getTrack();
        return $this->_elemento;
    }

    /**
     * Legge i jumper da file XML.
     * Cerca il file in  "$content_path/$itemid/$itemid.xml".
     * 
     * @param int $itemid ID del contenuto di cui si vogliono i jumper. 
     * @param string $content_path Percorso dove cercare il file XML.
     * @return array 
     */
    public function getJumperXML($path) {
        try {

            if ($this->_dbg) {
                $this->_japp->enqueueMessage('Read file XML jumper file: ' . $path);
            }
            $jumpers = array();
            $xml = new DOMDocument();
            $xml->load($path);
            $cue_points = $xml->getElementsByTagName('CuePoint');
            $i = 0;
            foreach ($cue_points as $point) {
                foreach ($point->childNodes as $node) {
                    if ('Time' == $node->nodeName)
                        $jumpers[$i]['tstart'] = $node->nodeValue;
                    elseif ('Name' == $node->nodeName)
                        $jumpers[$i]['titolo'] = $node->nodeValue;
                }
                $i++;/** @todo se il nodo non contiene time e name incremento i e non faccio nessun controllo se il jumper abbia 2 elementi tstart e titolo */
            }
            unset($xml);
            unset($cue_points);

            if ($this->_dbg)
                var_dump($jumpers);

            return $jumpers;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_webtv.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
        }
        return 0;
    }

    /*
     * Aggiunge il recordo nella tabella Track impostando lo stato di superamento a 0
     *  
     * 
     */

    public function setTrack() {
        try {

            $query = '
                INSERT IGNORE INTO #__gg_track
                    (id_elemento, id_utente, stato, data )
                VALUES
                    (' . $this->_id . ', ' . $this->_user_id . ', 0, NOW() )';


            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
        }
    }

//    /*
//     * Aggiorna il tempo di permanenza in secondi nella tabella track
//     * ATTENZIONE: F
//     * 
//     */
//
//    public function updateTrack($secondi, $stato, $id_elemento) {
//        try {
//            $query = '
//                UPDATE #__gg_track
//                    set tview = ' . $secondi . ',                
//                    stato = ' . $stato . '                
//                WHERE id_elemento= ' . $id_elemento . ' AND 
//                    id_utente=' . $this->_user_id . ' ';
//
//
//            if ($this->_dbg)
//                $this->_japp->enqueueMessage($query);
//
//            $this->_db->setQuery($query);
//            if (false === ($results = $this->_db->query()))
//                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
//        } catch (Exception $e) {
//            jimport('joomla.error.log');
//            $log = &JLog::getInstance('com_gglms.log.php');
//            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
//            if ($this->_dbg)
//                $this->_japp->enqueueMessage($e->getMessage(), 'error');
//        }
//
//        return $query;
//    }

    public function getTrack() {
        try {

            $user = & JFactory::getUser();
            $user_id = $user->get('id');

            $query = '
                SELECT 
                    *
                FROM
                    #__gg_track
                WHERE id_elemento= ' . $this->_id . ' AND 
                    id_utente=' . $this->_user_id . ' ';


            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_track = empty($results) ? array() : $results;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
            $this->_track = array();
        }

        return $this->_track;
    }

    private function _add_ending_slash($path) {
        return $path . ((substr($path, strlen($path) - 1, 1) != '/') ? '/' : '');
    }

    public function checkIscrizione() {

        try {
            $query = '
                    SELECT
                        i.id_utente,
                        i.id_corso
                    FROM
                        #__gg_corsi_versione AS v
                    Inner Join #__gg_moduli AS m ON m.id_corso = v.id
                    Inner Join #__gg_elementi AS e ON e.id_modulo = m.id
                    Inner Join #__gg_iscrizioni AS i ON v.id_corso = i.id_corso
                    WHERE 
                        e.id = ' . $this->_id . '
                        AND
                        i.id_utente =  ' . $this->_user_id . '
                    ';

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_iscrizione = empty($results) ? array() : $results;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
            $this->_iscrizione = array();
        }
        if (!$this->_iscrizione) {
            //TODO Personalizzare il messaggio per i non registrati
            $msg = "Non sei iscritto a questo corso. Se disponi di un coupon puoi riscattarlo qui sotto.";

            $this->_japp->redirect(JRoute::_('index.php?option=com_gglms&view=coupon'), $msg, 'error');
        }
    }

    private function _chek_prerequisites($user_id, $prerequisites) {
        try {
            debug::vardump($prerequisites);
            $query = 'SELECT id, path, tipologia FROM #__gg_elementi WHERE id IN (' . $prerequisites . ')';
            debug::msg($query);
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssocList()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            if (empty($results))
                throw new RuntimeException('Impossibile recuperare le informazioni di propedeuticità per gli elementi ' . $prerequisites, E_ERROR);
            foreach ($results as $r) {
                debug::vardump($r);
                if ($r['tipologia'] == 'contenuto') {
                    $query = 'SELECT stato FROM #__gg_track WHERE id_elemento=' . $r['id'] . ' AND id_utente=' . $user_id . ' AND stato=1 LIMIT 1';
                    debug::msg($query);
                    $this->_db->setQuery($query);
                    if (false === ($check = $this->_db->loadAssoc()))
                        throw new RuntimeException($this->_db->getErrorMsg(), E_ERROR);
                    if (empty($check))
                        return 0;
                } elseif ($r['tipologia'] == 'quiz') {
                    $query = 'SELECT c_passed FROM #__quiz_r_student_quiz WHERE c_student_id='.$user_id.' AND c_quiz_id=' .$r['path']. ' AND c_passed=1 LIMIT 1';
                    debug::msg($query);
                    $this->_db->setQuery($query);
                    if (false === ($check = $this->_db->loadAssoc()))
                        throw new RuntimeException($this->_db->getErrorMsg(), E_ERROR);
                    if (empty($check))
                        return 0;
                }
            }
            return 1;
        } catch (Exception $e) {
            debug::exception($e);
            return 0;
        }
    }

}
