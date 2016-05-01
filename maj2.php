﻿<!DOCTYPE html> 
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>MAJ2</title>
	</head>
	
	<body>
<?php		
		function recup_tweet($mc){
			echo '<h1>'.$mc.'</h1>';
			include_once('PHPcheck.php');
			include_once('lemm2.php');
			include_once('recupvect.php');
			
			$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root', 'root');
			$tmp="";
			$id_ignore = array(893 , 892 , 867 , 866 , 788 , 759 , 758 , 754 , 734 , 733 , 732 , 720 , 651 , 558 , 559 , 635);
			foreach($id_ignore as $value){
				$tmp .= ' and id != '.$value;
			}
			//$date = 'and date = "2016-04-21" ';
			$sql = 'SELECT * FROM `post` WHERE 	`polarity` = 0    and `joy` = 0    and `fear` = 0    and `sadness` = 0    and `anger` = 0    and `surprise` = 0    and `disgust` = 0';
			//echo $sql;
			$rep = $bdd->query($sql);
			$i=0;
			while ($data = $rep->fetch()){
				if($i == 5){header("Refresh: 1;url='http://localhost/projet/maj2.php'");}
				$tmp = $data['post'];
				$corr = nl2br(correct_text(stripslashes($tmp)));
					echo "Traitement du tweet : ".$data['id'].'<br/>';
					//echo $data['auteur'].'<br/>';
					//echo "corr : ".$corr.'<br/>';
				//sleep(2);
				$lemm = lemm(strtolower ($corr));
					//echo "lemm : ";print_r ($lemm);echo "<br/>";
				if(count($lemm) > 1 ){
					$vect = vect($lemm);
						//echo "vect : ";print_r ($vect);echo "<br/>";
					if($vect[7] == 1){
						$strtotime = strtotime($data['date']." ".$data['heure']);
						$sql_2 = "UPDATE `post` SET `strtotime`='".$strtotime."',`polarity`='".$vect[0]."',`joy`='".$vect[1]."',`fear`='".$vect[2]."',`sadness`='".$vect[3]."',`anger`='".$vect[4]."',`surprise`='".$vect[5]."',`disgust`='".$vect[6]."' WHERE `id`= ".$data['id'];
						$rep_2 = $bdd->query($sql_2);
						echo "MAJ ".$data['id']."<br/>";
					}
					else{
						$sql_3 = "DELETE FROM `post` WHERE `id`= ".$data['id'];
						$rep_3 = $bdd->query($sql_3);
						echo "SUP ".$data['id']."<br/>";
					}				
					$i++;					
				}
				else{
					$sql_3 = "DELETE FROM `post` WHERE `id`= ".$data['id'];
					$rep_3 = $bdd->query($sql_3);
					echo "erreur lemmatisation ".$data['id']."<br/>";
				}
			}
			echo $i;
		}
		recup_tweet('MAJ 2');
?>
	</body>
</html>