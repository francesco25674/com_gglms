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
?>
<!--<script  type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#invia_tecnico').click(function(){
            jQuery.post('http://www.ausindfad.it/home/index.php?option=com_gglms&task=helpdesksubmit', {
                fromname: '<?php echo $this->user['nominativo']; ?>', 
                frommail: '<?php echo $this->user['email']; ?>', 
                tomail: jQuery('#help_mail_tecnico').val(), 
                testo : jQuery('#testo_tecnico').val(), 
                
                idutente : '<?php echo $this->user['idutente']; ?>',
                username : '<?php echo $this->user['username']; ?>',
                societa : '<?php echo $this->associazione['name']; ?>'
            }, function(data){
                jQuery('.box').hide();
                jQuery('#submitok').show();
            });
        });
        
        jQuery('#invia_didattico').click(function(){
            jQuery.post('http://www.ausindfad.it/home/index.php?option=com_gglms&task=helpdesksubmit', {
                fromname: '<?php echo $this->user['nominativo']; ?>', 
                frommail: '<?php echo $this->user['email']; ?>', 
                tomail: jQuery('#help_mail_didattico').val(), 
                testo : jQuery('#testo_didattico').val(), 
                
                idutente : '<?php echo $this->user['idutente']; ?>',
                username : '<?php echo $this->user['username']; ?>',
                societa : '<?php echo $this->associazione['name']; ?>'
            }, function(data){
                jQuery('.box').hide();
                jQuery('#submitok').show();
            });
        });
    });
</script>-->


<h2>Per acquistare il corso o per qualsiasi informazione rivolegersi a:</h2>

<div id="info_associazione">


    <h3><?php echo $this->associazione['name']; ?></h3>
    <h4>Telefono <b><?php echo $this->associazione['telefono']; ?></b></h4>
    <h4>Email <b><a href="mailto:<?php echo $this->associazione['email_riferimento']; ?>"><?php echo $this->associazione['email_riferimento']; ?></a></b></h4>
    <h4>Link <a href="<?php echo $this->associazione['link_ecommerce']; ?>">catalogo e-commerce</a></h4>


</div>


<h2>Per ricevere assistenza in merito ai corsi utilizza il seguente moduli:</h2>

<div class="box">
    <form method="post" action="index.php?option=com_gglms&view=helpdesk">
<!--        <input type="hidden" name="option" id="option" value="com_gglms"/>
        <input type="hidden" name="view" id="view" value="helpdesk"/>-->
        <input type="hidden" name="idutente" id="idutente" value="<?php echo $this->user['idutente']; ?>"/>
        <input type="hidden" name="username" id="username" value="<?php echo $this->user['username']; ?>"/>
        <input type="hidden" name="name" id="name" value="<?php echo $this->associazione['name']; ?>"/>
        

        <label>
            <span>Tipologia di assitenza</span>
            <select id="tomail" name="tomail">
                <option value="<?php echo $this->associazione['email_tutor']; ?>">Didattica (<?php echo $this->associazione['nomi_tutor']; ?>)</option>
                <option value="support@ausindfad.it">Tecnica</option>
            </select>
        </label>

        <label>
            <span>Nominativo</span>
            <input type="text" name="fromname" id="fromname" value="<?php echo $this->user['nominativo']; ?>"/>
        </label>

        <label>
            <span>Email</span>
            <input type="text" name="frommail" id="frommail" value="<?php echo $this->user['email']; ?>"/>
        </label>


        <label>
            <span>Scrivi qui il testo della tua domanda</span>
            <textarea name="testo" id="testo" cols="40" rows="5"></textarea>
        </label>


        <input type="submit" id="inviarichiesta" name="inviarichiesta" value="Invia Richiesta" />
    </form>



</div>
<!--    <div class="forms">
        <form>
            <h2>Assistenza tecnica</h2>
            <h4>GGallery </h4>
            <input type="hidden" name="help_mail_tecnico" id="help_mail_tecnico" value="support@ausindfad.it"/>

            <label>
                <span>Nominativo</span>
                <input type="text" name="nominativo_tecnico" id="nominativo_tecnico" value="<?php echo $this->user['nominativo']; ?>"/>
            </label>

            <label>
                <span>Email</span>
                <input type="text" name="email_tecnico" id="email_tecnico" value="<?php echo $this->user['email']; ?>"/>
            </label>


            <label>
                <span>Richiedi aiuto in merito a problemi riscontrati</span>
                <textarea name="testo_tecnico" id="testo_tecnico" cols="40" rows="5"></textarea>
            </label>

            <button id="invia_tecnico">Invia richiesta</button>
        </form>
    </div>-->
</div>


<!--<div id="submitok">
    <p><h2>Un turor risponderà alla tua richiesta al più presto.</h2></p>

<p><h3>Grazie! </h3></p>

        </div>-->


