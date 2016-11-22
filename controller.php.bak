<?php

require_once 'models/libs/errors/debug.class.php';
require_once 'gglms.conf.php';

defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.controller');

class gglmsController extends JController {

    private $_japp;

    public function __construct($config = array()) {
        parent::__construct($config);

        debug::init();
        debug::start(DEBUG_STRATEGY, array('debug_level' => DEBUG_LEVEL));
        debug::client_info();
        debug::vardump(JRequest::getWord('task'), 'task');

        $document = JFactory::getDocument();
        $document->addScript('components/com_gglms/js/jquery.min.js');
        $document->addStyleSheet('components/com_gglms/css/jquery-ui.css');
        $document->addScriptDeclaration('jQuery.noConflict();');

        $this->_japp = JFactory::getApplication();

        $this->registerTask('corso', 'corso');
        $this->registerTask('elemento', 'elemento');
        $this->registerTask('coupon', 'coupon');
        $this->registerTask('check_coupon', 'check_coupon');
        $this->registerTask('generatecoupon', 'generatecoupon');
        $this->registerTask('updateTrack', 'updateTrack');
        $this->registerTask('enablecoupon', 'enablecoupon');
        $this->registerTask('attestato', 'attestato');
        $this->registerTask('helpdesk', 'helpdesk');
        $this->registerTask('helpdesksubmit', 'helpdesksubmit');
        $this->registerTask('openelement', 'openelement');
        $this->registerTask('closeelement', 'closeelement');
        $this->registerTask('switchviewmode', 'switchviewmode');
    }

    public function __destruct() {
        //debug::end(/*DEBUG_STRATEGY*/'log');
        debug::chuck_norris();
    }

    public function corso() {
        JRequest::setVar('view', 'corso');
        parent::display();
//        $view =& $this->getView('corso', 'html');
//        $view->display();
    }

    public function openelement() {
        JRequest::setVar('view', 'corso');
        $model = $this->getModel('corso');
        if ($model->open_all_element())
            $this->_japp->enqueueMessage('Grazie per aver sbloccato il corso');
        else
            $this->_japp->enqueueMessage('Errore nello sblocco del corso, contatta un amministratore', 'error');
        parent::display();
    }

    public function closeelement() {
        JRequest::setVar('view', 'corso');
        $model = $this->getModel('corso');
        if ($model->close_all_element())
            $this->_japp->enqueueMessage('Grazie per aver bloccato il corso');
        else
            $this->_japp->enqueueMessage('Errore nello blocco del corso, contattaun amministratore', 'error');
        parent::display();
    }

    public function helpdesk() {
        JRequest::setVar('view', 'helpdesk');
        parent::display();
    }

    public function helpdesksubmit() {
        JRequest::setVar('view', 'helpdesk');
        $app = &JFactory::getApplication();
        require_once('models/libs/sendmail.class.php');
        require_once('models/libs/smarty/EasySmarty.class.php');
//        $mail = new sendmail(MIME_HTML);
//        $mail->from($_POST['fromname'], $_POST['frommail']);
//        $mail->to($_POST['tomail'], RCPT_TO);
//        $mail->subject('Richiesta assistenza');
//        $smarty = new EasySmarty();
//        $smarty->assign('data', $_POST);
//        $mail->body($smarty->fetch_template('helpdesk_mail.tpl', null, true, false, 0));
//        if (!$mail->send())
//            throw new RuntimeException('Error sending mail', E_USER_ERROR);
        //echo var_export($_POST, true);
        //$app->close();
     
        
            
        //// NOVITA' INSERIMENTO ////
        $mailer = JFactory::getMailer();
        $mailer->setSender($_POST['frommail']);
        $recipient = array($_POST['tomail']);
        $mailer->addRecipient($recipient);
        $mailer->setSubject('Richiesta assistenza');
        $smarty = new EasySmarty();
        $smarty->assign('data', $_POST);
        $mailer->setBody($smarty->fetch_template('helpdesk_mail.tpl', null, true, false, 0));
        if (!$mailer->Send())
            throw new RuntimeException('Error sending mail', E_USER_ERROR);

        $app->close();
        
        
        
        
        
        
        
        
        parent::display('helpdesk');
    }

    public function elemento() {
        JRequest::setVar('view', 'elemento');
        parent::display();
    }

