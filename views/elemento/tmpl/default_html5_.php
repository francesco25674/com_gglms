<?php
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();

// qualche trick per i browser
require 'components/com_gglms/models/libs/errors/libs/browser_detection.php';
$browser = browser_detection('browser_name');
$mobile = browser_detection('mobile_test');
switch ($browser) {
    case 'opera':
        $video_params = 'preload="none"';
        break;
    default:
        $video_params = 'preload="auto"';
}
switch ($mobile) {
    case 'android':
        $video_params .= ' autoplay';
        break;
    default:
        $video_params .= '';
}

if ($this->elemento['track']['stato']) {
    ?>
    <script type="text/javascript">
        var player_features = ['backlight', 'playpause','progress','current','duration','tracks','volume','fullscreen'];
    </script>
    <?php
} else {
    ?>
    <script type="text/javascript">
        var player_features = ['backlight', 'playpause','current','duration','tracks','volume','fullscreen'];
    </script>
    <?php
}

$document->addScriptDeclaration('<!--[if lt IE 9]><script src="components/com_gglms/js/html5shiv.js"></script><![endif]-->');
$document->addScript('components/com_gglms/js/mediaelement-and-player.min.js');
$document->addScript('components/com_gglms/js/elemento.js');
$document->addScript('components/com_gglms/js/fullscreen.min.js');
$document->addStyleSheet('components/com_gglms/css/mediaelementplayer.min.css');
$document->addStyleSheet('components/com_gglms/css/elemento.min.css');
$path = $this->elemento['path'] . '/';
?>

<script>
    //Popopolo l'array jumper. Ogni Jumper è formato dal titolo e dai secondi ai quali si attiva.
    var jumper_old=null;
    //var jumper_attuale;
    var jumper = new Array();
    var path_slide = "<?php echo $path . "images/"; ?>";
    var path_pdf = "<?php echo $path . $this->elemento['path_pdf']; ?>";
    var tview = <?php echo (isset($this->elemento['track']['tview']) ? $this->elemento['track']['tview'] : 0); ?>;
    //var durata= 0;
    var stato = <?php echo isset($this->elemento['track']['stato']) ? $this->elemento['track']['stato'] : 0; ?>;
    var id_elemento = <?php echo $this->elemento['id']; ?>;
    var old_tempo;
    var vjs;
    var prova = tview;
<?php
$i = 0;
foreach ($this->jumper as $val) {
    ?>
            jumper[<?php echo $i++; ?>] = {
                'tstart': <?php echo $val['tstart']; ?>,
                'titolo': "<?php echo $val['titolo']; ?>"
            }
    <?php
}
?>
</script>

