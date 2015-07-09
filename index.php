<!DOCTYPE html>
<html lang="fr">

<head>

	<meta charset="UTF-8" />

	<title>DEV - Le générateur de projet transmédia</title>
	
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	
	<meta name="Description" content="" />
	<meta name="Generator" content="Notepad++" />
	<meta name="Keywords" content="" />
	
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />

	<link href="style/normalize.css" rel="stylesheet" type="text/css" media="all">
	<link href="style/style.css" rel="stylesheet" type="text/css" media="all">	
	
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
	<script>

		function generate_data() {
			$("#projet-wrapper").hide("fold", function() {
				$("#projet-wrapper").css("visibility", "visible");
				$.ajax({
						type: "POST",		
						url: 'ajax_generate.php',
						data: $('#poll_form').serialize(),
						success: function (data) {
							var dataObj = jQuery.parseJSON(data);
							$("#projet").html(dataObj.sentence);
							history.pushState(dataObj.sentence, dataObj.sentence, dataObj.hash+'.html');
						}
				})
			});
		$("#projet-wrapper").show("fold",300);
		}

		function read_data(hash) {
			var hash  = window.location.pathname.split('/').pop().slice(0, -5); //slice enlève les 4 dernires caratères (.html) et slip et pop enlèvent le chemin
			//alert(hash);
			var dataObj = jQuery.getJSON("data.json");
			//alert(dataObj.hash); // TEST

			/*
			function getByHash(hash) {
			    var found = null;
			    for (var i = 0; i < data.length; i++) {
			        var element = data[i];

			        if (element.Key == hash) {
			           found = element;
			       } 
			    }
			    return found;
			}
			*/

		}
	</script>

</head>

<body onLoad='read_data();'>

	<h2>Plus aucune chance de voir le financement de son projet refusé, grâce au...</h2>
	<h1>Générateur de projet transmédia</h1>

	<form id="poll_form" method="post" accept-charset="utf-8">  
		<input type="radio" name="poll_option" value="1" id="poll_option" /><label for='1'> Mon projet est encore flou, c'est pour trouver des stagiaires</label><br />
		<input type="radio" name="poll_option" value="2" id="poll_option" /><label for='2'> Le projet doit permettre de fédérer des freelances</label><br />
		<input type="radio" name="poll_option" value="3" id="poll_option" checked="checked" /><label for='3'> Le projet doit être costaud, c'est pour présenter à SWSX</label><br />
	</form>
	
	<div id="bouton-actualiser"><input type="button" onClick="generate_data()" value="Générer un nouveau projet"></div>

	<div id="projet-wrapper" style="visibility:hidden;">“ <span id="projet"></span> ”</div>

	<script type="text/javascript">
	    document.write(hash_url);
	</script>
	
</body>

</html>