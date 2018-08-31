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
$themedayOutertext = $elems->find('center', 0)->outertext;
$themedayWTags = strip_tags($themedayOutertext);
$themeday = str_replace("...", ": ", $themedayWTags);
echo $themeday."\n";
