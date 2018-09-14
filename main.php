<?php

include 'vendor/autoload.php';
use Sunra\PhpSimple\HtmlDomParser;

$curl = new Curl\Curl();
$themedayUrl = "https://temadagar.se/";

$curl->get($themedayUrl);
if ($curl->error) {
    echo $curl->error_code;
    // TODO: Throw a better error
}

// TODO: Maybe deliver some options, different formats or similar
$html = HtmlDomParser::str_get_html($curl->response);
$elems = $html->find('div[class=content]', 0);
if (!$elems->find('center', 0)) {
    if (isset($argv[1]) == "irc") {
        echo "/topic Idag firar vi : Ingenting\n";
        exit;
    }
    echo "Idag firar vi : Ingenting\n";
    exit;
}
$themedayInnertext = $elems->find('center', 0)->innertext;
$outputArrayed = array_values(array_filter(explode("<br />", $themedayInnertext)));
$output = "";
foreach ($outputArrayed as $i => $row) {
    if ($i == 0) {
        $output .= strip_tags($row);
    } else if ($i == count($outputArrayed) - 1) {
        $output .= strip_tags($row);
    } else {
        $output .= strip_tags($row) ." & ";
    }
}
$themeday = str_replace("...", ": ", $output);
if (isset($argv[1]) == "irc") {
    echo "/topic {$themeday}\n";
    exit;
}
echo $themeday."\n";
