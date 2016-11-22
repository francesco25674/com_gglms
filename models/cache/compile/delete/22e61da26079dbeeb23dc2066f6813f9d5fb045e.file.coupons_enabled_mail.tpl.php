<?php /* Smarty version Smarty-3.1.5, created on 2015-06-04 16:06:56
         compiled from "components/com_gglms/models/templates/coupons_enabled_mail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:205362595055705b8044da73-34428751%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '22e61da26079dbeeb23dc2066f6813f9d5fb045e' => 
    array (
      0 => 'components/com_gglms/models/templates/coupons_enabled_mail.tpl',
      1 => 1369813369,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '205362595055705b8044da73-34428751',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ausind' => 0,
    'ragione_sociale' => 0,
    'username' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_55705b8050f2c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55705b8050f2c')) {function content_55705b8050f2c($_smarty_tpl) {?>
<html>
    <head>
        <title>Coupon</title>
    </head>
    <body>
        <h1>Abilitazione coupon <?php echo $_smarty_tpl->tpl_vars['ausind']->value['associazione_name'];?>
</h1>

        <p>Spett.le <?php echo $_smarty_tpl->tpl_vars['ragione_sociale']->value;?>
,</p>
        <p>
            I coupon da Lei richiesti sono stati attivati.<br />
            Le ricordiamo che è possibile accedere al portale <a href="<?php echo $_smarty_tpl->tpl_vars['ausind']->value['associazione_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['ausind']->value['associazione_name'];?>
</a> con username: <?php echo $_smarty_tpl->tpl_vars['username']->value;?>
</p>
        <br />		
        <b>Per una migliore navigazione consigliamo fortemente di usare browser quali Firefox (versione 4 o superiore), Google Chrome (versione 6 o superiore), Explorer (dalla versione 9)</b>
        <br />
        <p>
            Cordiali saluti<br />
            Lo staff <?php echo $_smarty_tpl->tpl_vars['ausind']->value['associazione_name'];?>

        </p>
        <p>Questa mail è generata automaticamente, si prega di non rispondere.</p>

    </body>
</html>
<?php }} ?>