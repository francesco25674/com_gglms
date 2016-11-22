function sliding(tempo){
    if (old_tempo != tempo && typeof(jumper.length) != 'undefined') {
        old_tempo = tempo;
        var currTime = parseInt(tempo);
        var i = 0;
        var past_jumper_selector = new Array();
        while (i<jumper.length && currTime>=parseInt(jumper[i]['tstart'])) {
            past_jumper_selector[i] = '#'+i;
            i++;
        }
        i--; // col ciclo while vado avanti di 1
        
        if (i<jumper.length && i != jumper_old) { // se cambio jumper
            jQuery('#'+jumper_old).css('background-color', '#fff');
            jumper_old = i;
            
            // cambio slide
            var url = path_slide + "large/Slide"+(i+1)+".jpg";
            jQuery('#slide_src').attr('src',url);
            //jQuery('#slide_src').attr('width','370');
            jQuery('#slide').fadeIn();

            if (prova < currTime) {
                prova = currTime;
            }
            
            if (!stato) { // il tracking viene fatto a ogni cambio slide
                var set_stato = (i>=jumper.length-1) ? 1 : 0; // penultimo jumper => stato =1
                jQuery.ajax({
                    url: 'index.php?option=com_gglms&task=updateTrack&secondi='+(currTime>prova?currTime:prova)+'&stato='+set_stato+'&id_elemento='+id_elemento,
                    cache: false,
                    success: function($data) {
                        if ($data==0)
                            alert('Impossibile effettuare il tracking del contenuto. Assicurati le tue impostazioni non blocchino le chiamate al server');
                    },
                    error: function() {
                        alert('Impossibile effettuare il tracking del contenuto. Assicurati le tue impostazioni non blocchino le chiamate al server');
                    }
                });
                jQuery(past_jumper_selector.join(',')).css('background-color', '#fff');
            } else { // cancello eventuali jumper azzurri
                jQuery('.jumper').css('background-color', '#fff');
            }

            // jumper attuale è azzurro
            jQuery('#'+i).css('background-color','#98ACC6');
            
        }
    }
}

// ~@:-]