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

class gglmsViewcorso extends JView {

    function display($tpl = null) {
        global $mainframe;
        $document = JFactory::getDocument();
        $document->addScript('components/com_gglms/js/jquery-ui.min.js');
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/corso.css');
        
        $model =& $this->getModel('corso');
        $corso = $model->getCorso();
        $this->assign('is_trial', $model->is_trial());
        $user = & JFactory::getUser();
        $this->assign('id_utente', $user->id);
        $this->assignRef('corso', $corso);

        parent::display($tpl);
    }
}