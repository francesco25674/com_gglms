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

jimport('joomla.application.component.view');

class gglmsViewattestato extends JView {

    private $_dbg;
    private $_japp;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
    }

    public function display($tpl = null) {
        $itemid = JRequest::getInt('id', 0);
//        $quizid = JRequest::getInt('c', null);
//        if (empty($quizid)) {
//            // visualizzo la lista di attestati per il download
//            $quiz = $model->get_quiz_id_from_item(JRequest::getInt('id', 0));
//            $is_company = $model->is_company();
//            if ($is_company) {
//                // e' un'azienda
//                $certifications = $model->get_employees_certification(array($quiz));
//            } else {
//                //  è un dipendente
//                $certifications = $model->get_current_user_certification(array($quiz));
//            }
//            if ($this->_dbg)
//                $this->_japp->enqueueMessage(var_export($certifications, true));
//            if (empty($certifications))
//                $this->_japp->enqueueMessage('Non ci sono attestati da scaricare');
//            $this->assignRef('is_company', $is_company);
//            $this->assignRef('certifications', $certifications);
//            $id = JRequest::getInt('id', 0);
//            $this->assignRef('id', $id);
//
//            parent::display($tpl);
//        } else {
        // genero il pdf
        $studentid = JRequest::getInt('student', null);
        $model = & $this->getModel();
        if (empty($studentid)) {
            // se è vuoto allora è l'utente correntemente loggato
            $user = & JFactory::getUser();
            $studentid = $user->get('id');
        }
        $model->certificate($studentid, $itemid);
        $this->_japp->close();
//        }
    }

}
