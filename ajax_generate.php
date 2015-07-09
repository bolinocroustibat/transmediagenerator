<?php

	header('Content-Type: text/html; charset=utf-8'); 
	
	
	$poll_option = $_POST['poll_option'];
	
	
	/* RECHERCHE DANS UNE CHAINE UNICODE */
	function substr_unicode($str, $s, $l = null) {
		return join("", array_slice(
			preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}
	
	/* FONCTION D'ACCORD DES ADJECTIFS ET DE L'ADVERBE */
	function accord($word) {
		if (substr($word,-2) == 'un') {
			$word = substr_replace($word, 'une',-2);
		}
		elseif (substr($word,-2) == 'if') {
			$word = substr_replace($word, 'ive',-2);
		}
		elseif (substr($word,-1) == 'é') {
			$word = substr_replace($word, 'ée',-1);
		}
		elseif (substr($word,-3) == 'eux') {
			$word = substr_replace($word, 'euse',-3);
		}
		elseif (substr($word,-2) == 'nd') {
			$word = substr_replace($word, 'nde',-2);
		}
		elseif (substr_unicode($word,-2) == 'al') {
			$word = substr_replace($word, 'ale',-2);
		}
		elseif (substr_unicode($word,-2) == 'el') {
			$word = substr_replace($word, 'elle',-2);
		}
		elseif (substr_unicode($word,-2) == 'el') {
			$word = substr_replace($word, 'elle',-2);
		}
		elseif (substr_unicode($word,-2) == 'el') {
			$word = substr_replace($word, 'elle',-2);
		}
		elseif (substr_unicode($word,-2) == 'en') {
			$word = substr_replace($word, 'enne',-2);
		}
		elseif (substr_unicode($word,-3) == 'ant') {
			$word = substr_replace($word, 'ante',-3);
		}
		$pos = strpos($word,' mis ');
		if(!empty($pos)) {
			$word = substr_replace($word,' mise ',$pos,5); // Fait le remplacement	de "mis"
		}
		$pos = strpos($word,'é ');
		if(!empty($pos)) {
			$word = substr_replace($word,'ée ',$pos,2); // Fait le remplacement	de "--é"
		}
		return $word;
	}
	
	
	function csv_column_to_array($column) {
		static $table;
		$tmpdata = dirname(__FILE__)."/tmp/data.txt";
		$lasttime = file_exists($tmpdata) ? filemtime($tmpdata) : 0;
		
		if (isset($_GET['force'])) { /* Et si on veut un cache qui est rafraîchi au bout d'un certain temps : ($lasttime < time() - 600 || isset($_GET['force'])) */
			$file = fopen('https://docs.google.com/spreadsheet/pub?key=11K-GO7TLPKKo2VRfOjshr4Qme_SsJVhkuOPiW2b_GpM&output=csv', 'r');
			$table = array();
			while($row = fgetcsv($file)) {
				// $row = array_map( "utf8_decode", $row ); // à chacune des entrées la fonction utf8_decode est appliquée
				$table[] = $row;
			}
			fclose($file);
			
			file_put_contents($tmpdata, json_encode($table) );
		}			
		if ( !isset($table) ) {
			$table = json_decode(file_get_contents($tmpdata) );
		}
		$data = array();
		foreach( $table as $line => $row) {
			if ($row[$column] !='') {
				$data[] = $row[$column];
			}
		}
		array_splice($data,0,3);
			

		return $data;
	}
	
	$sentence = '';
	
	/* CHOIX DU NOM ET DETERMINATION DU GENRE */
	$data = csv_column_to_array(3);
	$random_row = rand (0,count($data)-1);
	$nom = $data[$random_row]; 
	/* puis on va chercher le genre qui correspond */
	$data = csv_column_to_array(2);
	$genre = $data[$random_row];
	
	/* test
	echo 'GENRE : '.$genre.'<br/><br/>';
	*/
	

	/* INTRO
	$data = csv_column_to_array(0);
	$random_row = rand (0,count($data)-1);
	$intro = $data[$random_row];
	if ($genre == 'f') {
		$intro = accord($intro);
	}
	$sentence = $intro;
	*/
	
	/* qualifiant facultatif */
	if (rand(0,1)==1){
		$data = csv_column_to_array(1);
		$random_row = rand (0,count($data)-1);
		$qualifiant = $data[$random_row];
		if ($genre == 'f') {
			$qualifiant = accord($qualifiant);	
		}
		$sentence = $sentence.' '.$qualifiant;	
	}
	
	$sentence = $nom;
	
	/* CHOIX DE L'ADJECTIF 1 */
	$data = csv_column_to_array(4);
	$random_row = rand (0,count($data)-1);
	$adjectif1 = $data[$random_row];
	if ($genre == 'f') {
		$adjectif1 = accord($adjectif1);	
	}
	$sentence = $sentence.' '.$adjectif1;
	
	/* CHOIX EVENTUEL DE L'ADJECTIF 2 (DIFFERENT) */
	if ($poll_option == 3){
		$data = csv_column_to_array(5);
		$random_row2 = $random_row;	
		while ($random_row2==$random_row) { /* pour qu'il soit différent */
			$random_row2 = rand (0,count($data)-1);	
		}
		$adjectif2 = $data[$random_row2];
		if ($genre == 'f') {
			$adjectif2 = accord($adjectif2);	
		}
		$sentence = $sentence.' '.$adjectif2;
	}

	/* ADVERBE */
	$data = csv_column_to_array(6);
	$random_row = rand (0,count($data)-1);
	$adverbe = $data[$random_row];
	if ($genre == 'f') {
		$adverbe = accord($adverbe);	
	}
	$sentence = $sentence.' '.$adverbe;

	/* COMPLEMENT VERBEUX */
	if (rand(0,4) == 4) {
		/* intransitif */
		$data = csv_column_to_array(9);
		$random_row = rand (0,count($data)-1);
		$verbe_intransitif = $data[$random_row];
		$sentence = $sentence.' '.$verbe_intransitif;			
	}
	else {
		/* transifif */
		$data = csv_column_to_array(7);
		$random_row = rand (0,count($data)-1);
		$verbe_transitif = $data[$random_row];
		$sentence = $sentence.' '.$verbe_transitif;
		/* cod */
		$data = csv_column_to_array(8);
		$random_row = rand (0,count($data)-1);
		$cod = $data[$random_row];
		$sentence = $sentence.' '.$cod;		
	}
	
	/* COMPLEMENT VRAC */
	if ($poll_option != 1) {
		$data = csv_column_to_array(10);
		$random_row = rand (0,count($data)-1);
		$vrac = $data[$random_row];
		$sentence = $sentence.' '.$vrac;
	}
	
	/*  FINAL */
	if ($genre == 'f') {
		$sentence = 'Une '.$sentence;
	}
	else {
		$sentence = 'Un '.$sentence;
	}
	$pos = strpos($sentence,' , ');
	if(!empty($pos)) {
		$sentence = substr_replace($sentence,', ',$pos,3); // Fait le remplacement	de la virgule
	}
	$pos = strpos($sentence,' de e');
	if(!empty($pos)) {
		$sentence = substr_replace($sentence," d'e",$pos,5);
	}
	$pos = strpos($sentence,' de o');
	if(!empty($pos)) {
		$sentence = substr_replace($sentence," d'o",$pos,5);
	}
	$pos = strpos($sentence,' de le');
	if(!empty($pos)) {
		$sentence = substr_replace($sentence," du",$pos,6);
	}
	$sentence=ucfirst($sentence).".";

	$hash = hash('md5',$sentence); // Génère le hash
	
	$data = json_encode(array(
		'hash' => $hash,
		'sentence' => $sentence
	)); // Crée un JSON avec la phrase et le hash, et l'affiche pour qu'il soit récupéré par Ajax
	
	$inp = file_get_contents('data.json');
	$tempArray = json_decode($inp);
	array_push($tempArray, $data);
	$jsonData = json_encode($tempArray);
	file_put_contents('data.json', $jsonData);
	
	// file_put_contents('data.json',$data,FILE_APPEND | LOCK_EX); // FILE_APPEND pour ajouter à la suite sans écraser ce qu'il y avait avant
	
	echo $data;

?>