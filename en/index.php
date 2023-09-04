<!DOCTYPE html>
<html lang="en">

<?php		
// RECUPERATION DE LA PHRASE EN CAS DE CHARGEMENT DE LA PAGE AVEC HASH
if(isset($_GET['hash']) && $_GET['hash']!='') { // Si on recoit un hash
	$hash = $_GET['hash'];
	$generated_projects_json = file_get_contents("generated_projects_EN.json"); //charge le fichier qui contient l'objet JSON
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

	<title>The Amazing VR Project Generator</title>
	
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
	<link href="https://fonts.googleapis.com/css?family=Bungee+Shade" rel="stylesheet">
	<meta name="Description" content="<?php if(isset($sentence) && $sentence!=''){echo $sentence;}else{echo 'No chance that your project will be refused, thanks to the Amazing VR Project Generator!';} ?>" />
	<meta name="Keywords" content="vr, transmedia, generator" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	<meta property="og:locale" content="en_EN">
	<meta property="og:site_name" content="The Amazing VR Project Generator" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://www.transmediagenerator.com/en/<?php if(isset($hash) && $hash!=''){echo $hash.'.html';}?>" />
	<meta property="og:title" content="<?php if(isset($sentence) && $sentence!=''){echo 'I\'ve just generated my VR project:';}else{echo 'The Amazing VR Project Generator';} ?>" />
	<meta property="og:description" content="<?php if(isset($sentence) && $sentence!=''){echo $sentence;}else{echo 'No chance that your project will be refused, thanks to the Amazing VR Project Generator!';} ?>" />
	<meta property="og:image" content="../style/transmedia_FB_1200x1200.jpg" />
	<link rel="alternate" hreflang="fr" href="https://www.transmediagenerator.com/fr/" />
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
	<style type="text/css">
		h1{
			font-family: 'Bungee Shade', cursive;
			font-size:2.8em;
			color:#91eeef; // #b71d1f
		}
		#poll_form {
			max-width:520px;
		}
		.project-wrapper{
			background: #d3e3d7;
		}
		#list-link {
			color:#79a983;
		}
		@media screen and (max-width:500px) {
			h1 {
				font-size:1.2em;
			}
		}
	</style>
	
	<script src="//code.jquery.com/jquery-3.3.1.min.js" defer></script>
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	
	<script>
		function generate_data() { // si le bouton de génération a été cliqué
			$("#share-wrapper").css("visibility", "hidden");
			$(".project-wrapper").hide("blind", function() {
				$.ajax({
					type: "POST",		
					url: 'ajax_generate_EN.php',
					data: $('#poll_form').serialize(),
					success: function (data) {
						var dataObj = jQuery.parseJSON(data);
						sentence = dataObj.sentence;
						hash = dataObj.hash;
						$(".project").html(sentence);
						history.pushState(sentence, sentence, hash+'.html'); // change l'URL dynamiquement
						if ((typeof sentence !== 'undefined') && (typeof hash !== 'undefined')) { // si les variables existent
							document.getElementById('ShareTwitter').href = 'https://twitter.com/?status='+sentence+' via @TransmediaGen'; // met à jour le lien de partage Twitter
							document.getElementById('ShareFacebook').href = 'https://www.facebook.com/sharer/sharer.php?u=https://www.transmediagenerator.com/en/'+hash+'.html'; // met à jour le lien de partage Facebook		
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

<body<?php if(isset($sentence) && $sentence!=''){echo ' onload="read_data(\''.addslashes($sentence).'\')"';} ?> style="background:url('../style/background_EN-1920x1080-opacite_vert.jpg');">

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
			<li><abbr lang="en" title="English">English version</abbr></li>
			 | 
			<li><a rel="alternate" href="../fr/" hreflang="fr" title="Passer à la version française de ce générateur"><abbr lang="fr" title="Français">Version française</abbr></a></li>
		</ul>
	</nav>
	
	<div id="main-wrapper">
		<!-- <h2>Plus aucune chance de voir le financement de son projet refusé, grâce à...</h2> -->
		<h1>The Amazing<div class="big_h1">VR Project Generator<div class="big_h1"></h1>
		<form id="poll_form" method="post" accept-charset="utf-8">  
			<label><input type="radio" name="poll_option" value="1" /> For finding investors: I want my VR project to be as vague as possible</label><br />
			<label><input type="radio" name="poll_option" value="2" /> For finding freelances: an off-road VR project</label><br />
			<label><input type="radio" name="poll_option" value="3" checked="checked" /> For finding interns: my VR project must sound impressive</label><br />
		</form>
		<div id="generate-button"><input type="button" onClick="generate_data()" value="Create a new VR project!"></div>
		<div class="project-wrapper" style="visibility:hidden;">“ <span class="project"></span> ”</div>
	
		<div id="share-wrapper" style="visibility:hidden;">
			Share this VR project 
			<a href="https://www.facebook.com/sharer/sharer.php<?php if(isset($hash) && $hash!=''){echo '?u=https://www.transmediagenerator.com/en/'.$hash.'.html';}?>" id="ShareFacebook">on Facebook</a> - 
			<a href="https://twitter.com/?status=<?php if(isset($sentence) && $sentence!=''){echo $sentence.' via @TransmediaGen';}?>" id="ShareTwitter">on Twitter</a>
		</div>
	</div>

	<div id="bottom-right-wrapper">
		<a href="splitscreen.php"><img src="../style/VR-NOW.png" /></a>
		<br />
		<a href="list.html" id="list-link">All VR projects created</a>
	</div>

</body>

</html>