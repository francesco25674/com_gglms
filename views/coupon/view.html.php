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

class gglmsViewcoupon extends JView {

    function display($tpl = null) {

        global $mainframe;
        $document = & JFactory::getDocument();
        $document->addScript('components/com_gglms/js/jquery-ui.min.js');
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/coupon.css');
//        
//        $model = & $this->getModel();
//        $tmp = $model->abilita_Coupon('98');
//        
//
//        $this->assignRef('tmp', $tmp);
        
     
        parent::display($tpl);
    }

}