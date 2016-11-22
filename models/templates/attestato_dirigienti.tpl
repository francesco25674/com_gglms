{* Smarty HTML 
@param
$data = Array(
	datetest
	titoloattestato
	durata
	cb_nome
	cb_cognome
	cb_datadinascita
	cb_luogodinascita
	cb_provinciadinascita
	cb_societa
	name
	dg
	logo
)
*}
{literal}
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
        font-size: 28pt;
    }
    h2 {
        font-size: 22pt;
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
        text-align:left;
    }
</style>
{/literal}
<div id="container">
    <div id="attestato">
        <div style="text-align: center">
            <img width="200" src="images/loghi/{$data.logo}" align="center" />
        </div>

        <div>
            <h1>Attestato<br />di Frequenza</h1>
            <p class="small">(art. 37 comma 7, D. Lgs. 81/08 e Accordo Conferenza Stato Regioni 21/12/2011)</p>
            <h2>Si attesta che</h2>
            <h2>{$data.cb_cognome|capitalize} {$data.cb_nome|capitalize}</h2>
            <p class="big">nata/o il {$data.cb_datadinascita} a {$data.cb_luogodinascita|capitalize} {if isset($data.cb_provinciadinascita)}({$data.cb_provinciadinascita}){/if}</p>
            <p class="big">ha frequentato in modalit&agrave; e-learning</p>
            <p class="big">il corso di formazione in materia di sicurezza sul lavoro per Dirigenti,</p>
            <p class="big">terminato il: {$data.datetest}</p>
        </div>

    </div>
        <div>
            <p class="small" style="text-align:right;">{$data.name}<br />{$data.dg}</p>
        </div>
</div>   
