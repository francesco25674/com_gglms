{* Smarty XHTML *}
<style>
    #container {
        text-align:center;
    }
    #attestato {
        margin: 0 auto;
        text-align:center;
    }
    h1 {
        color: navy;
        font-family: times;
        font-size: 24pt;
        text-align:center;
    }
    p {
        color: #000;
        font-family: times;
        font-size: 14pt;
        text-align:center;
    }
</style>


<div id="container">
    <div id="attestato">
        <div style="text-align: center">
            <img width="100" src="components/com_gglms/models/libs/pdf/imgs/{$data.logo}" align="center" />
        </div>

        <div>

            <h1>Programma nazionale per la formazione continua degli operatori della sanità</h1>
            <p>
                Premesso che la <strong>Commissione Nazionale per la Formazione Continua</strong> ha accreditato provvisoriamente
                il Provider <strong>GGALLERY Srl</strong> accreditamento n.39.<br />

                Premesso che il Provider ha organizzato l'evento formativo n.{$data.codice_ecm}, edizione n. 1 denominato
                <strong>{$data.titoloattestato}</strong> e tenutosi dal <strong>{$data.datainizio}</strong> al <strong>{$data.datafine}</strong>,
                avente come obiettivi didattico/formativo generali: <em>{$data.obbiettivi}</em>, assegnando all'evento stesso 
                N.<strong>{$data.crediti}</strong> ({$data.crediti_testo}) Crediti Formativi E.C.M.
            </p>

            <p>
                Il sottoscritto <strong>PAOLO MACRI'</strong><br />
                Rappresentante Legale dell'organizzatore<br />
                Verificato l'apprendimento del participante
            </p>
            <h1>ATTESTA CHE</h1>
            <p>
                il Dott./la Dott.ssa<br />
                <strong>{$data.cb_cognome} {$data.cb_nome}</strong><br /> 
                in qualità di {$data.cb_professionedisciplina}<br />
                nato a {$data.cb_luogodinascita} {if !empty($data.cb_provinciadinascita)}({$data.cb_provinciadinascita}){/if}<br />
                il {$data.cb_datadinascita}                
            </p>
            <p>
                ha conseguito<br />
                N. {$data.crediti} ({$data.crediti_testo}) Crediti formativi per l'anno {$data.anno}
            </p>
            <p>
                IL RAPPRESENTANTE LEGALE DELL'ORGANIZZATORE<br />
                Dott. Paolo Macrì<br />
                <img width="100" src="components/com_gglms/models/libs/pdf/imgs/firma_paolo.png" align="center" />
            </p>
        </div>
    </div>
</div>   


<!-- {$data|@var_dump}   -->