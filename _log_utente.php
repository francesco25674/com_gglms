<?php
$xml_gen_request = '<richiesta>' .
    '<username>07050810154</username>'.
    '<password>ggallery</password>' .
    '<coupon>GGa53b7k9d873ke1b2edfk358b34b3e5555</coupon>'.
    '</richiesta>';



$xml_gen_request_str =  rawurlencode(base64_encode($xml_gen_request));
$fopen = "http://localhost/www.ausindfad.it/home/index.php?option=com_gglms&task=log_utente&data=".$xml_gen_request_str;
$handle = fopen($fopen, "r");
$result = stream_get_contents($handle);
fclose($handle);

//----------------------------

$domanda = formatXml($xml_gen_request);
$risposta = formatXml($result);

echo "<div style='float: left; width: auto'><pre><h2>domanda</h2><h3><a href=".$fopen.">link</a></h3>". htmlentities((print_r($domanda,true))) ."</pre></div>";
echo "<div style='float: left; width: auto; border-left: dotted; margin-left: 10px;padding-left: 10px'><pre><h2>risposta</h2>". htmlentities((print_r($risposta,true))) ."</pre></div>";

function formatXml($string){
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = TRUE;
    $dom->loadXML($string);
    $dom->formatOutput = TRUE;
    $string = $dom->saveXml();
    return $string;
}