    public function coupon() {
        JRequest::setVar('view', 'coupon');
        parent::display();
    }

    public function check_coupon() {
        $app = &JFactory::getApplication();

        $coupon = JRequest::getVar('coupon');
        $model = $this->getModel('coupon');
        $dettagli_coupon = $model->check_Coupon($coupon);

        if (empty($dettagli_coupon)) {
            $results['report'] = "<p> Il coupon inserito non è valido o è già stato utilizzato. (COD. 01)</p>";
            $results['valido'] = 0;
        } else {
            if (!$dettagli_coupon['abilitato']) {
                $results['report'] = "<p> Il coupon è in attesa di abilitazione. (COD. 03)</p>";
                $results['valido'] = 0;
            } else {
                $model->assegnaCoupon($dettagli_coupon['coupon']);
                $model->iscriviUtente($dettagli_coupon['corsi_abilitati']);
                $model->set_user_groups($dettagli_coupon['coupon']);
                $results['valido'] = 1;
                $results['report'] = "<p> Coupon valido. (COD.04)</p>";
                $results['report'] .= $model->get_listaCorsiFast($dettagli_coupon['corsi_abilitati']);
            }
        }

        echo json_encode($results);
        $app->close();
    }

    /*
     *  Aggiorna il tempo di permanenza sul contenuto
     *
     */

    public function updateTrack() {

//funzione AJAX richiamata dall'elemento ogni cambio slide solo per la prima volta che viene visto il contenuto

        $japp = & JFactory::getApplication();
        $db = & JFactory::getDbo();

        $secondi = JRequest::getVar('secondi');
        $stato = JRequest::getVar('stato');
        $id_elemento = JRequest::getVar('id_elemento');

        $user = & JFactory::getUser();
        $user_id = $user->get('id');

        try {
            if ($stato == 0) {
                $query = '
                UPDATE #__gg_track
                    set tview = ' . $secondi . '
                WHERE id_elemento= ' . $id_elemento . ' AND
                    id_utente=' . $user_id . ' LIMIT 1';
            } else {
            $query = '
                UPDATE #__gg_track
                    set tview = ' . $secondi . ',
                    stato = 1
                WHERE id_elemento= ' . $id_elemento . ' AND
                    id_utente=' . $user_id . ' LIMIT 1';
            }
            debug::msg($query);

            $db->setQuery($query);
            if (false === ($results = $db->query()))
                throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
            echo 1;
        } catch (Exception $e) {
            debug::exception($e);
            echo 0;
        }
        $japp->close();
    }

    public function attestato() {
        JRequest::setVar('view', 'attestato');
        parent::display();
    }

    public function switchviewmode() {
        $japp = & JFactory::getApplication();
        $session = & JFactory::getSession();
        $tpl = JRequest::getVar('tpl');
        if ($tpl == 'flash' || $tpl == 'html5') {
            $session->set('gg_elemento', $tpl);
        }
        $japp->close();
    }

