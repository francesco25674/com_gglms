<?php

/**
 * @version		1
 * @package		webtv
 * @author 		antonio
 * @author mail	tony@bslt.it
 * @link		
 * @copyright	Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class gglmsViewgglms extends JView {

    function display($tpl = null) {
        $document = & JFactory::getDocument();
        $document->addScript('components/com_gglms/js/jquery-ui.min.js');
        //$document->addStyleSheet(JURI::root(true) . '/components/com_webtv/css/tv_home.css');

        $model = & $this->getModel();
        $Corsi = $model->getCorsi();
        
        $this->assignRef('Corsi', $Corsi);
        
        parent::display($tpl);
    }

}