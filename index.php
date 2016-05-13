<!DOCTYPE html> 
<html>
	<head>
		<meta charset="utf-8">
		<title>Positivomètre</title>
		<link rel="stylesheet" type="text/css" href="style/style.css" />
		<link rel="stylesheet" type="text/css" href="style/projet-css.css" />
		<link rel="stylesheet" type="text/css" href='jQuery/jquery.datepick.package-5.0.1/jquery.datepick.css'/>
		<link rel="stylesheet" type="text/css" href='yui3-3.18.1/build/cssbutton/cssbutton.css' />
		
		<script type="text/javascript" src="yui3-3.18.1/build/yui/yui-min.js"></script>
		<script type="text/javascript" src="jQuery/jquery-2.2.0.min.js"></script>
		<script type="text/javascript" src="jQuery/jquery.datepick.package-5.0.1/jquery.plugin.js"></script>
		<script type="text/javascript" src="jQuery/jquery.datepick.package-5.0.1/jquery.datepick.js"></script>
		<script type="text/javascript" src="Flotr2-master/flotr2.min.js"></script>
		<style type="text/css">
			#container {
				width : 600px;
				height: 384px;
				margin: 8px auto;
			}
		</style>
		
		<!--
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		-->
		<link rel="stylesheet" href="style/jquery-ui.css">
		<script src="jQuery/jquery-2.2.0.js"></script>
		<script src="jQuery/jquery-ui.js"></script>
		<?php session_start();?>
		
		<script>
		<?php
			include_once('courbe_hist.php');
			include_once('recup_tweet.php');
			
			if($_POST['mc'] != null && $_POST['debut'] != null && $_POST['fin'] != null ||
			$_SESSION['post']['mc'] != $_POST['mc'] && $_SESSION['post']['debut'] != $_POST['debut'] && $_SESSION['post']['fin'] != $_POST['fin']){
				//Création des variables
				if($_POST['mc'] != null){$mc=$_POST['mc'];}else{$mc=$_SESSION['post']['mc'];}
				if($_POST['internet'] != null){$internet=$_POST['mc'];}else{$internet=$_SESSION['post']['internet'];}
				if($_POST['debut'] != null){
					$deb = $_POST['debut'];
					$date_deb = strtotime(implode('-' , array_reverse(explode('-' , $deb))));
				}
				else{
					$deb = $_SESSION['post']['debut'];
					$date_deb = strtotime(implode('-' , array_reverse(explode('-' , $deb))));
				}
				if(  $_POST['fin'] != null){
					$fin = $_POST['fin'];
					$date_fin = strtotime(implode('-' , array_reverse(explode('-' , $_POST['fin']))));
				}
				else{
					$fin = $_SESSION['post']['fin'];
					$date_fin = strtotime(implode('-' , array_reverse(explode('-' , $_SESSION['post']['fin']))));
				}
				if($_POST['disc'] != null){$pas = $_POST['disc'];}
				else{$pas = $_SESSION['post']['disc'];}
				if($pas == null){$pas = 10;}
				
				if($_POST['internet'] == 'oui' || $_SESSION['post']['internet'] == 'oui' ){		//Recherche internet activé
					$_SESSION['post'] = $_POST;
					$_SESSION['post']['internet'] = 'non';
					recup_tweet($mc);
				}
				else{																			//Affichage des résultats de recherche
					session_destroy();					
					if($date_deb>$date_fin){
						$datedeb = strtotime(implode('-' , array_reverse(explode('-' , $fin)))." 00:00:00");
						$datefin = strtotime(implode('-' , array_reverse(explode('-' , $deb)))." 23:59:59");
					}
					else{
						$datedeb = strtotime(implode('-' , array_reverse(explode('-' , $deb)))." 00:00:00");
						$datefin = strtotime(implode('-' , array_reverse(explode('-' , $fin)))." 23:59:59");
					}
					
					$data_var = historique ($mc , $datedeb , $datefin );
					$data =     courbe_radar ($mc , $datedeb , $datefin );
					$graph =    "'".quadri ($pas , $datedeb , $datefin , "")."'";
					$polarity = "'". courbe_1($datedeb , $datefin , $pas , $mc , "polarity" , "rgb(187,187,187)" )."'";
					$joy =      "'". courbe_1($datedeb , $datefin , $pas , $mc , "joy"      , "rgb(255,0,0)" )."'";
					$fear =     "'". courbe_1($datedeb , $datefin , $pas , $mc , "fear"     , "rgb(0,255,0)" )."'";
					$sadness =  "'". courbe_1($datedeb , $datefin , $pas , $mc , "sadness"  , "rgb(0,0,255)" )."'";
					$anger =    "'". courbe_1($datedeb , $datefin , $pas , $mc , "anger"    , "rgb(121,121,0)" )."'";
					$surprise = "'". courbe_1($datedeb , $datefin , $pas , $mc , "surprise" , "rgb(255,0,255)" )."'";
					$disgust =  "'". courbe_1($datedeb , $datefin , $pas , $mc , "disgust"  , "rgb(0,121,121)" )."'";
					
					$polarity2 = "'". courbe_2($datedeb , $datefin , $pas , $mc , "polarity" , array(187,187,187) , "Polarité" )."'";
					$joy2 =      "'". courbe_2($datedeb , $datefin , $pas , $mc , "joy"      , array(255,0,0) , "Joie" )."'";
					$fear2 =     "'". courbe_2($datedeb , $datefin , $pas , $mc , "fear"     , array(0,255,0) , "Peur" )."'";
					$sadness2 =  "'". courbe_2($datedeb , $datefin , $pas , $mc , "sadness"  , array(0,0,255) , "Tristesse" )."'";
					$anger2 =    "'". courbe_2($datedeb , $datefin , $pas , $mc , "anger"    , array(121,121,0) , "Colère" )."'";
					$surprise2 = "'". courbe_2($datedeb , $datefin , $pas , $mc , "surprise" , array(255,0,255) , "Surprise" )."'";
					$disgust2 =  "'". courbe_2($datedeb , $datefin , $pas , $mc , "disgust"  , array(0,121,121) , "Dégout" )."'";
				}
			}
		?>
			$(document).ready(function () {
				(function a () {
					var container = document.getElementById('container');
					var s1 = {label: 'Polarité', data: [ <?php echo $data[1];?> ]};
					var s2 = {label: 'Emotion' , data: [ <?php echo $data[0];?> ]};
					
					var graph = Flotr.draw(container, [s1,s2], {
					//var graph = Flotr.draw(container, [s2], {
							radar: {show: true}, 
							grid: {circular: true,minorHorizontalLines: true}, 
							yaxis: {min: 0, max: 100,minorTickFreq: 2}, 
							xaxis: {ticks: [[0, "Joie"], [1,"Peur"], [2, "Tristess"], [3, "Colère"], [4, "Surprise"], [5, "Dégout"]]},
							 legend : { position : 'sw', labelBoxBorderColor: '#fff' , backgroundOpacity: 0}
						});
				})();
				
				var balise1 = '<svg width="1085" height="400">';
				var graph = <?php echo $graph;?>;
				var polarity = <?php echo $polarity;?>;
				var joy = <?php echo $joy;?>;
				var fear = <?php echo $fear;?>;
				var sadness = <?php echo $sadness;?>;
				var anger = <?php echo $anger;?>;
				var surprise = <?php echo $surprise;?>;
				var disgust = <?php echo $disgust;?>;
				var courbe0 = polarity;
				var courbe1 = joy;
				var courbe2 = fear;
				var courbe3 = sadness;
				var courbe4 = anger;
				var courbe5 = surprise;
				var courbe6 = disgust;
				var balise2 = '</svg"><br/><br/>';
				$("input[name=pola]").click(function() {
					if( $('input[name=pola]').is(':checked') ){courbe0 = polarity;}
					else{courbe0 = "";}
					$("#grille").html(balise1+graph+courbe0+courbe1+courbe2+courbe3+courbe4+courbe5+courbe6+balise2);
				});
				$("input[name=joie]").click(function() {
					if( $('input[name=joie]').is(':checked') ){courbe1 = joy;}
					else{courbe1 = "";}
					$("#grille").html(balise1+graph+courbe0+courbe1+courbe2+courbe3+courbe4+courbe5+courbe6+balise2);
				});
				$("input[name=peur]").click(function() {
					if( $('input[name=peur]').is(':checked') ){courbe2 = fear;}
					else{courbe2 = "";}
					$("#grille").html(balise1+graph+courbe0+courbe1+courbe2+courbe3+courbe4+courbe5+courbe6+balise2);
				});
				$("input[name=tris]").click(function() {
					if( $('input[name=tris]').is(':checked') ){courbe3 = sadness;}
					else{courbe3 = "";}
					$("#grille").html(balise1+graph+courbe0+courbe1+courbe2+courbe3+courbe4+courbe5+courbe6+balise2);
				});
				$("input[name=cole]").click(function() {
					if( $('input[name=cole]').is(':checked') ){courbe4 = anger;}
					else{courbe4 = "";}
					$("#grille").html(balise1+graph+courbe0+courbe1+courbe2+courbe3+courbe4+courbe5+courbe6+balise2);
				});
				$("input[name=surp]").click(function() {
					if( $('input[name=surp]').is(':checked') ){courbe5 = surprise;}
					else{courbe5 = "";}
					$("#grille").html(balise1+graph+courbe0+courbe1+courbe2+courbe3+courbe4+courbe5+courbe6+balise2);
				});
				$("input[name=dego]").click(function() {
					if( $('input[name=dego]').is(':checked') ){courbe6 = disgust;}
					else{courbe6 = "";}
					$("#grille").html(balise1+graph+courbe0+courbe1+courbe2+courbe3+courbe4+courbe5+courbe6+balise2);
				});
			
				$("#grille").html(balise1+graph+courbe0+courbe1+courbe2+courbe3+courbe4+courbe5+courbe6+balise2);
				
				var polarity2 = <?php echo $polarity2;?>;
				var joy2 = <?php echo $joy2;?>;
				var fear2 = <?php echo $fear2;?>;
				var sadness2 = <?php echo $sadness2;?>;
				var anger2 = <?php echo $anger2;?>;
				var surprise2 = <?php echo $surprise2;?>;
				var disgust2 = <?php echo $disgust2;?>;
				
				
				$("#pola").html(balise1+graph+polarity2+balise2);
				$("#joie").html(balise1+graph+joy2+balise2);
				$("#peur").html(balise1+graph+fear2+balise2);
				$("#tris").html(balise1+graph+sadness2+balise2);
				$("#cole").html(balise1+graph+anger2+balise2);
				$("#surp").html(balise1+graph+surprise2+balise2);
				$("#dego").html(balise1+graph+disgust2+balise2);
			});
		</script>
			
		<script type="text/javascript">
		/*_____________________
		|					  |
		|Affichage des Boutons|
		|_____________________|*/
			// Create a YUI sandbox on your page.
			YUI().use('node', 'event', function (Y) {
				// The Node and Event modules are loaded and ready to use.
				// Your code goes here!
			});
			//Affichage de la description
			YUI().use('node', function(Y) {
				Y.delegate('click', function(e) {
					var buttonID = e.currentTarget.get('id'),
						node = Y.one('#b_aide');

					if (buttonID === 'Aide') {
						node.toggleView();
					}

				}, document, 'button');
			});
			//affichade des critères de recherche
			YUI().use('node', function(Y) {
				Y.delegate('click', function(e) {
					var buttonID = e.currentTarget.get('id'),
						node = Y.one('#b_info');

					if (buttonID === 'Info') {
						node.toggleView();
					}

				}, document, 'button');
			});

			//affichade des graphiques
			YUI().use('node', function(Y) {
				Y.delegate('click', function(e) {
					var buttonID = e.currentTarget.get('id'),
						node = Y.one('#b_hist');

					if (buttonID === 'Hist') {
						node.toggleView();
					}
				}, document, 'button');
			});

			//affichade de l'historique
			YUI().use('node', function(Y) {
				Y.delegate('click', function(e) {
					var buttonID = e.currentTarget.get('id'),
						node = Y.one('#graph');

					if (buttonID === 'Graph') {
						node.toggleView();
					}
				}, document, 'button');
			});

			//affichade des contacts
			YUI().use('node', function(Y) {
				Y.delegate('click', function(e) {
					var buttonID = e.currentTarget.get('id'),
						node = Y.one('#contact');

					if (buttonID === 'Contact') {
						node.toggleView();
					}
				}, document, 'button');
			});
		
		/*________________________
		|						  |
		|Affichage des Calendriers|
		|_________________________|*/
			$(
				function() {
					$( ".datepicker" ).datepicker({dateFormat: 'dd-mm-yy'});
				}
			);
		</script>
	</head> 
	<body class="yui3-skin-sam">
		<h1>
			<font class="moyen">
				<span style="color:#33cc09">P</span>
				<span style="color:#44bb08">O</span>
				<span style="color:#55aa08">S</span>
				<span style="color:#669907">I</span>
				<span style="color:#778806">T</span>
				<span style="color:#887705">I</span>
				<span style="color:#996605">V</span>
				<span style="color:#aa5504">O</span>
				<span style="color:#bb4403">M</span>
				<span style="color:#cc3302">E</span>
				<span style="color:#dd2202">T</span>
				<span style="color:#ee1101">R</span>
				<span style="color:#ff0000">E</span>
			</font>
		</h1>
		
		<div id="menu">
			<ul>
				<li><button id="Aide" class="yui3-button">Description</button></li>
				<li><button id="Info" class="yui3-button">Recherche</button></li>
				<li><button id="Graph" class="yui3-button">Graphiques</button></li>
				<li><button id="Hist" class="yui3-button">Historique</button></li>
				<li><button id="Contact" class="yui3-button">Contacts</button></li>
			</ul>
		</div>
		
		<!--Affichage de la description-->
		<div class="block" id="b_aide">
			<p class="soustitre">Description</p>
			Ce logiciel est destiné à donner une idée à l'utilisateur des 6 émotions (joie, peur, tristesse, colère, surprise, dégout) et de la polarité accompagnant un ensemble de tweets. Il possède les fonctionnalités suivantes :
			<ul>
				<li><strong>Modélisation globale</strong> : Consiste en un graphique en toile d'araignée à 6 axes sur lesquels figure l'intensité de chaques émotions relatives aux tweets sélectionnés.</li>
				<li><strong>Graphiques</strong>  : 1 graphique permettant de suivre l'évolution les émotions et la polarité et 7 graphiques en boite à moustache l'évolution dans le temps de chaque émotion.</li>
				<li><strong>Historique </strong> : Liste les émotions associées à chaque tweet sélectionné, avec possibilité de tri par thème, auteur ou date</li>
			</ul>
			<br/>L'utilisation est très simple : la recherche des tweets à étudier exige de sélectionner un mot clé, un intervalle de temps et une discrétisation.
		</div>
		
		<!--Affichage des critère de recherche-->
		<form name="form" action="index.php" method="post">
			<div class="block" id="b_info">

				<p class="soustitre">Recherche</p>
				<table>
					<tr>
						<th>Mots-Clés : </th>
						<td>
							<input type="search" placeholder="Mot-clef" name="mc" value="<?php echo $mc;?>">
						</td>
					</tr>
					<tr>
						<th>Période : </th>
						<td>
							<input type="text" value="<?php echo $deb;?>" placeholder="Début" class="datepicker" name="debut"><br/>
							<input type="text" value="<?php echo $fin;?>" placeholder="Fin" class="datepicker" name="fin">
						</td>
					</tr>
					<tr>
						<th>Discrétisation : </th>
						<td>
							<input type='number' name='disc' placeholder='Entre 1 et 100' min='1' max='100' value="<?php echo $pas;?>">
						</td>
					</tr>
					<tr>
						<th>Recherche Internet : </th>
						<td>
						<?php 
							if($internet == 'oui'){
								echo '<label><input type="radio" name="internet" value="oui" checked>Oui<br></label>
							<label><input type="radio" name="internet" value="non" >Non </label>';
							}
							else{
								echo '<label><input type="radio" name="internet" value="oui" >Oui<br></label>
							<label><input type="radio" name="internet" value="non" checked>Non </label>';
							}
						?>
						</td>
					</tr>
				</table>
				<div class="center"><input type="submit" value="Valider"></div>
			</div>
		</form>
		
		<!--Affichage des graphiques-->
		<div class="block" id="graph">
			<p class="soustitre">Graphiques</p>
			<div id="container"></div>
			<!---->
			<div id="grille"></div>
			<div>
				<br/>
				<label><input type="checkbox" name="pola" value="1" checked ><font color="BBBBBB">Polarité</font></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="checkbox" name="joie" value="2" checked ><font color="FF0000">Joie</font></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="checkbox" name="peur" value="3" checked ><font color="00FF00">Peur</font></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="checkbox" name="tris" value="4" checked ><font color="0000FF">Tristesse</font></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="checkbox" name="cole" value="5" checked ><font color="AAAA00">Colère</font></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="checkbox" name="surp" value="6" checked ><font color="FF00FF">Surprise</font></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="checkbox" name="dego" value="7" checked ><font color="00AAAA">Dégout</font></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<br/>
			<br/>
			<br/>
			<div id="pola"></div>
			<div id="joie"></div>
			<div id="peur"></div>
			<div id="tris"></div>
			<div id="cole"></div>
			<div id="surp"></div>
			<div id="dego"></div>
			

		</div>
		
		<!--Affichage de l'historique-->
		<div class="block" id="b_hist">
			<!--Tableau-->
			<script type="text/javascript">
				YUI().use("datatable-sort", function(Y) {
					var cols = [
						{key:"auteur", label:"Auteur" , sortable:true},
						{key:"date", label:"Date", sortable:true},
						{key:"heure", label:"Heure", sortable:true},
						{key:"post", label:"Post", sortable:true , width:"80px"},
						{key:"polarity", label:"Polarité" , sortable:true},
						{key:"joy", label:"Joie",  sortable:true},
						{key:"fear", label:"Peur",  sortable:true},
						{key:"sadness", label:"Tristesse", sortable:true},
						{key:"anger", label:"Colère",  sortable:true},
						{key:"surprise", label:"Surprise", sortable:true},
						{key:"disgust", label:"Dégout", sortable:true},
					],
					data = [<?php echo $data_var;?>],
					table = new Y.DataTable({
						columns: cols,
						data   : data,
						//summary: "Contacts list",
						//caption: "Historique des messages"
					}).render("#table");
				});
			</script>
			<p class="soustitre">Historique</p>
			<div id="table"></div>
		</div>
		
		<!--Affichage de l'historique-->
		<div class="block" id="contact">
			<p class="soustitre">Contacts</p>
			<table>
				<tr>
					<td>DELEGLISE Hugo : </td>
					<td>hugo_3030@hotmail.fr</td>
				</tr>
				<tr>
					<td>TITH Ponnaka : </td>
					<td>ponnaka.tith@yahoo.fr</td>
				</tr>
			</table>
		</div>
	</body>
</html>