<div id="article">
    <div id="percorso_elemento">
        Torna a <a class="percorso_modulo" href="index.php?option=com_gglms&view=corso&id=<?php echo $this->elemento['idcorso']; ?>&idm=<?php echo $this->elemento['idmodulo']; ?>">
            <?php echo $this->elemento['titoloattestato']; ?></a>
        -
        <span class="titolo_elemento"> <?php echo $this->elemento['elemento']; ?></span>
    </div>

    <div id="video-box" class="video-js-box">
        <video id="player_video" width="100%" height="280" poster="<?php echo $path . "video.jpg"; ?>" controls="controls" <?php echo $video_params; ?>>
            <!-- MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7 -->
            <source type="video/mp4" src="<?php echo $path . "video.mp4"; ?>" />
            <!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
            <source type="video/webm" src="<?php echo $path . "video.webm"; ?>" />
            <!-- Ogg/Vorbis for older Firefox and Opera versions -->
            <source type="video/ogg" src="<?php echo $path . "video.ogv"; ?>" />
            <!-- Optional: Add subtitles for each language -->
            <!--<track kind="subtitles" src="subtitles.srt" srclang="en" /> -->
            <!-- Optional: Add chapters -->
            <!--<track kind="chapters" src="chapters.srt" srclang="en" /> -->
            <!-- Flash fallback for non-HTML5 browsers without JavaScript -->
            <object width="490" height="280" type="application/x-shockwave-flash" data="components/com_gglms/js/flashmediaelement.swf">
                <param name="movie" value="components/com_gglms/js/flashmediaelement.swf" />
                <param name="flashvars" value="controls=true&file=<?php echo $path . "video.mp4"; ?>" />
                <!-- Image as a last resort -->
                <img src="<?php echo $path . "video.jpg"; ?>" width="100%" height="100%" title="<?php echo JText::_('COM_GGLMS_ELEMENTO_STR5'); ?>" />
            </object>
        </video>
    </div>
    <div id="slide">
        <div id="slide_container">
            <img id="slide_src" src="<?php echo $path; ?>images/large/Slide1.jpg" />
        </div>
    </div>

    <div id ="box_jumper">
        <?php
        $i = 0;
        foreach ($this->jumper as $var) {
            $_titolo = $var['titolo'];
            $_tstart = $var['tstart'];

            //Genero il minutaggio del Jumper
            $m = floor(($_tstart % 3600) / 60);
            $s = ($_tstart % 3600) % 60;
            $_durata = sprintf('%02d:%02d', $m, $s);

            //DIV ID del jumper che serve poi impostare il colore di background
            $_jumper_div_id = $i;

            //Anteprima Jumper
            $_id_contenuto = JRequest::getInt('id', 0);

            $_img_contenuto = $path . "images/normal/Slide" . ($i + 1) . ".jpg";
            $_background = "background-image: url('" . $_img_contenuto . "'); background-size: 60px 50px; background-position: center;  width: 60px; height: 50px;";


            //Se è la prima volta che viene aperto il video i jumper non sono cliccabili
            $class = ($this->elemento['track']['stato']) ? 'enabled' : 'disabled';
            echo '
            <div class="jumper ' . $class . '" id="' . $_jumper_div_id . '" rel="' . $_tstart . '">
                <div class="anteprima_jumper" style="' . $_background . '"></div>
            ' . $_durata . "</br>" . $_titolo . '
            </div>';
            $i++;
        }
        ?>
        <script type="text/javascript">
            var hasPlayed = false;
            vjs = new MediaElementPlayer('#player_video', {
                // shows debug errors on screen
                enablePluginDebug: false,
                // turns keyboard support on and off for this instance
                enableKeyboard: false,
                // remove or reorder to change plugin priority
                plugins: ['flash', 'silverlight'],
                // specify to force MediaElement to use a particular video or audio type
                type: '',
                // path to Flash and Silverlight plugins
                pluginPath: 'components/com_gglms/js/',
                // name of flash file
                flashName: 'flashmediaelement.swf',
                // name of silverlight file
                silverlightName: 'silverlightmediaelement.xap',
                // default if the <video width> is not specified
                defaultVideoWidth: 490,
                // default if the <video height> is not specified    
                defaultVideoHeight: 280,
                // overrides <video width>
                pluginWidth: -1,
                // overrides <video height>      
                pluginHeight: -1,
                // rate in milliseconds for Flash and Silverlight to fire the timeupdate event
                // larger number is less accurate, but less strain on plugin->JavaScript bridge
                timerRate: 250,
                features:  player_features,
                alwaysShowControls: false,
                // force iPad's native controls
                iPadUseNativeControls: false,
                // force iPhone's native controls
                iPhoneUseNativeControls: false,
                // force Android's native controls
                AndroidUseNativeControls: true,
                // method that fires when the Flash or Silverlight object is ready
                success: function (mediaElement, domObject) {
                    mediaElement.play();
                    // add event listener
                    mediaElement.addEventListener('timeupdate', function(e) {
                        time = mediaElement.currentTime.toFixed(0);
                        sliding(time);
                    }, false);
            
                    old_tempo = null;
                    if(stato) {
                        sliding(0);
                    } else {
                        mediaElement.addEventListener('loadedmetadata', function(e){
                            if (!hasPlayed){
                                mediaElement.setCurrentTime(tview);
                                hasPlayed = true;
                            }
                        });
                        mediaElement.addEventListener('ended', function(e) {
                            stato = 1;
                            jQuery.get("index.php?option=com_gglms&task=updateTrack", {
                                secondi:mediaElement.duration, 
                                stato:1, 
                                id_elemento:id_elemento
                            });
                        }, false);
                    }
            
                    mediaElement.setVideoSize('100%','100%');
                   
                },
                // fires when a player_featuresproblem is detected
                error: function () {
                    console.log('Errore della Madonna Incoronata Vergine');
                }
            });
            vjs.setPlayerSize('100%', '100%');
    
            jQuery('.jumper').click(function() {
                var rel = parseInt(jQuery(this).attr('rel'));
                if (stato || prova>=rel ) {
                    vjs.setCurrentTime(jQuery(this).attr('rel'));
                    sliding(jQuery(this).attr('rel'));
                } else {
                    alert("Solo dopo aver visionato tutto il video potrai utilizzare questio jumper");
                }
            });

            jQuery.ajaxSetup({
                cache: false
            });
        </script>
    </div>

    <!-- ICONE - PULSANTI-->
    <div class="buttons">
        <button id="video-fullscreen" name="video-fullscreen" title="<?php echo JText::_('COM_GGLMS_ELEMENTO_STR6'); ?>" class="tooltip-button"></button>
        <button id="video-slide-fullscreen" name="video-slide-fullscreen" title="<?php echo JText::_('COM_GGLMS_ELEMENTO_STR7'); ?>" class="tooltip-button"></button>
        <button id="slide-fullscreen" name="slide-fullscreen" title="<?php echo JText::_('COM_GGLMS_ELEMENTO_STR8'); ?>" class="tooltip-button"></button>

        <?php
        if ($this->elemento['path_pdf'] <> '')
            echo '<button id="file-download" name="file-download" title="' . JText::_('COM_GGLMS_ELEMENTO_STR1') . '" class="tooltip-button"></button>';
        else
            echo '<button id="file-download-off" title="' . JText::_('COM_GGLMS_ELEMENTO_STR2') . '" class="tooltip-button"></button>';
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('#video-slide-fullscreen').click(function(e) {
                    e.preventDefault();
                    video_slide_fullscreen();
                });
                jQuery('#slide-fullscreen').click(function(e) {
                    e.preventDefault();
                    slide_fullscreen();
                });
                
                jQuery('#file-download').click(function() {
                    //TODO sistemare il link per far scaricare il file e non aprirlo nella stessa finestra
                    window.location= path_pdf;
                });
                jQuery('#file-download-off').click(function() {
                    alert('Nessun materiale è associato a questa lezione.');
                });
                if (jQuery.browser.msie && parseInt(jQuery.browser.version)<9) {
                    // il tuo browser fa schifo
                    // disabilitiamo il fullscreen per un noto bug di IE<9
                    jQuery('#video-fullscreen').click(function(e) {
                        e.preventDefault();
                        alert('<?php echo JText::_('COM_GGLMS_ELEMENTO_STR3'); ?>');
                    });
                } else {
                    jQuery('#legacy_browser').hide();
                    jQuery('#video-fullscreen').click(function(e) {
                        e.preventDefault();
                        video_fullscreen();
                    });
                }
            });
        </script>
    </div>

    <div id="logarea">
        <p id="legacy_browser"><?php echo JText::_('COM_GGLMS_ELEMENTO_STR4'); ?> <a href="http://www.mozilla.org/en-US/firefox/new/">Mozilla Firefox</a>, <a href="https://www.google.com/chrome">Google Chrome</a>,
            <a href="http://www.opera.com/download/">Opera</a>, <a href="http://www.apple.com/safari/">Safari</a>.
        </p>
        <p><a href="<?php echo $_SERVER['REQUEST_URI']; ?>&tpl=flash" title="<?php echo JText::_('COM_GGLMS_ELEMENTO_STR9'); ?>" >
                <img src="components/com_gglms/img/flash_icon.png" alt="flash" align="left" width="24" />
            </a>&nbsp;<?php echo JText::_('COM_GGLMS_ELEMENTO_STR9'); ?>
        </p>
    </div>
</div>
