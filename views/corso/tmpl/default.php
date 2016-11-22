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
$idm = JRequest::getInt('idm', 0);
?>
<script>
    jQuery(function() {
        
        //se presente l'id del modulo in url lo recupero e lo espando altrimeti espando il primo 
        var idm = <?php echo $idm; ?>;
        
        if(idm){
            jQuery("#modulo"+idm).css("display","block");
        }
        else
        {
            jQuery("#"+jQuery(".modulo:first").attr('id')).css("display","block");
        }
        
        
        
        jQuery(".titolo_modulo").click(function(){
            
            id=(jQuery(this).attr('id'));
            jQuery("#modulo"+id).toggle();
            
        });
        
        jQuery(".contenuto_1, .contenuto_2,  .riepilogo_1,  .riepilogo_2 ").click(function(){
            var id = jQuery(this).attr('id');
            window.location="index.php?option=com_gglms&view=elemento&id="+id;
        });
        
        jQuery(".attestato_1, .attestato_2").click(function(){
            //controllo se l'utente può scaricarsi l'attestato
            if(<?php echo $this->corso['print_type']; ?>){
                var id = jQuery(this).attr('id');
                window.location="index.php?option=com_gglms&task=attestato&id="+id;
            }
            else {
                alert('L\'attestato relativo a questo corso ti verrà consegnato direttamente dalla tua azienda');
            }            
        });
        
        jQuery(".quiz_1, .quiz_2").click(function(){
            var id = jQuery(this).attr('path');
            window.location="index.php?option=com_joomlaquiz&quiz_id="+id;
        });
        
        jQuery(".exam_1, .exam_2").click(function() {
            var id = jQuery(this).attr('path');
            jQuery.ajax({
                url:"index.php?option=com_couponmanager&task=final_test&user_id=<?php echo $this->id_utente; ?>&quiz_id="+id,
                success: function() {
                    window.location="index.php?option=com_joomlaquiz&quiz_id="+id;
                }
            });
        });
        
        jQuery(".contenuto_0, .quiz_0, .attestato_0, .riepilogo_0").click(function(){
            alert('Non puoi ancora aprire questo elemento fino a che non hai superato quelli a lui propedeutici.');
        });
        
        
    });
</script>





<?php
echo '<div class="corso">';

if ($this->corso['titolo_visibile']) {
    echo '<div class="titolo_corso">' . $this->corso['corso'] . '</div>';
}

if ($this->is_trial) {
    echo '<p>    <h3>' . JText::_('COM_GGLMS_CORSO_STR1') . '</h3>';
    echo '<a href="index.php?option=com_gglms&task=openelement&id=' . $this->corso['id_corso'] . '&version=' . $this->corso['id'] . '">' . JText::_('COM_GGLMS_CORSO_STR2') . '</a>: ' . JText::_('COM_GGLMS_CORSO_STR3') . '<br />';
    echo '<a href="index.php?option=com_gglms&task=closeelement&id=' . $this->corso['id_corso'] . '&version=' . $this->corso['id'] . '">' . JText::_('COM_GGLMS_CORSO_STR4') . '</a>: ' . JText::_('COM_GGLMS_CORSO_STR5');
}
?>
<div style="float: right;">
   <?php echo JText::_('COM_GGLMS_CORSO_STR6'); ?>:&nbsp;<div style="display: inline" class="switchviewmode" rel="html5"><img src="components/com_gglms/img/html5_icon.png" /></div>
    <div style="display: inline" class="switchviewmode" rel="flash"><img src="components/com_gglms/img/flash_icon.png" /></div>
</div>
<div style="clear:both;"></div>

<script type="text/javascript">
    jQuery('.switchviewmode').click(function(e) {
        e.preventDefault;
        var tpl = jQuery(this).attr('rel');
        jQuery.ajax({
            url:"index.php?option=com_gglms&task=switchviewmode&tpl="+tpl
        }).done(function() { 
            alert('Ora i contenuti verranno visualizzati in '+tpl);
        });
    });
</script>
<?php
foreach ($this->corso['moduli'] as $m) {


    if ($m['path_immagine']) {
        echo'<div class="img_modulo"><img src="' . $m['path_immagine'] . '" width="80" /></div>';
    }

    if ($m['titolo_visibile']) {
        echo '<div class="titolo_modulo" id="' . $m['id'] . '">' . $m['modulo'] . ' </div>';
    }

    echo'<div class="modulo" id="modulo' . $m['id'] . '">';

    // VISUALIZZO GLI ELEMENTI
    foreach ($m['elementi'] as $e) {

        //In base alla tipologia di elemento stampo una riga diversa.
        //la classe dell'elemento viene definita nel models corso.php-> getElementi, e imposta l'aspetto del contenuto visitato/non visitato/bloccato
        //il parametro path aggiunto al div serve in caso di contenuto-quiz per indicare l'id del quiz.

        echo '
                <div class="' . $e['classe'] . '" id="' . $e['id'] . '" path="' . $e['path'] . '"> 
                    <div class="stato"><img src="' . $e['stato'] . '" height="20"/> </div>
                    <div class="titolo" style="font-weight:900"> ' . $e['elemento'] . '</div>
                </div>';
    }
    echo '</div>'; // chiudo il DIV modulo
}
echo '</div>'; // chiudo il DIV corso
