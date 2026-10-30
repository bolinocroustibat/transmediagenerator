<?php

header('Content-Type: text/html; charset=utf-8');

$poll_option = 3;
include('ajax_generate_EN.php');
$array_data = json_decode($data,true);
$tweet = $array_data['sentence'];

// require codebird
require_once(__DIR__.'/twitter-codebird/codebird.php');

// clés de l'API Twitter, dans le .env à la racine (non versionné, cf. .env.example)
$env = parse_ini_file(dirname(__DIR__).'/.env', false, INI_SCANNER_RAW);

\Codebird\Codebird::setConsumerKey($env['TWITTER_CONSUMER_KEY'], $env['TWITTER_CONSUMER_SECRET']);
$cb = \Codebird\Codebird::getInstance();
$cb->setToken($env['TWITTER_ACCESS_TOKEN'], $env['TWITTER_ACCESS_TOKEN_SECRET']);
 
$params = array('status' => $tweet);
$reply = $cb->statuses_update($params);

if ($reply){
	echo 'Le tweet suivant a ete poste :<br />"'.$tweet.'"';
} else { // CAREFUL : ERROR RETURN DOES NOT WORK
	echo "Erreur ! Le tweet n'a pas ete poste";
}

?>