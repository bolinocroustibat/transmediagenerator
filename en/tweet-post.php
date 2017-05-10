<?php

header('Content-Type: text/html; charset=utf-8');

$poll_option = 3;
include('ajax_generate_EN.php');
$array_data = json_decode($data,true);
$tweet = $array_data['sentence'];

// require codebird
require_once(__DIR__.'/twitter-codebird/codebird.php');

\Codebird\Codebird::setConsumerKey("v5wtfm2twBjj1jEQnJUcCeilx", "YeYebDAWsyQpO31n67QUPgckTVlj86aXyPUTgy7nCZcePcnYb0");
$cb = \Codebird\Codebird::getInstance();
$cb->setToken("3937703314-FgZFAJhR8LepKFrODWUu1S4Djq1xMhBgJRsqqAr", "DxqT0cugfZzMV7MsIw3dqqjjDHjGTxyHrUWwSIKuJ1BbF");
 
$params = array('status' => $tweet);
$reply = $cb->statuses_update($params);

if ($reply){
	echo 'Le tweet suivant a ete poste :<br />"'.$tweet.'"';
} else { // CAREFUL : ERROR RETURN DOES NOT WORK
	echo "Erreur ! Le tweet n'a pas ete poste";
}

?>