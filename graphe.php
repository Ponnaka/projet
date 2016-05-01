<?php
	function appel_graph($datedeb , $datefin , $nbbat , $mc , $sentiment) {
		$pas = abs($datefin-$datedeb)/$nbbat;
		$Tab=array();
		for($i=0;$i<$nbbat;$i++){
			$Tab[$i]=array();
		}
		
		$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root', 'root'); 
		$where_1 = '(`polarity` != 0    or `joy` != 0    or `fear` != 0    or `sadness` != 0    or `anger` != 0    or `surprise` != 0    or `disgust` != 0) and ';
		$sql = 'SELECT * FROM `post` WHERE '.$where_1.' `post` like "%'.$mc.'%" and `strtotime`>='.$datedeb.' and `strtotime`<='.$datefin.' order by `'.$sentiment.'`';
		//echo $sql;
		$rep = $bdd->query($sql);
		while ($ligne = $rep ->fetch()) {
			for($i=0;$i<$nbbat;$i++){
				$tmp = array();
				if($ligne['strtotime']>=$datedeb+$i*$pas and $ligne['strtotime']<=$datefin+($i+1)*$pas){
					//echo $ligne[$sentiment];
					array_push($Tab[$i],$ligne[$sentiment]);
				}
			}
		}
		
		$Tabmoy=array();
		$Tabdecil1=array();
		$Tabdecil9=array();
		$Tabconf=array();
		
		for($i=0;$i<$nbbat;$i++){
			$x1=round(count($Tab[$i])/4);
			$x9=round(count($Tab[$i])*3/4);
			$Tabdecil1[$i]=$Tab[$i][$x1]/100;
			$Tabmoy[$i]=array_sum($Tab[$i])/count($Tab[$i])/100;
			$Tabdecil9[$i]=$Tab[$i][$x9]/100;
			$var=0; 
			foreach($Tab[$i] as $v){ 
				$var+=pow(($v-$Tabmoy[$i]),2); 
			} 
			$var=$var/(count($Tab[$i])-1); 
			$Tabconf[$i]=4*sqrt($var/count($Tab[$i]));
			if($Tabconf[$i] > 100 ){$Tabconf[$i]=100;}
		}
		graphique ($Tabmoy , $Tabdecil1 , $Tabdecil9 , $Tabconf , $datedeb , $datefin , $sentiment);
	}
	
	
	function graphique ($moy , $q1 , $q3 , $valide , $x_min , $x_max, $titre){
		$marge=24;
		$taille=5;
		$moy2=$moy;
		$q1_2=$q1;
		$q3_2=$q3;
		$valide2=$valide;
		$valide3=$valide;
		
		$pas_x=count($moy);						$pas_y=50;
		$width=1085;							$height=400;
		$long_axe_x=$width-2*$marge;			$long_axe_y=$height-2*$marge;
		$xa=$marge;								$ya=$marge;
		$xb=$width-$marge;						$yb=$height-$marge;
		$xc=$marge-$taille;						$yc=$yb+$taille;
		$axe_x=array();							$axe_y=array();
		$grad_x=array();						$grad_y=array();
		$rect_x=$long_axe_x/$pas_x;
		
		for( $i=0 ; $i<$pas_x ; $i++){
			$tmp=$marge+$i*($long_axe_x/$pas_x);
			array_push($axe_x,$tmp);
			$tmp=round($x_min+$i*(($x_max-$x_min)/$pas_x),2);
			array_push($grad_x, date("Y-m-d" , $tmp) );
			$moy2[$i]=$height-$marge-($moy2[$i]*$long_axe_y);
			$q1_2[$i]=$height-$marge-($q1_2[$i]*$long_axe_y);
			$q3_2[$i]=$height-$marge-($q3_2[$i]*$long_axe_y);
			$tmp = ceil($valide2[$i]*255/100);
			$valide2[$i]= '" style="stroke:rgb(0,'.$tmp.',0);stroke-width:4"';
			$valide3[$i]= '" style="stroke:rgb(0,'.$tmp.',0);"';
		}
		$tmp=$marge+$i*($long_axe_x/$pas_x);
		array_push($axe_x,$tmp);
		$tmp=$x_min+$i*(($x_max-$x_min)/$pas_x);
		array_push($grad_x,$tmp);
		
		for( $i=0 ; $i<=$pas_y ; $i++){
			$tmp=$marge+$i*($long_axe_y/$pas_y);
			array_push($axe_y,$tmp);
			$tmp=$i*100/$pas_y;
			array_unshift($grad_y,$tmp);
		}
		$titre_x = $width/2;
		$titre_y = $marge/2;
		
		echo '	<svg width="'.$width.'" height="'.$height.'">
		<text x="'.$titre_x.'" y="'.$titre_y.'" fill="red">'.strtoupper($titre).'</text>
		<line x1="'.$xa.'" y1="'.$yb.'" x2="'.$xb.'" y2="'.$yb.'" style="stroke:rgb(0,0,0);stroke-width:2" />
		<line x1="'.$xa.'" y1="'.$ya.'" x2="'.$xa.'" y2="'.$yb.'" style="stroke:rgb(0,0,0);stroke-width:2" />';
		for( $i=0 ; $i<$pas_x ; $i++){
			echo '
		<line x1="'.$axe_x[$i].'" y1="'.$yb.'" x2="'.$axe_x[$i].'" y2="'.$yc.'" style="stroke:rgb(0,0,0);stroke-width:2" />
		<line x1="'.$axe_x[$i].'" y1="'.$ya.'" x2="'.$axe_x[$i].'" y2="'.$yb.'" style="stroke:rgb(0,0,0);stroke-dasharray: 5, 5" />
		<text x="'.$axe_x[$i].'" y="'.$height.'" fill="black">'.$grad_x[$i].'</text>';
			echo '
		<line x1="'.$axe_x[$i].'" y1="'.$moy2[$i].'" x2="'.$axe_x[$i+1].'" y2="'.$moy2[$i].$valide2[$i].' />';				//affichage de la moyenne
			$tmp = $axe_x[$i+1]-($axe_x[1]-$axe_x[0])/2;
			echo '
		<line x1="'.$tmp.'" y1="'.$q1_2[$i].'" x2="'.$tmp.'" y2="'.$q3_2[$i].$valide3[$i].'  />';				//affichage du bâton
		}
		for( $i=0 ; $i<$pas_y ; $i++){
			if(gettype($grad_y[$i]/10) == 'integer'){
			echo '
		<line x1="'.$xa.'" y1="'.$axe_y[$i].'" x2="'.$xc.'" y2="'.$axe_y[$i].'" style="stroke:rgb(0,0,0);stroke-width:2" />';
				if($grad_y[$i]==100){
					$grad_y[$i].='%';
				}
				echo '
		<line x1="'.$xa.'" y1="'.$axe_y[$i].'" x2="'.$xb.'" y2="'.$axe_y[$i].'" style="stroke:rgb(0,0,0);stroke-dasharray: 5, 5" />
		<text x="0" y="'.$axe_y[$i].'" fill="black">'.$grad_y[$i].'</text>';
			}
			else{
				echo '
		<line x1="'.$xa.'" y1="'.$axe_y[$i].'" x2="'.$xb.'" y2="'.$axe_y[$i].'" style="stroke:rgb(0,0,0);stroke-dasharray: 1, 5" />';
			}
		}
			echo '
		</svg>';
	}
	
	
?>