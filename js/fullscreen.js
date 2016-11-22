function video_fullscreen()  {
    vjs.enterFullScreen();
    var ua = jQuery.browser;
    if (!vjs.isFullScreen && ua.msie) {
        createFullScreen([jQuery('#video-box>:first')]);
    }
}

function video_slide_fullscreen() {
    var fswidth = jQuery(window).width()/2;
    var fsheight = jQuery(window).height();
    var video = jQuery('#video-box>:first');
    var iwidth = video.innerWidth();
    var iheight = video.innerHeight();
    
    jQuery('#video-box').addClass('puffoblu');
    // aspect ratio
    var ratio = iheight/iwidth;
    if ((fsheight/fswidth) < ratio){
	    video.innerHeight(fsheight);
	    video.innerWidth(video.innerHeight()/ratio); 
    } else {
	    video.innerWidth(fswidth);
	    video.innerHeight(video.innerWidth()*ratio); 
    }
    video.addClass('fullscreen');
    video.css({
        'margin-left' : -1*(video.innerWidth()/2),
        'margin-top' : -1*(video.innerHeight()/2),
        'left': '50%'
    });
    vjs.setPlayerSize(video.innerWidth(),video.innerHeight());

    var slide = jQuery('#slide>:first');
    var iwidth = slide.innerWidth();
    var iheight = slide.innerHeight();
    jQuery('#slide').addClass('dragoviola');
    // aspect ratio
    var ratio = iheight/iwidth;
    if ((fsheight/fswidth) < ratio){
	    slide.innerHeight(fsheight);
	    slide.innerWidth(slide.innerHeight()/ratio); 
    } else {
	    slide.innerWidth(fswidth);
	    slide.innerHeight(slide.innerWidth()*ratio); 
    }
    slide.addClass('fullscreen');
    slide.css({
        'margin-right' : -1*(slide.innerWidth()/2),
        'margin-top' : -1*(slide.innerHeight()/2)//,
//        'right': '50%'
    });

    var msg = jQuery('<div>', {'id': 'vjs-fullscreen-msg'});
    msg.html('<span>Premere ESC per abbandonare la modalit&agrave; schermo intero</span>');
    jQuery('body').append(msg);
    msg.fadeIn(500).delay(750).fadeOut(1500, function(){jQuery(this).remove()});
    
    jQuery('.dragoviola').click(function(){
        jQuery('#video-box').removeClass('puffoblu');
        vjs.setPlayerSize(490,280);
        var video = jQuery('#video-box>:first');
        video.removeClass('fullscreen');
        video.innerWidth('100%');
        video.innerHeight('100%');
        video.css({
            'margin-left' : 0,
            'margin-top' : 0,
            'left': 0
        });
        jQuery('#slide').removeClass('dragoviola');
        var slide = jQuery('#slide>:first');
        slide.removeClass('fullscreen');
        slide.innerWidth('100%');
        slide.innerHeight('100%');
        slide.css({
            'margin-left' : 0,
            'margin-top' : 0,
            'left': 0
        });

    });
}

function slide_fullscreen() {
    createFullScreen([jQuery('#slide_container')]);
}

jQuery(document).keyup(function(e) {
    if (e.keyCode == 27) { 
       if (vjs.isFullScreen) {
	       vjs.exitFullScreen();
	   } else if (jQuery('.puffoblu').length) {
                jQuery('#video-box').removeClass('puffoblu');
                vjs.setPlayerSize(490,280);
                var video = jQuery('#video-box>:first');
                video.removeClass('fullscreen');
                video.innerWidth('100%');
                video.innerHeight('100%');
                video.css({
                    'margin-left' : 0,
                    'margin-top' : 0,
                    'left': 0
                });
                jQuery('#slide').removeClass('dragoviola');
                var slide = jQuery('#slide>:first');
                slide.removeClass('fullscreen');
                slide.innerWidth('100%');
                slide.innerHeight('100%');
                slide.css({
                    'margin-left' : 0,
                    'margin-top' : 0,
                    'left': 0
                });
            } else {
                e.preventDefault();
                removeFullScreen();
            }
        }
    });

function removeFullScreen() {
    var fs = jQuery('#fs_container');
    if (fs.length) {
        // rimuovo il messaggio 'premere esc per uscire da fullscreen'
        fs.children('#vjs-fullscreen-msg').remove();

        var item;
        fs.children().each(function() {
            item = jQuery(this);
            item.innerWidth('100%');
            item.innerHeight('100%');
            item.css({
                'margin-left' : 0,
                'margin-top' : 0,
                'left': 0
            });
            item.removeClass('fullscreen');
            jQuery(item.attr('rel')).append(item);
            if (item.has('video')) {
                vjs.setPlayerSize(490, 280);
                vjs.play();
            }
        })
        fs.remove();
        //vjs.play();
    }
}

function createFullScreen(items) {
    var aurea = 1;
  // elemento contenitore per il full screen
  var fs = jQuery('<div>', {
      'css' : {
            'overflow-y' : 'auto',
            'background' : '#000',
            'width' : '100%',
            'height' : '100%',
            'position': 'fixed',
            'top': 0,
            'bottom': 0,
            'left': 0,
            'right': 0,
            'z-index': '1000'
        },
        'id': 'fs_container'
    });
    jQuery('body').append(fs);

    // elemento di avviso per uscire dalla modalità full screen con esc
    var msg = jQuery('<div>', {'id': 'vjs-fullscreen-msg'});
    msg.html('<span>Premere ESC per abbandonare la modalit&agrave; schermo intero</span>');
    fs.append(msg);
    
    
    var num = items.length;
    var fswidth = fs.innerWidth()/num; // dimensione massima diviso numero di elementi
    var fsheight = fs.innerHeight();
    var iwidth, iheight, ratio, i=0;
    jQuery.each(items, function(index, item) {
       // salvo il padre originario nell'attributo rel, servirà per removeFullScreen
        if (item.parent().attr('id'))
            item.attr('rel', '#'+item.parent().attr('id'));
        else
            item.attr('rel', '.'+item.parent().attr('class'));
        
        // dimensioni dell'elemento originale
        iwidth = item.innerWidth();
        iheight = item.innerHeight();
        // aspect ratio
        ratio = iheight/iwidth;

        if ((fsheight/fswidth) < ratio){
	        item.innerHeight(aurea*fsheight);
	        item.innerWidth(item.innerHeight()/ratio); 
        } else {
	        item.innerWidth(aurea*fswidth);
	        item.innerHeight(item.innerWidth()*ratio); 
        }

        item.addClass('fullscreen');
        item.css({
            'margin-left' : -1*(item.innerWidth()/2),
            'margin-top' : -1*(item.innerHeight()/2),
            'left': (50/num+50*i)+'%'
        });

        if (item.has('video')) {
            vjs.setPlayerSize(item.innerWidth(), item.innerHeight());
            vjs.play();
        }

        fs.append(item);
        i++;
    });    
    
    fs.children().click(function() {
        removeFullScreen()
    });
    jQuery('#vjs-fullscreen-msg').fadeIn(500).delay(750).fadeOut(1500);
}


// ~@:-]