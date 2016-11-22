<?php /* Smarty version Smarty-3.1.5, created on 2015-06-03 17:02:51
         compiled from "components/com_gglms/models/templates/attestato_frequenza.tpl" */ ?>
<?php /*%%SmartyHeaderCode:753167566556f171b8ca186-59427404%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd36fd2d74e5e960f11325eac8602abf24807e7a6' => 
    array (
      0 => 'components/com_gglms/models/templates/attestato_frequenza.tpl',
      1 => 1421409319,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '753167566556f171b8ca186-59427404',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_556f171b9ded6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556f171b9ded6')) {function content_556f171b9ded6($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include 'components/com_gglms/models/libs/smarty/smarty/plugins/modifier.capitalize.php';
?>

<style>
    #container {
        text-align:center;
    }
    #attestato {
        margin: 0 auto;
        text-align:center;
    }

    h1, h2 {
        text-align:center;
        color: navy;
        font-family: times;
    }

    h1 {
        font-size: 24pt;
    }
    h2 {
        font-size: 18pt;
    }
    p {
        color: #000;
        font-family: times;
        font-size: 14pt;
        text-align:center;
    }
    p.small {
        font-size: 10pt;
    }
    p.big {
        font-size: 16pt;
        text-align:right;
    }
</style>

<div id="container">
    <div id="attestato">
        <div style="text-align: center">
            <img width="200" src="images/loghi/<?php echo $_smarty_tpl->tpl_vars['data']->value['logo'];?>
" align="center" />
        </div>

        <div>
            <h1>Attestato di Frequenza</h1>
            <p>
                Si attesta che
            </p>
            <h2><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['data']->value['cb_cognome']);?>
 <?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['data']->value['cb_nome']);?>
</h2>
            <p>
                nata/o a <?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['data']->value['cb_luogodinascita']);?>
 <?php if (isset($_smarty_tpl->tpl_vars['data']->value['cb_provinciadinascita'])){?>(<?php echo $_smarty_tpl->tpl_vars['data']->value['cb_provinciadinascita'];?>
)<?php }?><br />
                il <?php echo $_smarty_tpl->tpl_vars['data']->value['cb_datadinascita'];?>

            </p>
            <p>
                ha frequentato in modalit&agrave; e-learning
            </p>
            <p>il corso</p>
            <h2><?php echo $_smarty_tpl->tpl_vars['data']->value['titoloattestato'];?>
</h2>
            <p>della durata di <?php echo $_smarty_tpl->tpl_vars['data']->value['durata'];?>
 ore</p>
            <p class="small">
                ai sensi dell'art.37, comma 7, del D.Lgs. 81/08<br />
                e dell'Accordo Conferenza Stato Regioni del 21 dicembre 2011
            </p>
            <p>
                terminato il <?php echo $_smarty_tpl->tpl_vars['data']->value['datetest'];?>

            </p>
        </div>

    </div>
        <div>
            <p class="small" style="text-align:right;"><?php echo $_smarty_tpl->tpl_vars['data']->value['name'];?>
<br /><?php echo $_smarty_tpl->tpl_vars['data']->value['dg'];?>
</p>
        </div>
</div>   <?php }} ?>