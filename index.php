﻿<!DOCTYPE html>
<html lang="fr">

<?php		
// RECUPERATION DE LA PHRASE EN CAS DE CHARGEMENT DE LA PAGE AVEC HASH
if(isset($_GET['hash']) && $_GET['hash']!='') { // Si on recoit un hash
	$hash = $_GET['hash'];
	$generated_projects_json = file_get_contents("generated_projects.json"); //charge le fichier qui contient l'objet JSON
	$generated_projects_table = json_decode($generated_projects_json,true); // transforme l'objet JSON en tableau PHP
	foreach ($generated_projects_table as $row_obj) { // parcourt chaque ligne du tableau PHP
		$row = json_decode($row_obj,true); // tranforme l'objet-ligne en tableau
		if ($hash == $row["hash"]){
			$sentence = $row["sentence"];
		}
	}
}
?>

<head>

	<meta charset="UTF-8" />

	<title>DEV - The Amazing Transmedia Project Generator</title>
	
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Codystar' rel='stylesheet' type='text/css'>
	
	<meta name="Description" content="<?php if(isset($sentence) && $sentence!=''){echo $sentence;}else{echo 'Plus aucune chance de voir le financement de son projet refusé, grâce au générateur de projet transmédia !';} ?>" />
	<meta name="Generator" content="Notepad++" />
	<meta name="Keywords" content="transmedia, transmédia, générateur, generator, generateur, crossmedia" />

	<meta property="og:title" content="<?php if(isset($sentence) && $sentence!=''){echo 'J\'ai généré mon projet transmédia :';}else{echo 'The Amazing Transmedia Project Generator';} ?>" />
	<meta property="og:image" content="style/partage-1200x1200.jpg" />
	<meta property="og:url" content="http://www.transmediagenerator.com/<?php if(isset($hash) && $hash!=''){echo $hash;}?>.html" />
	<meta property="og:site_name" content="The Amazing Transmedia Project Generator" />
	<meta property="og:description" content="<?php if(isset($sentence) && $sentence!=''){echo $sentence;}else{echo 'Plus aucune chance de voir le financement de son projet refusé, grâce au générateur de projet transmédia !';} ?>" />
	
	<link rel="apple-touch-icon" sizes="57x57" href="style/favicons/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="style/favicons/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="style/favicons/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="style/favicons/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="style/favicons/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="style/favicons/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="style/favicons/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="style/favicons/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="style/favicons/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="style/favicons/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="style/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="style/favicons/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="style/favicons/favicon-16x16.png">
	<link rel="manifest" href="style/favicons/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<link href="style/normalize.css" rel="stylesheet" type="text/css" media="all">
	<link href="style/style.css" rel="stylesheet" type="text/css" media="all">	
	
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
	<script>

		function generate_data() { // si le bouton de génération a été cliqué
			$("#projet-wrapper").hide("fold", function() {
				$("#projet-wrapper").css("visibility", "visible");
				$.ajax({
						type: "POST",		
						url: 'ajax_generate.php',
						data: $('#poll_form').serialize(),
						success: function (data) {
							var dataObj = jQuery.parseJSON(data);
							sentence = dataObj.sentence;
							hash = dataObj.hash;
							$("#projet").html(sentence);
							history.pushState(sentence, sentence, hash+'.html'); // change l'URL dynamiquement
							if ((typeof sentence !== 'undefined') && (typeof hash !== 'undefined')) { // si les variables existent
								document.getElementById('ShareTwitter').href = 'http://twitter.com/?status='+sentence; // met à jour le lien de partage Twitter
								document.getElementById('ShareFacebook').href = 'http://www.facebook.com/sharer/sharer.php?u=http://www.transmediagenerator.com/'+hash+'.html'; // met à jour le lien de partage Facebook		
							}
						}
				})
			});
			$("#projet-wrapper").show("fold",300);
			$("#share").css("visibility", "visible");
			$("#share").show("fold",300);
		}

		function read_data(sentence) {
			$("#projet-wrapper").css("visibility", "visible");
			$("#share").css("visibility", "visible");
			$("#projet").html(sentence);
		}
	</script>

</head>

<body<?php if(isset($sentence) && $sentence!=''){echo ' onload="read_data(\''.addslashes($sentence).'\')"';} ?>>

	<div id="main-wrapper">

		<!-- <h2>Plus aucune chance de voir le financement de son projet refusé, grâce à...</h2> -->
		<h1>The Amazing Transmedia<br />Project Generator</h1>

		<form id="poll_form" method="post" accept-charset="utf-8">  
			<label><input type="radio" name="poll_option" value="1" id="poll_option" /> Mon projet est encore flou, c'est pour trouver des stagiaires</label><br />
			<label><input type="radio" name="poll_option" value="2" id="poll_option" /> Mon projet doit permettre de fédérer des freelances</label><br />
			<label><input type="radio" name="poll_option" value="3" id="poll_option" checked="checked" /> Mon projet doit être costaud, c'est pour présenter à SXSW</label><br />
		</form>

		<div id="bouton-actualiser"><input type="button" onClick="generate_data()" value="Créer un nouveau projet transmédia !"></div>

		<div id="projet-wrapper" style="visibility:hidden;">“ <span id="projet"></span> ”</div>
		
		<div id="share" style="visibility:hidden;">
			Partager ce projet 
			<a href="http://www.facebook.com/sharer/sharer.php<?php if(isset($hash) && $hash!=''){echo '?u=http://www.transmediagenerator.com/'.$hash.'.html';}?>" id="ShareFacebook">sur Facebook</a> - 
			<a href="http://twitter.com/?status=<?php if(isset($sentence) && $sentence!=''){echo $sentence;}?>" id="ShareTwitter">sur Twitter</a>
		</div>
	
	</div>

</body>

</html>