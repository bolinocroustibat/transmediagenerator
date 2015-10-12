<!DOCTYPE html>
<html lang="fr">

<?php		
// RECUPERATION DE LA PHRASE EN CAS DE CHARGEMENT DE LA PAGE AVEC HASH
if(isset($_GET['hash']) && $_GET['hash']!='') { // Si on recoit un hash
	$hash = $_GET['hash'];
	$generated_projects_json = file_get_contents("generated_projects.json"); //charge le fichier json
	$generated_projects_table = json_decode($generated_projects_json,true); // transforme en tableau PHP l'objet JSON
	foreach ($generated_projects_table as $row_obj) { // parcourt chaque ligne du tableau PHP
		$row = json_decode($row_obj,true); // tranforme l'objet ligne en tableau
		if ($hash == $row["hash"]){
			$sentence = $row["sentence"];
		}
	}
}
?>

<head>

	<meta charset="UTF-8" />

	<title>The Amazing Transmedia Project Generator</title>
	
	<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	
	<meta name="Description" content="<?php if(isset($sentence) && $sentence!=''){echo $sentence;}else{echo 'Plus aucune chance de voir le financement de son projet refusé, grâce au générateur de projet transmédia !';} ?>" />
	<meta name="Generator" content="Notepad++" />
	<meta name="Keywords" content="transmedia, transmédia, générateur, generator, generateur, crossmedia" />

	<meta property="og:title" content="<?php if(isset($sentence) && $sentence!=''){echo 'J\'ai généré mon projet transmédia :';}else{echo 'The Amazing Transmedia Project Generator';} ?>" />
	<meta property="og:url" content="http://www.transmediagenerator.com/<?php if(isset($hash) && $hash!=''){echo $hash;}?>.html" />
	<meta property="og:site_name" content="The Amazing Transmedia Project Generator" />
	<meta property="og:description" content="<?php if(isset($sentence) && $sentence!=''){echo $sentence;}else{echo 'Plus aucune chance de voir le financement de son projet refusé, grâce au générateur de projet transmédia !';} ?>" />
	
	<!-- <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" /> -->

	<link href="style/normalize.css" rel="stylesheet" type="text/css" media="all">
	<link href="style/style.css" rel="stylesheet" type="text/css" media="all">	
	
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
	<script>

		function generate_data() { // si le bouton de génération a été cliqué
			$("#share").css("visibility", "hidden");
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
						}
				})
			});
			if ((typeof sentence !== 'undefined') && (typeof hash !== 'undefined')) { // si les variables existent
				var ShareURLTwitter = 'http://twitter.com/?status='+sentence; // Crée l'adresse de partage Twitter
				var ShareURLFacebook = 'http://www.facebook.com/sharer/sharer.php?u=http://www.transmediagenerator.com/'+hash+'.html';  // Crée l'adresse de partage Facebook
				document.getElementById('ShareTwitter').href = ShareURLTwitter; // met à jour le lien de partage Twitter
				document.getElementById('ShareFacebook').href = ShareURLFacebook; // met à jour le lien de partage Facebook		
			}
			$("#projet-wrapper").show("fold",300);
			$("#share").css("visibility", "visible");
		}

		function read_data(sentence) {
			// console.log(sentence);
			$("#projet-wrapper").css("visibility", "visible");
			$("#share").css("visibility", "visible");
			$("#projet").html(sentence);
		}
	</script>

</head>

<body<?php if(isset($sentence) && $sentence!=''){echo ' onload="read_data(\''.addslashes($sentence).'\')"';} ?>>

	<div id="main-wrapper">

		<h2>Plus aucune chance de voir le financement de son projet refusé, grâce à...</h2>
		<h1>The Amazing Transmedia Project Generator</h1>

		<form id="poll_form" method="post" accept-charset="utf-8">  
			<label><input type="radio" name="poll_option" value="1" id="poll_option" /> Mon projet est encore flou, c'est pour trouver des stagiaires</label><br />
			<label><input type="radio" name="poll_option" value="2" id="poll_option" /> Le projet doit permettre de fédérer des freelances</label><br />
			<label><input type="radio" name="poll_option" value="3" id="poll_option" checked="checked" /> Le projet doit être costaud, c'est pour présenter à SWSX</label><br />
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