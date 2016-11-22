<?php

error_reporting(E_ALL & ~E_NOTICE);
defined('_JEXEC') or die('Restricted access');
define('SMARTY_DIR', 'components/com_gglms/models/libs/smarty/smarty/');
define('SMARTY_COMPILE_DIR', 'components/com_gglms/models/cache/compile/');
define('SMARTY_CACHE_DIR', 'components/com_gglms/models/cache/');
define('SMARTY_TEMPLATE_DIR', 'components/com_gglms/models/templates/');
define('SMARTY_CONFIG_DIR', 'components/com_gglms/models/');
define('SMARTY_PLUGINS_DIRS', 'components/com_gglms/models/libs/smarty/extras/');

jimport('joomla.application.component.model');

class gglmsModelEnablecoupon extends JModel {

    private $_dbg;
    private $_japp;
    protected $_db;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
        $this->_db = & JFactory::getDbo();
    }

    public function __destruct() {
        
    }

    public function enable_coupons($data) {
        try {
            // test sul token

            // if (!$this->_check_token($data['id_associazione'], $data['token']))
            //     throw new RuntimeException('Token test failed', E_USER_ERROR);

            $query = 'UPDATE #__gg_coupon SET abilitato=1, data_abilitazione=NOW() WHERE id_iscrizione=\'' . $data['transition_id'] . '\' AND gruppo=' . $data['id_associazione'];
            $this->_logger($query);
            $this->_db->setQuery($query);
            if (false === $this->_db->query())
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);




//          invio la mail all'azienda con conferma
            $query = 'SELECT u.name, u.username, u.email FROM #__gg_coupon AS c INNER JOIN #__users AS u ON u.id = c.id_societa WHERE c.id_iscrizione=\'' . $data['transition_id'] . '\' LIMIT 1';
            $this->_logger($query);
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->LoadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $name = isset($results['name']) ? $results['name'] : null;
            $username = isset($results['username']) ? $results['username'] : null;
            $email = isset($results['email']) ? $results['email'] : null;
            
            if (is_null($email))
                throw new RuntimeException('Cannot get company mail', E_USER_ERROR);

            $query = 'SELECT dominio, email_riferimento FROM #__usergroups_details WHERE group_id=' . $data['id_associazione'] . ' LIMIT 1';
            $this->_db->setQuery($query);
            
            if (false === ($results = $this->_db->loadRow()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

            
            
            $this->_logger($results);
            $data['associazione_name'] = $results[0];
            $data['associazione_url'] = 'http://www.' . strtolower($results[0]) . '/';
            $data['email_riferimento'] = $results[1];

            
            
            
            require_once('libs/sendmail.class.php');
            require_once('libs/smarty/EasySmarty.class.php');
            
            $mail = new sendmail(MIME_HTML);
            $mail->from($data['associazione_name'], $data['email_riferimento']);
            $mail->to($email, RCPT_TO);
            $mail->to('antonio@ggallery.it', RCPT_BCC);
            $mail->to($data['email_riferimento'], RCPT_BCC);
            $mail->subject('Conferma abilitazione coupon corso ' . $data['associazione_name']);
            $smarty = new EasySmarty();
            $smarty->assign('ausind', $data);
            $smarty->assign('ragione_sociale', $name);
            $smarty->assign('username', $username);
            $mail->body($smarty->fetch_template('coupons_enabled_mail.tpl', null, true, false, 0));
            if (!$mail->send())
                throw new RuntimeException('Error sending mail', E_USER_ERROR);

            
                // NUOVO SISTEMA MAILING
//            
//            require_once('libs/smarty/EasySmarty.class.php');
//            $mailer = JFactory::getMailer(); 
//            $mailer->setSender($data['associazione_name'], $data['email_riferimento']);
//            $recipient = array($email,  $results['email_riferimento'],  'antonio@ggallery.it');
//            $mailer->addRecipient($recipient);
//            $mailer->setSubject('Conferma abilitazione coupon corso ' . $data['associazione_name']);
//            
//            $smarty = new EasySmarty();
//            $smarty->assign('ausind', $data);
//            $smarty->assign('ragione_sociale', $name);
//            $smarty->assign('username', $username);
//            $mailer->body($smarty->fetch_template('coupons_enabled_mail.tpl', null, true, false, 0));
//            if (!$mailer->send())
//                throw new RuntimeException('Error sending mail', E_USER_ERROR);
            
            // FINE NUOVO SISTEMA MAILING
            
            
            
            
            // e buona notte al secchio
            $this->_logger('success');
            return true;
        } catch (Exception $e) {
            $this->_logger($e->getMessage());
        }
        return false;
    }

    private function _check_token($id, $token) {
        $query = 'SELECT username FROM #__users WHERE usertype=' . $id . ' LIMIT 1';
        $this->_logger($query);
        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadRow()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        $this->_logger($results);
        $username = isset($results[0]) ? $results[0] : null;
        if (is_null($username))
            throw new DomainException('Invalid Username', E_USER_ERROR);
        
//        return md5($username . date('Y-m-d')) == $token;
        return date('Y-m-d') == $token;
    }

    private function _logger($msg) {
        if ($this->_dbg) {
            if (!is_string($msg))
                $msg = var_export($msg, true);
            error_log($msg . "\n", 3, DEBUG_LOG_FILE);
        }
    }

}

// ~@:-]