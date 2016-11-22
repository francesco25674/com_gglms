<?php

/**
 * @version		1
 * @package		gg_lms
 * @author 		antonio
 * @author mail         antonio@ggallery.it
 * @link		
 * @copyright           Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
/*
  defined('SMARTY_DIR') || define('SMARTY_DIR', 'components/com_gglms/models/libs/smarty/smarty/');
  defined('SMARTY_COMPILE_DIR') || define('SMARTY_COMPILE_DIR', 'components/com_gglms/models/cache/compile/');
  defined('SMARTY_CACHE_DIR') || define('SMARTY_CACHE_DIR', 'components/com_gglms/models/cache/');
  defined('SMARTY_TEMPLATE_DIR') || define('SMARTY_TEMPLATE_DIR', 'components/com_gglms/models/templates/');
  defined('SMARTY_CONFIG_DIR') || define('SMARTY_CONFIG_DIR', 'components/com_gglms/models/');
  defined('SMARTY_PLUGINS_DIRS') || define('SMARTY_PLUGINS_DIRS', 'components/com_gglms/models/libs/smarty/extras/');
 */
jimport('joomla.application.component.view');

class gglmsViewhelpdesk extends JView {

    function display($tpl = null) {

        global $mainframe;
        $document = & JFactory::getDocument();
        $document->addScript('components/com_gglms/js/jquery.min.js');
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/helpdesk.css');


        $model = & $this->getModel();
        $id_associazione = $model->get_idAssociazione();
        debug::vardump($id_associazione, 'id_associazione');
        if (isset($_POST['fromname'])) {

            $app = &JFactory::getApplication();

            require_once('components/com_gglms/models/libs/sendmail.class.php');
            require_once('components/com_gglms/models/libs/smarty/EasySmarty.class.php');
//            $mail = new sendmail(MIME_HTML);
//            $mail->from($_POST['fromname'], $_POST['frommail']);
//            $mail->to($_POST['tomail'], RCPT_TO);
//            $mail->subject('Richiesta assistenza');
//            $smarty = new EasySmarty();
//            $smarty->assign('data', $_POST);
//            $mail->body($smarty->fetch_template('helpdesk_mail.tpl', null, true, false, 0));
//            if (!$mail->send())
//                throw new RuntimeException('Error sending mail', E_USER_ERROR);


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



            parent::display('end');
        }

        else {
            if (!empty($id_associazione)) {

                $user = $model->get_UserDetails();
                $associazione = $model->get_AssocDetails($id_associazione);

                $this->assignRef('associazione', $associazione);
                $this->assignRef('user', $user);
                //$this->assignRef('associati', $associati);

                parent::display('vista');
            } else {
                $associati = $model->get_Associati();
                $this->assignRef('associati', $associati);

                parent::display();
            }
        }
    }

}
