<?php

/**
 * NOTA per la versione flash occorre impostare correttamente la variabile $live_site
 * 
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

class gglmsViewelemento extends JView {

    function display($tpl = null) {

        $groups = JFactory::getUser()->getAuthorisedGroups();
        $tutor = false;
        foreach ($groups as $key => $value) {
            if ($value == 16)
                $tutor = true;
        }
        debug::vardump($groups, 'gruppi');
        $this->assignRef('tutor', $tutor);
        debug::vardump($tutor, 'tutor');


        //RECUPERO SE ESISTE IL TEMPLATE PREFERITO
        $session = JFactory::getSession();
        $gg_elemento = $session->get('gg_elemento');
        $tpl = JRequest::getVar('tpl');

        if (empty($gg_elemento)) {
            if (isset($tpl) && $tpl == 'flash') {
                $gg_elemento = 'flash';
            } else {
                $gg_elemento = 'html5';
            }
        } else {
            if (!empty($tpl) && ($tpl == 'html5' || $tpl == 'flash')) {
                $gg_elemento = $tpl;
            }
        }
        $session->set('gg_elemento', $gg_elemento);
        //FINE TEMPLATE PREFERITO


        $model = & $this->getModel();
        $elemento = $model->getElemento();



        if ($gg_elemento == "html5") {
            //$path = "contenuti_fad/" . $elemento['path'] . "/cue_points.xml";
            $path = $elemento['path'] . '/cue_points.xml';
            $jumper = $model->getJumperXML($path);
            $this->assignRef('jumper', $jumper);
        }

        $this->assignRef('elemento', $elemento);

        if($elemento['tipologia']=='riepilogo')
        {
            $layout =  'riepilogo';
        }
        else
            $layout = ($gg_elemento == 'flash') ? 'flash' : null;

        parent::display($layout);
    }

}