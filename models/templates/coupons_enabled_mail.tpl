{* Smarty *}
<html>
    <head>
        <title>Coupon</title>
    </head>
    <body>
        <h1>Abilitazione coupon {$ausind.associazione_name}</h1>

        <p>Spett.le {$ragione_sociale},</p>
        <p>
            I coupon da Lei richiesti sono stati attivati.<br />
            Le ricordiamo che è possibile accedere al portale <a href="{$ausind.associazione_url}">{$ausind.associazione_name}</a> con username: {$username}</p>
        <br />		
        <b>Per una migliore navigazione consigliamo fortemente di usare browser quali Firefox (versione 4 o superiore), Google Chrome (versione 6 o superiore), Explorer (dalla versione 9)</b>
        <br />
        <p>
            Cordiali saluti<br />
            Lo staff {$ausind.associazione_name}
        </p>
        <p>Questa mail è generata automaticamente, si prega di non rispondere.</p>

    </body>
</html>
