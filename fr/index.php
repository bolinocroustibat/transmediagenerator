<!DOCTYPE html>
<html lang="fr">

<?php		
// RECUPERATION DE LA PHRASE EN CAS DE CHARGEMENT DE LA PAGE AVEC HASH
if(isset($_GET['hash']) && $_GET['hash']!='') { // Si on recoit un hash
	$hash = $_GET['hash'];
	$generated_projects_json = file_get_contents("generated_projects_FR.json"); //charge le fichier qui contient l'objet JSON
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

	<title>The Amazing Transmedia Project Generator</title>
	
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Codystar' rel='stylesheet'>
	<meta name="Description" content="<?php if(isset($sentence) && $sentence!=''){echo $sentence;}else{echo 'Plus aucune chance de voir le financement de son projet refusé, grâce au générateur de projet transmédia !';} ?>" />
	<meta name="Generator" content="Notepad++" />
	<meta name="Keywords" content="transmedia, transmédia, générateur, generator, generateur, crossmedia" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta property="og:locale" content="fr_FR" />
	<meta property="og:site_name" content="The Amazing Transmedia Project Generator" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://www.transmediagenerator.com/fr/<?php if(isset($hash) && $hash!=''){echo $hash.'.html';}?>" />
	<meta property="og:title" content="<?php if(isset($sentence) && $sentence!=''){echo 'J\'ai généré mon projet transmédia :';}else{echo 'The Amazing Transmedia Project Generator';} ?>" />
	<meta property="og:description" content="<?php if(isset($sentence) && $sentence!=''){echo $sentence;}else{echo 'Plus aucune chance de voir le financement de son projet refusé, grâce au générateur de projet transmédia !';} ?>" />
	<meta property="og:image" content="style/transmedia_FB_1200x1200.jpg" />
	<link rel="alternate" hreflang="en" href="http://www.transmediagenerator.com/en/" />
	<link rel="apple-touch-icon" sizes="57x57" href="../style/favicons/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="../style/favicons/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="../style/favicons/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="../style/favicons/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="../style/favicons/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="../style/favicons/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="../style/favicons/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="../style/favicons/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="../style/favicons/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="../style/favicons/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../style/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="../style/favicons/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../style/favicons/favicon-16x16.png">
	<link rel="manifest" href="../style/favicons/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css" rel="stylesheet" type="text/css" media="all">
	<link href="../style/style.css" rel="stylesheet" type="text/css" media="all">	
	<script src="//code.jquery.com/jquery-3.3.1.min.js" defer></script>
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	
	<script>
		function generate_data() { // si le bouton de génération a été cliqué
			$("#share-wrapper").css("visibility", "hidden");
			$(".project-wrapper").hide("blind", function() {
				$.ajax({
					type: "POST",		
					url: 'ajax_generate_FR.php',
					data: $('#poll_form').serialize(),
					success: function (data) {
						var dataObj = jQuery.parseJSON(data);
						sentence = dataObj.sentence;
						hash = dataObj.hash;
						$(".project").html(sentence);
						history.pushState(sentence, sentence, hash+'.html'); // change l'URL dynamiquement
						if ((typeof sentence !== 'undefined') && (typeof hash !== 'undefined')) { // si les variables existent
							document.getElementById('ShareTwitter').href = 'http://twitter.com/?status='+sentence+' via @TransmediaGen'; // met à jour le lien de partage Twitter
							document.getElementById('ShareFacebook').href = 'http://www.facebook.com/sharer/sharer.php?u=http://www.transmediagenerator.com/fr/'+hash+'.html'; // met à jour le lien de partage Facebook		
						}
					}
				})
				$(".project-wrapper").css("visibility", "visible");
			});
			$(".project-wrapper").show("blind", function() {
				$("#share-wrapper").css("visibility", "visible");
			});
		}
		function read_data(sentence) { // si on a reçu la phrase grâce au hash dans l'URL, utilisée en argument du body onload
			$(".project-wrapper").css("visibility", "visible");
			$("#share-wrapper").css("visibility", "visible");
			$(".project").html(sentence);
		}
	</script>
</head>

<body<?php if(isset($sentence) && $sentence!=''){echo ' onload="read_data(\''.addslashes($sentence).'\')"';} ?> style="background:url('../style/background_FR-1920x1080-opacite.jpg') no-repeat center center fixed;">

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-68594064-1', 'auto');
		ga('send', 'pageview');
	</script>
	
	<nav id="language">
	  <ul>
		<li><a rel="alternate" href="../en/" hreflang="en" title="Switch to the English version of this generator"><abbr lang="en" title="English">English version</abbr></a></li>
		 | 
		<li><abbr lang="fr" title="Français">Version française</abbr></li>
	  </ul>
	</nav>
	
	<div id="main-wrapper">

		<!-- <h2>Plus aucune chance de voir le financement de son projet refusé, grâce à...</h2> -->
		<h1>The Amazing<div class="big_h1">Transmedia Project Generator</div></h1>

		<form id="poll_form" method="post" accept-charset="utf-8">  
			<label><input type="radio" name="poll_option" value="1" /> Mon projet est encore flou, c'est pour trouver des stagiaires</label><br />
			<label><input type="radio" name="poll_option" value="2" /> Mon projet doit permettre de fédérer des freelances</label><br />
			<label><input type="radio" name="poll_option" value="3" checked="checked" /> Mon projet doit être costaud, c'est pour présenter à SXSW</label><br />
		</form>
		<div id="generate-button"><input type="button" onClick="generate_data()" value="Créer un nouveau projet transmédia !"></div>
		<div class="project-wrapper" style="visibility:hidden;">“ <span class="project"></span> ”</div>
				<div id="share-wrapper" style="visibility:hidden;">
			Partager ce projet 
			<a href="http://www.facebook.com/sharer/sharer.php<?php if(isset($hash) && $hash!=''){echo '?u=http://www.transmediagenerator.com/fr/'.$hash.'.html';}?>" id="ShareFacebook">sur Facebook</a> - 
			<a href="http://twitter.com/?status=<?php if(isset($sentence) && $sentence!=''){echo $sentence.' via @TransmediaGen';}?>" id="ShareTwitter">sur Twitter</a>
		</div>
	
	</div>
	
	<div id="bottom-right-wrapper">
		<a href="liste.html"" id="list-link">Tous les projets transmedia créés</a>
	</div>

</body>

</html>