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

<h2><?php echo JText::_($this->associazione['welcome']); ?> <?php echo JText::_('COM_GGLMS_HELPDESK_STR6'); ?>:</h2>

<div id="info_associazione">


    <h3><?php echo $this->associazione['name']; ?></h3>

    <?php
    if ($this->associazione['telefono']) {
        ?>
        <h4><?php echo JText::_('COM_GGLMS_HELPDESK_STR7'); ?> <b><?php echo $this->associazione['telefono']; ?></b></h4>
        <?php }
    ?>


    <h4>Email <b><a href="mailto:<?php echo $this->associazione['email_riferimento']; ?>"><?php echo $this->associazione['email_riferimento']; ?></a></b></h4>
    <?php if (!empty($this->associazione['link_ecommerce'])) {
        ?>
        <h4>Link <a href="<?php echo $this->associazione['link_ecommerce']; ?>"><?php echo JText::_('COM_GGLMS_HELPDESK_STR8'); ?></a></h4>
        <?php
    }
    ?>

</div>


<h2><?php echo JText::_('COM_GGLMS_HELPDESK_STR9'); ?></h2>

<div class="box">
    <form method="post" action="index.php?option=com_gglms&view=helpdesk">
<!--        <input type="hidden" name="option" id="option" value="com_gglms"/>
        <input type="hidden" name="view" id="view" value="helpdesk"/>-->
        <input type="hidden" name="idutente" id="idutente" value="<?php echo $this->user['idutente']; ?>"/>
        <input type="hidden" name="username" id="username" value="<?php echo $this->user['username']; ?>"/>
        <input type="hidden" name="name" id="name" value="<?php echo $this->associazione['name']; ?>"/>


        <label>
            <span><?php echo JText::_('COM_GGLMS_HELPDESK_STR14'); ?></span>
            <fieldset>
                <input type="radio" name="tomail" size="53" value="support@ausindfad.it" /> <?php echo JText::_('COM_GGLMS_HELPDESK_STR11'); ?>
                <input type="radio" name="tomail" size="53" value="<?php echo $this->associazione['email_tutor']; ?>" checked="checked" /> <?php echo JText::_('COM_GGLMS_HELPDESK_STR10'); ?> (<?php echo $this->associazione['nomi_tutor']; ?>)<br/>
            </fieldset>
        </label>




        <label>
            <span><?php echo JText::_('COM_GGLMS_HELPDESK_STR15'); ?></span>
            <input type="text" name="fromname" size="53" id="fromname" value="<?php if (isset($this->user['nominativo'])) echo $this->user['nominativo']; ?>"/>
        </label>

        <label>
            <span>Email</span>
            <input type="text" name="frommail" size="53" id="frommail" value="<?php if (isset($this->user['email'])) echo $this->user['email']; ?>"/>
        </label>


        <label>
            <span><?php echo JText::_('COM_GGLMS_HELPDESK_STR12'); ?></span>
            <textarea name="testo" id="testo" cols="40" rows="5"></textarea>
        </label>


        <input type="submit" id="inviarichiesta" name="inviarichiesta" value="<?php echo JText::_('COM_GGLMS_HELPDESK_STR13'); ?>" />
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


