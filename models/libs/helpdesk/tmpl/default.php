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
//echo $this->loadTemplate('old');
?>
<script type="text/javascript">
    jQuery(function() {
        jQuery('#vai')
        .click(function(){
            var id= jQuery('#associato').val();
            window.location = 'index.php?option=com_gglms&task=helpdesk&id='+id;
        });
    });
</script>




<div id="scelta_associato">

    <p>
    <h3>Seleziona l'ente a cui sei associato: </h3>


    <select id="associato">

        <?php
        foreach ($this->associati as $a) {
            echo '<option value="' . $a['group_id'] . '">' . $a['name'] . '</option>';
        }
        ?>
        <option value="13">Nessuna associazione</option>

    </select>
    <button id="vai">Vai</button>
</div>