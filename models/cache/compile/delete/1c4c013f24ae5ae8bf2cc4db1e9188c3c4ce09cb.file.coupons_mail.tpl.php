<?php /* Smarty version Smarty-3.1.5, created on 2015-06-03 16:06:18
         compiled from "components/com_gglms/models/templates/coupons_mail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1414614494556f09da69dec1-25440295%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1c4c013f24ae5ae8bf2cc4db1e9188c3c4ce09cb' => 
    array (
      0 => 'components/com_gglms/models/templates/coupons_mail.tpl',
      1 => 1433340371,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1414614494556f09da69dec1-25440295',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ausind' => 0,
    'coursename' => 0,
    'coupons' => 0,
    'coupon' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_556f09da75183',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556f09da75183')) {function content_556f09da75183($_smarty_tpl) {?>
<html>
    <head>
        <title>Coupon</title>
    </head>
    <body>
        <h1>Registrazione <?php echo $_smarty_tpl->tpl_vars['ausind']->value['associazione_name'];?>
</h1>

        <p>Spett.le <?php echo $_smarty_tpl->tpl_vars['ausind']->value['ragione_sociale'];?>
,</p>
        <p>
            la procedura di generazione coupon &egrave; andata buon fine; inoltre &egrave; stato creato un account aziendale sul portale <?php echo $_smarty_tpl->tpl_vars['ausind']->value['associazione_name'];?>
 
            con privilegi speciali, che permette di monitorare i Vosti coupon e prelevare gli attestati dei partecipanti.        
        </p>
        <p>
            Utilizzi queste credenziali per accedere al portale <a href="<?php echo $_smarty_tpl->tpl_vars['ausind']->value['associazione_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['ausind']->value['associazione_name'];?>
</a>:
            <br />
            username: <span style="font-family: monospace;"><?php echo $_smarty_tpl->tpl_vars['ausind']->value['username'];?>
</span><br />
            <?php if (isset($_smarty_tpl->tpl_vars['ausind']->value['password'])){?>password: <span style="font-family: monospace;"><?php echo $_smarty_tpl->tpl_vars['ausind']->value['password'];?>
</span><br /><?php }?>
            Le ricordiamo che saranno valide anche per gli eventuali futuri acquisti di corsi e-learning, custodisca quindi con cura questa mail.
        </p>
            Ecco <?php if ($_smarty_tpl->tpl_vars['ausind']->value['coupon_number']>1){?>i<?php }else{ ?>il<?php }?> <?php echo $_smarty_tpl->tpl_vars['ausind']->value['coupon_number'];?>
 coupon da Lei richiesti. I coupon non saranno attivi fino al momento della conferma di avvenuto pagamento.
        </p>

        <h3><?php echo $_smarty_tpl->tpl_vars['coursename']->value;?>
</h3>

        <div style="font-family: monospace;">
		  <?php  $_smarty_tpl->tpl_vars['coupon'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['coupon']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['coupons']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['coupon']->key => $_smarty_tpl->tpl_vars['coupon']->value){
$_smarty_tpl->tpl_vars['coupon']->_loop = true;
?>
            <?php echo $_smarty_tpl->tpl_vars['coupon']->value;?>
<br />
        <?php } ?>
        </div>

        <p>
	        <b>Per una migliore fruizione del corso consigliamo fortemente di usare browser quali Firefox (versione 4 o superiore), Google Chrome (versione 6 o superiore), Explorer (dalla versione 9)</b>
        <p>
        
        <p>
            Cordiali saluti<br />
            Lo staff <?php echo $_smarty_tpl->tpl_vars['ausind']->value['associazione_name'];?>

        </p>
        <p>Questa mail è generata automaticamente, si prega di non rispondere.</p>

    </body>
</html>
<?php }} ?>