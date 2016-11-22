<?php
defined('_JEXEC') or die('Restricted access');
?>
<div id="article">
	<div id="percorso_elemento">
		<a class="percorso_modulo"
			href="index.php?option=com_gglms&view=corso&id=<?php echo $this->elemento['idcorso']; ?>&idm=<?php echo $this->elemento['idmodulo']; ?>">
			<?php echo $this->elemento['nomecorso']; ?>
		</a> - <span class="titolo_elemento"> <?php echo $this->elemento['elemento']; ?>
		</span>
	</div>
	<iframe
		src="contenuti_fad/<?php echo $this->elemento['path']; ?>/main.html"
		style="width: 910px;
	height: 720px;"></iframe>
</div>
<script type="text/javascript">
jQuery.get("index.php?option=com_gglms&task=updateTrack", {
	secondi:0,
   stato:1, 
   id_elemento:<?php echo $this->elemento['id']; ?>
});
</script>
<?php 
// ~@:-]
?>
