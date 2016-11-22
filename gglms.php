<?php
/**
 * @version		1
 * @package		GG LMS
 * @author 		antonio
 * @author mail	antonio@ggallery.it
 * @link		
 * @copyright	Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */

// asino chi legge
 
// no direct access
defined('_JEXEC') or die('Restricted access');
 
// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require specific controller if requested
if($controller = JRequest::getCmd('controller')) 
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if ( file_exists( $path ) ) {
		require_once( $path );
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'gglmsController' . ucfirst($controller);
$controller = new $classname();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();