    /**
     * Task che esegue la generazione dei numero di coupon.
     * Viene eseguito in maniera ajax e legge da get la stringa contenente l'XML in base64
     *
     * @param unknown_type $cachable
     */
    function generatecoupon() {
        try {
            $app = & JFactory::getApplication();
            $dbg = JRequest::getBool('dbg', 0);
            // debug::startNested('log', array('debug_level' => $dbg?E_ALL|E_STRICT|DEBUG_INFO|DEBUG_LOG:0, 'logfile' => DEBUG_LOG_FILE, 'append' => false), 'coupon');

            $data_base64 = JRequest::getVar('data', null);

            // FB::log($data_base64, 'data_base64');
            // debug::vardump($data_base64, 'data_base64');
            if (!isset($data_base64))
                throw new DomainException('No data sent', E_USER_ERROR);
            if (false === ($data = base64_decode($data_base64)))
                throw new DomainException('Invalid encoded string sent: ' . $data_base64, E_USER_ERROR);
            
            // debug::vardump($data, 'data');
            // FB::log($data, 'data');

            $xml = new DOMDocument();
            $xml->loadXML($data);
            $xml_data = array();

            // debug::msg('id_iscrizione: ' . $xml->getElementsByTagName('id_iscrizione')->item(0)->nodeValue);

            $xml_data['transition_id'] = filter_var($xml->getElementsByTagName('id_iscrizione')->item(0)->nodeValue, FILTER_SANITIZE_STRING);
            if (empty($xml_data['transition_id']))
                throw new DomainException('id_iscrizione not set or is not valid'.$xml_data['transition_id'], E_USER_ERROR);

            $xml_data['course_id'] = filter_var($xml->getElementsByTagName('id_corso')->item(0)->nodeValue, FILTER_SANITIZE_STRING);
            if (empty($xml_data['course_id']))
                throw new DomainException('id_corso not set or is not valid', E_USER_ERROR);

            $xml_data['ragione_sociale'] = $xml->getElementsByTagName('ragione_sociale')->item(0)->nodeValue;
            if (empty($xml_data['ragione_sociale']))
                throw new DomainException('ragione sociale not set or is not valid', E_USER_ERROR);

            $xml_data['username'] = $xml->getElementsByTagName('piva')->item(0)->nodeValue;
            if (empty($xml_data['username']))
                throw new DomainException('piva not set or is not valid', E_USER_ERROR);

            $xml_data['coupon_number'] = filter_var($xml->getElementsByTagName('coupon')->item(0)->nodeValue, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($xml_data['coupon_number']))
                throw new DomainException('coupon not set or is not valid', E_USER_ERROR);

            $xml_data['email'] = filter_var($xml->getElementsByTagName('email')->item(0)->nodeValue, FILTER_VALIDATE_EMAIL);
            if (empty($xml_data['email']))
                throw new DomainException('email not set or is not valid', E_USER_ERROR);

            $xml_data['id_associazione'] = filter_var($xml->getElementsByTagName('id')->item(0)->nodeValue, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($xml_data['id_associazione']))
                throw new DomainException('id not set or is not valid', E_USER_ERROR);

            $xml_data['attestato'] = filter_var($xml->getElementsByTagName('attestato')->item(0)->nodeValue, FILTER_VALIDATE_BOOLEAN);
            if (is_null($xml_data['attestato']))
                throw new DomainException('attestato not set or is not valid', E_USER_ERROR);

             debug::vardump($xml_data, 'xml_data');

            $model = $this->getModel('generatecoupon');
            $ret = ($model->insert_coupons($xml_data) ? 'ok' : 'no');
            // debug::vardump($ret, 'return');
        } catch (Exception $e) {
            // debug::exception($e);
            $ret = 'no';
        }
        //header('content-type: application/json; charset=utf-8');
        //echo $_GET['callback'] . '(' . json_encode($ret) . ')';
        echo $ret;
        //debug::end('log', 'coupon');
        $app->close();
    }

    public function enablecoupon() {
        try {
            fopen(DEBUG_LOG_FILE, 'w');
            $app = &JFactory::getApplication();

            $data_base64 = JRequest::getVar('data', null);
            $this->_logger($data_base64);
            if (!isset($data_base64))
                throw new DomainException('Param data not set', E_USER_ERROR);
            if (false === ($data = base64_decode($data_base64)))
                throw new DomainException('Param data is not valid', E_USER_ERROR);
            $this->_logger($data);

            $xml = new DOMDocument();
            $xml->loadXML($data);
            $xml_data = array();

            $xml_data['id_associazione'] = filter_var($xml->getElementsByTagName('id')->item(0)->nodeValue, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($xml_data['id_associazione']))
                throw new DomainException('id not set or is not valid', E_USER_ERROR);

            $xml_data['transition_id'] = filter_var($xml->getElementsByTagName('id_iscrizione')->item(0)->nodeValue, FILTER_SANITIZE_STRING);
            if (empty($xml_data['transition_id']))
                throw new DomainException('id_iscrizione not set or is not valid', E_USER_ERROR);

            $xml_data['token'] = $xml->getElementsByTagName('token')->item(0)->nodeValue;
            $this->_logger($xml_data['token']);
            if (empty($xml_data['token']))
                throw new DomainException('token not set or not valid', E_USER_ERROR);

            $model = $this->getModel('enablecoupon');

            echo ($model->enable_coupons($xml_data) ? 'ok' : 'no');
        } catch (Exception $e) {
            echo 'no';
            $this->_logger($e);
        }
        $app->close();
    }

    private function _logger($msg) {
        if ($this->_dbg) {
            if (!is_string($msg))
                $msg = var_export($msg, true);
            error_log($msg . "\n", 3, DEBUG_LOG_FILE);
        }
    }

}