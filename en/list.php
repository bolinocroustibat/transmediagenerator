<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="UTF-8" />

	<title>The Amazing VR Project Generator | All created projects</title>
	
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
	<link href="https://fonts.googleapis.com/css?family=Bungee+Shade" rel="stylesheet">
	<meta name="Description" content="No chance that your project will be refused, thanks to the Amazing VR Project Generator!" />
	<meta name="Keywords" content="virtual reality, vr, generator, transmedia" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	<meta property="og:title" content="The Amazing VR Project Generator | All created projects" />
	<meta property="og:image" content="../style/transmedia_FB_1200x1200.jpg" />
	<meta property="og:url" content="https://www.transmediagenerator.com/fr/liste.html" />
	<meta property="og:site_name" content="The Amazing VR Project Generator" />
	<meta property="og:description" content="No chance that your project will be refused, thanks to the Amazing VR Project Generator!" />
	<link rel="alternate" hreflang="fr" href="https://www.transmediagenerator.com/fr/liste.html" />
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
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
</head>

<body style="background:url('../style/background_EN-1920x1080-opacite_vert.jpg');">

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-68594064-1', 'auto');
		ga('send', 'pageview');
	</script>
	
	<div id="main-wrapper">

		<!-- <h2>Plus aucune chance de voir le financement de son projet refusé, grâce à...</h2> -->
		<h1>The Amazing<div class="big_h1">VR Project Generator<div class="big_h1"></h1>

		<ul>
			<?php		
			$generated_projects_json = file_get_contents("generated_projects_EN.json"); //charge le fichier qui contient l'objet JSON
			$generated_projects_table = array_reverse(json_decode($generated_projects_json,true)); // transforme l'objet JSON en tableau PHP, qu'on met à l'envers (chronologique)
			$total_nb = count($generated_projects_table); //nb d'entrées dans le tableau
			$nb=0;
			foreach ($generated_projects_table as $row_obj) { // parcourt chaque ligne du tableau PHP
				$row = json_decode($row_obj,true); // tranforme l'objet-ligne en tableau
				$hash = $row["hash"];
				$sentence = $row["sentence"];
				echo ($total_nb-$nb).'<sup>e</sup> projet transmédia créé :<li class="project-wrapper">“ <a class="project" href="'.$hash.'.html">'.$sentence.'</a> ”</li>';
				$nb++;
			}
			?>
		</ul>
	
	</div>

</body>

</html>