<?php /* Smarty version Smarty-3.1.5, created on 2016-10-07 14:39:23
         compiled from "components/com_gglms/models/templates/attestato_frequenza.tpl" */ ?>
<?php /*%%SmartyHeaderCode:148332660857f7b39b846b72-49523833%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd36fd2d74e5e960f11325eac8602abf24807e7a6' => 
    array (
      0 => 'components/com_gglms/models/templates/attestato_frequenza.tpl',
      1 => 1475849836,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '148332660857f7b39b846b72-49523833',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'tracklog' => 0,
    'single' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.5',
  'unifunc' => 'content_57f7b39b8bf97',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f7b39b8bf97')) {function content_57f7b39b8bf97($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include 'components/com_gglms/models/libs/smarty/smarty/plugins/modifier.capitalize.php';
if (!is_callable('smarty_modifier_date_format')) include 'components/com_gglms/models/libs/smarty/smarty/plugins/modifier.date_format.php';
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
</div>

<?php if ($_smarty_tpl->tpl_vars['data']->value['stampatracciato']==1){?>

    <br><br><br><br><br>

    <h1>Tracciato attività</h1>

    <br><br>

    <div id="tracklog">
        <table>
            <tr>
                <td><b>Elemento</b></td><td><b>Data</b></td><td><b>Permanenza</b></td>
            </tr>

            <?php  $_smarty_tpl->tpl_vars['single'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['single']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tracklog']->value['course']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['single']->key => $_smarty_tpl->tpl_vars['single']->value){
$_smarty_tpl->tpl_vars['single']->_loop = true;
?>
                <tr>
                    <?php if ($_smarty_tpl->tpl_vars['single']->value['elemento']=='Attestato'||$_smarty_tpl->tpl_vars['single']->value['tipologia']=='riepilogo'){?>
                        <td><?php echo $_smarty_tpl->tpl_vars['single']->value['elemento'];?>
</td> <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['single']->value['data'],"%d/%m/%Y");?>
</td> <td></td>
                    <?php }else{ ?>
                        <td><?php echo $_smarty_tpl->tpl_vars['single']->value['elemento'];?>
</td> <td><?php echo $_smarty_tpl->tpl_vars['single']->value['data'];?>
</td> <td><?php echo $_smarty_tpl->tpl_vars['single']->value['tview'];?>
</td>
                    <?php }?>

                </tr>
            <?php } ?>

            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['tracklog']->value['total']['elemento'];?>
</td> <td><?php echo $_smarty_tpl->tpl_vars['tracklog']->value['total']['data'];?>
</td> <td><?php echo $_smarty_tpl->tpl_vars['tracklog']->value['total']['tview'];?>
</td>
            </tr>
        </table>



    </div>

<?php }?>
<!-- <?php echo var_dump($_smarty_tpl->tpl_vars['data']->value);?>
   -->

<!-- <?php echo var_dump($_smarty_tpl->tpl_vars['tracklog']->value);?>
   -->
<?php }} ?>