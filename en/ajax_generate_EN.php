<?php

	// header("Content-Type: application/json;");
	header("charset=UTF-8"); 
	
	$poll_option = $_POST['poll_option'];
	
	/* RECHERCHE DANS UNE CHAINE UNICODE */
	function substr_unicode($str, $s, $l = null) {
		return join("", array_slice(
			preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}

	/* REMPLACEMENT DANS UNE CHAINE UNICODE */
    function utf8_substr_replace($original, $replacement, $position, $length) {
		$startString = mb_substr($original, 0, $position, "UTF-8");
		$endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");
		$out = $startString . $replacement . $endString;
		return $out;
    }
	
	function csv_column_to_array($column) {
		static $temp_table;
		$cachefile = dirname(__FILE__)."/tmp/csv_cache_EN.txt";
		// $lasttime = file_exists($cachefile) ? filemtime($cachefile) : 0;
		if (isset($_GET['force'])) { /* Pour forcer le rafraîchissement du cache. Nota : et si on veut un cache qui est rafraîchi automatiquement au bout d'un certain temps : ($lasttime < time() - 600 || isset($_GET['force'])) */
			$csvfile = fopen('https://docs.google.com/spreadsheet/pub?key=18yKGZOpSgwoMhlExZZVT9AfsEXm4s_vqG93t821vm2o&output=csv', 'r');
			$temp_table = array();
			while($row = fgetcsv($csvfile)) {
				// $row = array_map( "utf8_encode", $row ); // à chacune des entrées la fonction utf8_encode est appliquée
				$temp_table[] = $row;
			}
			fclose($csvfile);
			file_put_contents($cachefile, json_encode($temp_table) ); // met dans le fichier de cachefile les données récupérées
		}
		if ( !isset($temp_table) ) {
			$temp_table = json_decode(file_get_contents($cachefile) );
		}
		$data = array();
		foreach( $temp_table as $line => $row) { // met dans le bon ordre
			if ($row[$column] !='') { // enlève les cellules vides
				$data[] = $row[$column];
			}
		}
		array_splice($data,0,3); // enlève les 4 premières lignes du tableau
		return $data;
	}
	
	$sentence = '';
		
	/* adjectif 1 facultatif ("grand", "ambitieux"...) */
	if (rand(0,1)==1){ // 1 chance sur 2 d'avoir un qualifiant
		$data = csv_column_to_array(1);
		$random_row = rand (0,count($data)-1);
		$qualifiant = $data[$random_row];
		$sentence = $qualifiant;
	}
	
	/* CHOIX DE L'ADJECTIF 2 */
	$data = csv_column_to_array(2);
	$random_row = rand (0,count($data)-1);
	$adjectif2 = $data[$random_row];
	if ($sentence[0] == ''){ $sentence = $adjectif2; }
	else { $sentence = $sentence.' '.$adjectif2; }
	
	/* CHOIX DU NOM */
	$data = csv_column_to_array(3);
	$random_row = rand (0,count($data)-1);
	$name = $data[$random_row];
	$sentence = $sentence.' '.$name;

	/* CONJONCTION DE SUBORDINATION */
	$data = csv_column_to_array(4);
	$random_row = rand (0,count($data)-1);
	$adverbe = $data[$random_row];
	$sentence = $sentence.' '.$adverbe;

	/* COMPLEMENT VERBEUX */
	if ($poll_option == 1){  // intransitif
		$data = csv_column_to_array(11);
		$random_row = rand (0,count($data)-1);
		$verbe_intransitif = $data[$random_row];
		$sentence = $sentence.' '.$verbe_intransitif;			
	}
	else { // transitif
		if (rand(0,2) == 0) { // transitif chose
			$data = csv_column_to_array(5); // verbe transitif chose
			$random_row = rand (0,count($data)-1);
			$verbe_transitif = $data[$random_row];
			$sentence = $sentence.' '.$verbe_transitif;
			/* cod */
			$data = csv_column_to_array(6); // COD chose
			$random_row = rand (0,count($data)-1);
			$cod = $data[$random_row];
			$sentence = $sentence.' '.$cod;			
		}
		elseif (rand(0,2) == 1) { // transitif personne
			$data = csv_column_to_array(7);  // verbe transitif personne
			$random_row = rand (0,count($data)-1);
			$verbe_transitif = $data[$random_row];
			$sentence = $sentence.' '.$verbe_transitif;
			$data = csv_column_to_array(8); // COD personne
			$random_row = rand (0,count($data)-1);
			$cod = $data[$random_row];
			$sentence = $sentence.' '.$cod;			
		}
		else { // transitif endroit
			$data = csv_column_to_array(9);  // verbe transitif endroit
			$random_row = rand (0,count($data)-1);
			$verbe_transitif = $data[$random_row];
			$sentence = $sentence.' '.$verbe_transitif;
			/* cod */
			$data = csv_column_to_array(10); // COD endroit
			$random_row = rand (0,count($data)-1);
			$cod = $data[$random_row];
			$sentence = $sentence.' '.$cod;		
		}	
	}
	
	/* COMPLEMENT VRAC */
	if ($poll_option == 3) {
		$data = csv_column_to_array(12);
		$random_row = rand (0,count($data)-1);
		$vrac = $data[$random_row];
		$sentence = $sentence.' '.$vrac;
	}
	
	/*  LIAISONS (FINAL) */
	if($sentence[0] == 'a' or $sentence[0] == 'e' or $sentence[0] == 'i' or $sentence[0] == 'o' or $sentence[0] == 'O' or ($sentence[0] == 'u' && substr($sentence,0,3)!='uni')) {
		$sentence = 'An '.$sentence;
	}
	else{
		$sentence = 'A '.$sentence;
	}
	$pos1 = strpos($sentence,' , ');
	if(!empty($pos1)) {
		$sentence = substr_replace($sentence,', ',$pos1,3); // Fait le remplacement de la première virgule trouvée
	}
	$pos2 = strpos($sentence,' , ',$pos1+1); // Cherche ensuite à partir de la suite
	if(!empty($pos2)) {
		$sentence = substr_replace($sentence,', ',$pos2,3); // Fait le remplacement de la 2e virgule trouvée
	}
	$sentence=$sentence.'.';
	
	/* GENERATION DU HASH */
	$hash = hash('md5',$sentence);
	
	// Crée un JSON avec la phrase et le hash, et l'affiche pour qu'il soit récupéré par Ajax
	$data = json_encode(array(
		'hash' => $hash,
		'sentence' => $sentence
	));
	// pousse le JSON de ce projet dans le fichier NoSQL JSON
	$inp = file_get_contents('generated_projects_EN.json');
	$tempArray = json_decode($inp);
	array_push($tempArray, $data);
	$jsonData = json_encode($tempArray);
	if(isset($jsonData) && $jsonData!=''){
		file_put_contents('generated_projects_EN.json', $jsonData, LOCK_EX);
	}
	
	// file_put_contents('generated_projects_EN.json',$data,FILE_APPEND | LOCK_EX); // FILE_APPEND pour ajouter à la suite sans écraser ce qu'il y avait avant
	
	echo $data;

?>