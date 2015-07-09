<!DOCTYPE html>
<html lang="fr">

<head>

	<meta charset="UTF-8" />

	<title>DEV - Générateur de pitch transmédia</title>
	
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	
	<meta name="Description" content="" />
	<meta name="Generator" content="Notepad++" />
	<meta name="Keywords" content="" />
	
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />

	<link href="style/normalize.css" rel="stylesheet" type="text/css" media="all">
	<link href="style/style.css" rel="stylesheet" type="text/css" media="all">	
	
	<script type="text/javascript" src="tabletop.js"></script>

	
</head>

<body>

<?php

$url='https://docs.google.com/spreadsheets/d/11K-GO7TLPKKo2VRfOjshr4Qme_SsJVhkuOPiW2b_GpM/pubhtml';

// make sure we have a URL
	if (is_null($url)) {
		return array();
	}
// initialize curl
	$curl = curl_init();
// set curl options
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
// get the spreadsheet data using curl
	$google_sheet = curl_exec($curl);
// close the curl connection
	curl_close($curl);
// parse out just the html table
	preg_match('/(<table[^>]+>)(.+)(<\/table>)/', $google_sheet, $matches);
	$data = $matches['0'];
// Convert the HTML into array (by converting into HTML, then JSON, then PHP array
	$cells_xml = new SimpleXMLElement($data);
	$cells_json = json_encode($cells_xml);
	$cells = json_decode($cells_json, TRUE);
// Create the array
	$array = array();
	foreach ($cells['tbody']['tr'] as $row_number=>$row_data) {
		$column_name = 'A';
		foreach ($row_data['td'] as $column_index=>$column) {
			$array[($row_number+1)][$column_name++] = $column;
		}
	}
print_r($array);
	
?>




<!--
<script type="text/javascript">
  window.onload = function() { init() };

  var public_spreadsheet_url = 'https://docs.google.com/spreadsheets/d/11K-GO7TLPKKo2VRfOjshr4Qme_SsJVhkuOPiW2b_GpM/pubhtml';

  function init() {
    Tabletop.init( { key: public_spreadsheet_url,
                     callback: showInfo,
                     simpleSheet: true } )
  }

  function showInfo(data, tabletop) {
    alert("Successfully processed!")
    console.log(data);
  }
</script>
-->



</bodv>

</html>