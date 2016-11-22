<?php
/**
 * @version     1
 * @package     GGlms
 * @author      antonio <antonio@ggallery.it> diego <diego@ggallery.it>
 * @copyright   Copyright (C) 2011 antonio - All rights reserved.
 * @license	 GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$path = $this->elemento['path'] . '/';

$document->addStyleSheet('components/com_gglms/css/elemento.css');
//$path = "contenuti_fad/" . $this->elemento['path'] . "/";
$path = $this->elemento['path'] . '/';
?>




<div id="article">
    <div id="percorso_elemento">
        <a class="percorso_modulo" href="index.php?option=com_gglms&view=corso&id=<?php echo $this->elemento['idcorso']; ?>&idm=<?php echo $this->elemento['idmodulo']; ?>">
            <?php echo $this->elemento['nomecorso']; ?></a>
        -
        <span class="titolo_elemento"> <?php echo $this->elemento['elemento']; ?></span>
    </div>

    <!-- ICONE - PULSANTI-->
    <div class="buttons">

        <h2> Scarica e leggi il documento </h2>
        <?php
        if ($this->elemento['path_pdf'] <> '')
            echo '<button id="file-download" name="file-download" title="' . JText::_('COM_GGLMS_ELEMENTO_STR1') . '" class="tooltip-button"></button>';
        else
            echo '<button id="file-download-off" title="' . JText::_('COM_GGLMS_ELEMENTO_STR2') . '" class="tooltip-button"></button>';
        ?>




        <script type="text/javascript">
            jQuery(document).ready(function(){
                var id_elemento = <?php echo $this->elemento['id']; ?>;
                var path_pdf = "<?php echo $path . $this->elemento['path_pdf']; ?>";
                var stato = <?php echo isset($this->elemento['track']['stato']) ? $this->elemento['track']['stato'] : 0; ?>;
                var durata =  <?php echo $this->elemento['durata']; ?>;

//                if (!stato) { // il tracking viene fatto a ogni cambio slide

                    jQuery.ajax({
                        url: 'index.php?option=com_gglms&task=updateTrack&secondi=' + durata + '&stato=' + 1 + '&id_elemento=' + id_elemento,
                        cache: false,
                        success: function ($data) {
                            if ($data == 0)
                                alert('Impossibile effettuare il tracking del contenuto. Assicurati le tue impostazioni non blocchino le chiamate al server');
                        },
                        error: function () {
                            alert('Impossibile effettuare il tracking del contenuto. Assicurati le tue impostazioni non blocchino le chiamate al server');
                        }
                    });
//                }


                jQuery('#video-slide-fullscreen').click(function(e) {
                    e.preventDefault();
                    video_slide_fullscreen();
                });
                jQuery('#slide-fullscreen').click(function(e) {
                    e.preventDefault();
                    slide_fullscreen();
                });
                
                jQuery('#file-download').click(function() {
                    //TODO sistemare il link per far scaricare il file e non aprirlo nella stessa finestra

                    window.location= path_pdf;
                });

            });
        </script>
    </div>

</div>