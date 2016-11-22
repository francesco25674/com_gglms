<?php /* Smarty version Smarty-3.1.5, created on 2016-10-09 13:53:10
         compiled from "components/com_gglms/models/templates/helpdesk_mail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:77047581257fa4bc6049897-56884498%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33764a1380f38211775d998f237e69c8b9e8f6d6' => 
    array (
      0 => 'components/com_gglms/models/templates/helpdesk_mail.tpl',
      1 => 1353942392,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '77047581257fa4bc6049897-56884498',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_57fa4bc61122a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57fa4bc61122a')) {function content_57fa4bc61122a($_smarty_tpl) {?>
<html>
	<head>
	
	</head>
	<body>
		<h1>Richiesta Assistenza</h1>
                
                <h2>Dettali richiesta:</h2>
                    
                        <p> 
                         Nome: <?php echo $_smarty_tpl->tpl_vars['data']->value['fromname'];?>
 <br />
                         Email: <?php echo $_smarty_tpl->tpl_vars['data']->value['frommail'];?>
  <br />
                         Messaggio : <br />
                         <?php echo $_smarty_tpl->tpl_vars['data']->value['testo'];?>
 </p>
                         
                        <hr>
                        <p>
                        ID Utente: <?php echo $_smarty_tpl->tpl_vars['data']->value['idutente'];?>
 <br />
                        Username: <?php echo $_smarty_tpl->tpl_vars['data']->value['username'];?>
 <br />
                        Società: <?php echo $_smarty_tpl->tpl_vars['data']->value['societa'];?>
 <br />

                        </p>
		
	</body>
</html>
<?php }} ?>