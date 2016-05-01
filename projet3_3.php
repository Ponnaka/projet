<!DOCTYPE html> 
<html>
	<head>
		<title>POSITIVOMETRE</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/style.css" />
		<link rel="stylesheet" href="style/projet-css.css" />
		<script src="yui3-3.18.1/build/yui/yui-min.js"></script>
		<link rel="stylesheet" href='yui3-3.18.1/build/cssbutton/cssbutton.css' />
		
		<script src="jQuery/jquery-2.2.0.min.js"></script>
		<link rel="stylesheet" type="text/css" href='jQuery/jquery.datepick.package-5.0.1/jquery.datepick.css'/>
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
			//Affichage de l'aide
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

			//affichade des critères de recherche
			YUI().use('node', function(Y) {
				Y.delegate('click', function(e) {
					var buttonID = e.currentTarget.get('id'),
						node = Y.one('#b_hist');

					if (buttonID === 'Hist') {
						node.toggleView();
					}
				}, document, 'button');
			});

			//affichade des critères de recherche
			YUI().use('node', function(Y) {
				Y.delegate('click', function(e) {
					var buttonID = e.currentTarget.get('id'),
						node = Y.one('#graph');

					if (buttonID === 'Graph') {
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
			</ul>
		</div>
		
		<!--Affichage de la description-->
		<div class="block" id="b_aide">
			<p class="soustitre">Description</p>
			Ce logiciel est destiné à donner une idée à l'utilisateur des 6 émotions (joie, peur, tristesse, colère, surprise, dégout) et de la polarité accompagnant un ensemble de tweets. Il possède les fonctionnalités suivantes :
			<ul>
				<li><strong>Historique </strong> : Liste les émotions associées à chaque tweet sélectionné, avec possibilité de tri par thème, auteur ou date</li>
				<li><strong>Modélisation globale</strong> : Consiste en un graphique en toile d'araignée à 7 axes sur lesquels figure l'intensité de chaques émotions relatives aux tweets sélectionnés.</li>
				<li><strong>Graphiques</strong>  : Modélise sur 7 graphiques en boite à moustache l'évolution dans le temps de chaque émotion.</li>
			</ul>
			</br>L'utilisation est très simple : la recherche des tweets à étudier exige de sélectionner un mot clé, un intervalle de temps et une discrétisation.
		</div>
		
		<!--Affichage des critère de recherche-->
		<form name="form" action="projet3_3.php" method="post">
			<div class="block" id="b_info">

				<p class="soustitre">Recherche</p>
				<table>
					<tr>
						<th>Mots-Clés : </th>
						<td>
							<input type="search" placeholder="Mot-clef" name="mc" value="<?php echo $_POST['mc'];?>">
						</td>
					</tr>
					<tr>
						<th>Période : </th>
						<td>
							<input type="text" value="<?php echo $_POST['debut'];?>" placeholder="Début" class="datepicker" name="debut"><br/>
							<input type="text" value="<?php echo $_POST['fin'];?>" placeholder="Fin" class="datepicker" name="fin">
						</td>
					</tr>
					<tr>
						<th>Discrétisation : </th>
						<td>
							<input type='number' name='disc' placeholder='Entre 1 et 100' min='1' max='100' value="<?php echo $_POST['disc'];?>">
						</td>
					</tr>
				</table>
				<div class="center"><input type="submit" value="Valider"></div>
			</div>
		</form>
		
		<div class="block" id="graph">
			<p class="soustitre">Graphiques</p>
			<div id="container"></div>
			<?php
				if($_POST != null){
					include_once('graphe.php');
					include_once('recup_tweet.php');
					$date_deb = $_POST['debut'];
					$date_fin = $_POST['fin'];
					$pas = $_POST['disc'];
					$mc = $_POST['mc'];
					
					$date_deb = explode('-' , $date_deb);
					$date_fin = explode('-' , $date_fin);
					
					$date_deb = $date_deb[2].'-'.$date_deb[1].'-'.$date_deb[0];
					$date_fin = $date_fin[2].'-'.$date_fin[1].'-'.$date_fin[0];
					
					$date_deb .= " 00:00:00";
					$date_fin .= " 23:59:59";
					
					//recup_tweet($mc);
					
					$Tab=array(0,0,0,0,0,0,0);
					$j=0;
					
					$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root', 'root'); 
					$where_1 = '(`polarity` != 0    or `joy` != 0    or `fear` != 0    or `sadness` != 0    or `anger` != 0    or `surprise` != 0    or `disgust` != 0) and ';
					$sql = 'SELECT * FROM `post` WHERE `post` like "%'.$_POST['mc'].'%" and `strtotime`>='.strtotime($date_deb).' and `strtotime`<='.strtotime($date_fin);
					
					$rep = $bdd->query($sql);
					while ( $ligne = $rep ->fetch() ) {
						$Tab[0]+=$ligne['polarity'];
						$Tab[1]+=$ligne['joy'];
						$Tab[2]+=$ligne['fear'];
						$Tab[3]+=$ligne['sadness'];
						$Tab[4]+=$ligne['anger'];
						$Tab[5]+=$ligne['surprise'];
						$Tab[6]+=$ligne['disgust'];
						$j++;
					}
					for($i=0;$i<7;$i++){
						$Tab[$i]/=$j;
					}
					$data = "[0 , ".$Tab[0]."] , [1 , ".$Tab[1]."] , [2 , ".$Tab[2]."] , [3 , ".$Tab[3]."] , [4 , ".$Tab[4]."] , [5 , ".$Tab[5]."] , [6 ,".$Tab[6]." ]";
					
					if($pas == null){$pas = 10;}
					appel_graph( strtotime($date_deb), strtotime($date_fin) , $pas , $mc , 'polarity');
					appel_graph( strtotime($date_deb), strtotime($date_fin) , $pas , $mc , 'joy');
					appel_graph( strtotime($date_deb), strtotime($date_fin) , $pas , $mc , 'fear');
					appel_graph( strtotime($date_deb), strtotime($date_fin) , $pas , $mc , 'sadness');
					appel_graph( strtotime($date_deb), strtotime($date_fin) , $pas , $mc , 'anger');
					appel_graph( strtotime($date_deb), strtotime($date_fin) , $pas , $mc , 'surprise');
					appel_graph( strtotime($date_deb), strtotime($date_fin) , $pas , $mc , 'disgust');
				}
			?>
			<script type="text/javascript">
				(function a () {
				var container = document.getElementById('container');
				
				//var s1 = {label: 'Actual', data: [[0, 3], [1, 8], [2, 5], [3, 5], [4, 3], [5, 9] , [6, 8.5] ]};
				var s1 = {label: 'Actual', data: [ <?php echo $data;?> ]};
				
				var graph = Flotr.draw(container, s1, {
						radar: {show: true}, 
						grid: {circular: true}, 
						yaxis: {min: 0, max: 100}, 
						xaxis: {ticks: [[0, "Polarité"] , [1, "Joie"], [2,"Peur"], [3, "Tristess"], [4, "Colère"], [5, "Surprise"], [6, "Dégout"]]}
					});
				
				})();
			</script>
		</div>
		
		<!--Affichage de l'historique-->
		<div class="block" id="b_hist">
			<?php
				try{
					$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root', 'root');
				}
				catch (Exception $e) {
					die('Erreur : ' . $e->getMessage() );
				}
				if ($_POST['mc'] != null){
					$data_var;
					$sql = 'SELECT `auteur`, `date`, `heure`, `post` , ROUND(`polarity`,2) as `polarity`, ROUND(`joy`,2) as `joy`, 
					ROUND(`fear`,2) as `fear`, ROUND(`sadness`,2) as `sadness`, ROUND(`anger`,2) as `anger`, ROUND(`surprise`,2) as `surprise`, ROUND(`disgust`,2) as `disgust`
					FROM `post` 
					WHERE `post` like "%'.$_POST['mc'].'%" 
						and `strtotime`>='.strtotime($date_deb).' 
						and `strtotime`<='.strtotime($date_fin).' 
					ORDER BY date DESC, heure DESC';
					
					$rep = $bdd->query($sql);
					while($data = $rep ->fetch()){
						//print_r($data);
						$data_var .= "{";
						foreach($data as $key=>$value){
							if (gettype($key) != "integer"){
								$data_var .= $key.':"'.str_replace('"' , '\"' , $value).'" , ';
							}
						}
						$data_var = substr($data_var,0,-2)."},";
					}
					$data_var = substr($data_var, 0, -1);
				}

			?>
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
		
	</body>
</html>