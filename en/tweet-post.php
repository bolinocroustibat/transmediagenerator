<?php

header('Content-Type: text/html; charset=utf-8');

$poll_option = 3;
include('ajax_generate_EN.php');
$array_data = json_decode($data,true);
$tweet = $array_data['sentence'];

// require codebird
require_once('./twitter-codebird/codebird.php');

\Codebird\Codebird::setConsumerKey("***REMOVED***", "***REMOVED***");
$cb = \Codebird\Codebird::getInstance();
$cb->setToken("***REMOVED***", "***REMOVED***");
 
$params = array('status' => $tweet);
$reply = $cb->statuses_update($params);

if ($reply){
	echo 'Le tweet suivant a ete poste :<br />"'.$tweet.'"';
} else { // CAREFUL : ERROR RETURN DOES NOT WORK
	echo "Erreur ! Le tweet n'a pas ete poste";
}

?>