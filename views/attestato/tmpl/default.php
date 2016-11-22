<?php
/**
 * @version		1
 * @package		GGlms
 * @author 		antonio
 * @author mail         antonio@ggallery.it
 * @link
 * @copyright	Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<h2>Attestati disponibili per il download</h2>
<ul>
<?php
if ($this->is_company) {
    
    foreach ($this->certifications as $c) {
    ?>
    <li><a style="color: #325482" href="index.php?option=com_gglms&task=attestato&<?php echo 'id='.$this->id.'&c='.$c['c_quiz_id'].'&student='.$c['c_student_id']; ?>"><?php echo $c['cb_cognome'].' '.$c['cb_nome']; ?></a></li>
    <?php
    }
} else {
    foreach ($this->certifications as $c) {
    ?>
    <li><a style="color: #325482" href="index.php?option=com_gglms&task=attestato&<?php echo 'id='.$this->id.'&c='.$c['c_quiz_id']; ?>"><?php echo $c['c_title']; ?></a></li>
    <?php
    }
}
?>
</ul>
