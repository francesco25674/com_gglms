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
$document->addScript(JURI::root(true) . '/components/com_gglms/flash/swfobject.js');
$document->addStyleDeclaration("#flash { width: 890px; height: 700px; margin: 0 auto; display: block; }");
debug::vardump($this->elemento);
$config = & JFactory::getConfig();
if (false === strpos($this->elemento['path'], 'http'))
    $path = $config->getValue('config.live_site') . $this->elemento['path'] . "/";
else
    $path = $this->elemento['path'] . "/";

$lingua = "IT"; // è ancora ininfluente dentro il flash


?>


<script type="text/javascript">
    function fnc_alert(param)
    {
        console.log(param);
    }
</script>
<div id="percorso_elemento">
    <a class="percorso_modulo" href="index.php?option=com_gglms&view=corso&id=<?php echo $this->elemento['idcorso']; ?>&idm=<?php echo $this->elemento['idmodulo']; ?>">
        <?php echo $this->elemento['nomecorso']; ?></a>
    -
    <span class="titolo_elemento"> <?php echo $this->elemento['elemento']; ?></span>
</div>

<div id="flash">
    <script>

        var attributes = {};

        var flashvars  = {};
                
        var params = {
            menu: 'false',
            allowFullScreen: 'true',
            path : "<?php echo $path; ?>",
            lingua : "<?php echo $lingua; ?>",
            stato: "<?php echo $this->elemento['track']['stato']; ?>",
            tview: "<?php echo $this->elemento['track']['tview']; ?>",
            id_elemento: "<?php echo $this->elemento['id']; ?>",
            dominio: "<?php echo $config->getValue('config.live_site'); ?>"
            
        };
        
    
        swfobject.embedSWF("components/com_gglms/flash/main.swf", "flash", "890", "700", "9", flashvars, params, attributes);
    </script>

</div>