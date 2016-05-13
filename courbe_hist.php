<?php
	function courbe_radar ($mc , $datedeb , $datefin){
		$Tab=array(0,0,0,0,0,0,0);
		$j=0;
		try{$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root', 'root');}
		catch (Exception $e) {die('Erreur : ' . $e->getMessage() );}
		$sql = 'SELECT * FROM `post` WHERE `post` like "%'.$mc.'%" and `strtotime`>='.($datedeb).' and `strtotime`<='.($datefin);
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
		for($i=0;$i<7;$i++){$Tab[$i]/=$j;}
		$data[0] = "[0 , ".$Tab[1]."] , [1 , ".$Tab[2]."] , [2 , ".$Tab[3]."] , [3 , ".$Tab[4]."] , [4 , ".$Tab[5]."] , [5 , ".$Tab[6]."]";
		$data[1] = "[0 , ".$Tab[0]."] , [1 , ".$Tab[0]."] , [2 , ".$Tab[0]."] , [3 , ".$Tab[0]."] , [4 , ".$Tab[0]."] , [5 , ".$Tab[0]."]";
		
		return $data;
	}
	
	function historique ($mc , $date_deb , $date_fin){
		try{$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root', 'root');}
		catch (Exception $e) {die('Erreur : ' . $e->getMessage() );}
		$data_var;
		$sql = 'SELECT `auteur`, `date`, `heure`, `post` , ROUND(`polarity`,2) as `polarity`, ROUND(`joy`,2) as `joy`, 
		ROUND(`fear`,2) as `fear`, ROUND(`sadness`,2) as `sadness`, ROUND(`anger`,2) as `anger`, ROUND(`surprise`,2) as `surprise`, ROUND(`disgust`,2) as `disgust`
		FROM `post` 
		WHERE `post` like "%'.$mc.'%" 
		and `strtotime`>='.($date_deb).' 
		and `strtotime`<='.($date_fin).' 
		ORDER BY date DESC, heure DESC';
		$rep = $bdd->query($sql);
		while($data = $rep ->fetch()){
			$data_var .= "{";
			foreach($data as $key=>$value){
				if (gettype($key) != "integer"){
					$data_var .= $key.':"'.str_replace('"' , '\"' , $value).'" , ';
				}
			}
			$data_var = substr($data_var,0,-2)."},";
		}
		
		return substr($data_var, 0, -1);
	}

	function quadri ($pas , $x_min , $x_max, $titre){
		$marge=30;								$taille=5;
		$pas_x=$pas;							$pas_y=50;
		$width=1085;							$height=400;
		$long_axe_x=$width-2*$marge;			$long_axe_y=$height-2*$marge;
		$xa=$marge;								$ya=$marge;
		$xb=$width-$marge;						$yb=$height-$marge;
		$xc=$marge-$taille;						$yc=$yb+$taille;
		$axe_x=array();							$axe_y=array();
		$grad_x=array();						$grad_y=array();
		$rect_x=$long_axe_x/$pas_x;
		for( $i=0 ; $i<=$pas_x ; $i++){
			$tmp=$marge+$i*($long_axe_x/$pas_x);
			array_push($axe_x,$tmp);
			$tmp=round($x_min+$i*(($x_max-$x_min)/$pas_x),2)+1;
			array_push($grad_x, date("d-m-y" , $tmp) );
		}
		for( $i=0 ; $i<=$pas_y ; $i++){
			$tmp=$marge+$i*($long_axe_y/$pas_y);
			array_push($axe_y,$tmp);
			$tmp=$i*100/$pas_y;
			array_unshift($grad_y,$tmp);
		}
		$titre_x = $width/2;
		$titre_y = $marge/2;
		$data='<text x="'.$titre_x.'" y="'.$titre_y.'" fill="red">'.$titre.'</text><line x1="'.$xa.'" y1="'.$yb.'" x2="'.$xb.'" y2="'.$yb.'" style="stroke:rgb(0,0,0);stroke-width:2" /><line x1="'.$xa.'" y1="'.$ya.'" x2="'.$xa.'" y2="'.$yb.'" style="stroke:rgb(0,0,0);stroke-width:2" />';
		for( $i=0 ; $i<=$pas_x ; $i++){
			$data.='<line x1="'.$axe_x[$i].'" y1="'.$yb.'" x2="'.$axe_x[$i].'" y2="'.$yc.'" style="stroke:rgb(0,0,0);stroke-width:2" />';
			$data.='<line x1="'.$axe_x[$i].'" y1="'.$ya.'" x2="'.$axe_x[$i].'" y2="'.$yb.'" style="stroke:rgb(0,0,0);stroke-dasharray: 5, 5" />';
			$data.='<text x="'.($axe_x[$i]-30).'" y="'.($height-6).'" fill="black">'.$grad_x[$i].'</text>';
		}
		for( $i=0 ; $i<$pas_y ; $i++){
			if(gettype($grad_y[$i]/10) == 'integer'){
				$data.='<line x1="'.$xa.'" y1="'.$axe_y[$i].'" x2="'.$xc.'" y2="'.$axe_y[$i].'" style="stroke:rgb(0,0,0);stroke-width:2" />';
				if($grad_y[$i]==100){$grad_y[$i].='%';}
				$data.='<line x1="'.$xa.'" y1="'.$axe_y[$i].'" x2="'.$xb.'" y2="'.$axe_y[$i].'" style="stroke:rgb(0,0,0);stroke-dasharray: 5, 5" />';
				$data.='<text x="0" y="'.$axe_y[$i].'" fill="black">'.$grad_y[$i].'</text>';
			}
			else{
				$data.='<line x1="'.$xa.'" y1="'.$axe_y[$i].'" x2="'.$xb.'" y2="'.$axe_y[$i].'" style="stroke:rgb(0,0,0);stroke-dasharray: 1, 5" />';
			}
		}
		return $data;
	}

	function courbe_1($datedeb , $datefin , $nbbat , $mc , $sentiment , $color) {
		$pas = abs($datefin-$datedeb)/$nbbat;
		$Tab=array();
		for($i=0;$i<$nbbat;$i++){
			$Tab[$i]=array();
		}
		try{$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root', 'root');}
		catch (Exception $e) {die('Erreur : ' . $e->getMessage() );}
		$where_1 = '(`polarity` != 0    or `joy` != 0    or `fear` != 0    or `sadness` != 0    or `anger` != 0    or `surprise` != 0    or `disgust` != 0) and ';
		$sql = 'SELECT * FROM `post` WHERE '.$where_1.' `post` like "%'.$mc.'%" and `strtotime`>='.$datedeb.' and `strtotime`<='.$datefin.' order by `'.$sentiment.'`';
		//echo $sql;
		$rep = $bdd->query($sql);
		while ($ligne = $rep ->fetch()) {
			for($i=0;$i<$nbbat;$i++){
				$tmp = array();
				if($ligne['strtotime']>=$datedeb+$i*$pas and $ligne['strtotime']<=$datedeb+($i+1)*$pas){
					//echo $ligne[$sentiment];
					array_push($Tab[$i],$ligne[$sentiment]);
				}
			}
		}
		$Tabmoy=array();
		$Tabdecil1=array();
		$Tabdecil9=array();
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
		}
		$marge=30;
		$taille=5;
		$moy2=$Tabmoy;
		$pas_x=count($Tabmoy);
		$width=1085;
		$height=400;
		$long_axe_x=$width-2*$marge;
		$long_axe_y=$height-2*$marge;
		$axe_x=array();
		for( $i=0 ; $i<=$pas_x ; $i++){
			$tmp=$marge+($i+0.5)*($long_axe_x/$pas_x);
			array_push($axe_x,$tmp);
			$moy2[$i]=$height-$marge-($moy2[$i]*$long_axe_y);
		}
		
		$courbe = '<circle cx="'.$axe_x[0].'" cy="'.$moy2[0].'" r="2" style="stroke:'.$color.'" stroke-width="3" fill="'.$color.'"/>';	//affichage de la moyenne par un point			rgb(0,0,0)
		for( $i=1 ; $i<$pas_x ; $i++){
			$courbe .= '<line x1="'.$axe_x[$i-1].'" y1="'.$moy2[$i-1].'" x2="'.$axe_x[$i].'" y2="'.$moy2[$i].'" style="stroke:'.$color.'"  />';	//affichage du bâton
			$courbe .= '<circle cx="'.$axe_x[$i].'" cy="'.$moy2[$i].'" r="2" style="stroke:'.$color.'" stroke-width="3" fill="'.$color.'" />';	//affichage de la moyenne par un point			rgb(0,0,0)
		}
		return $courbe;
	}
	
	function courbe_2($datedeb , $datefin , $nbbat , $mc , $sentiment , $color , $titre) {
		$pas = abs($datefin-$datedeb)/$nbbat;
		$Tab=array();
		for($i=0;$i<$nbbat;$i++){
			$Tab[$i]=array();
		}
		try{$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root', 'root');}
		catch (Exception $e) {die('Erreur : ' . $e->getMessage() );}
		$where_1 = '(`polarity` != 0    or `joy` != 0    or `fear` != 0    or `sadness` != 0    or `anger` != 0    or `surprise` != 0    or `disgust` != 0) and ';
		$sql = 'SELECT * FROM `post` WHERE '.$where_1.' `post` like "%'.$mc.'%" and `strtotime`>='.$datedeb.' and `strtotime`<='.$datefin.' order by `'.$sentiment.'`';
		//echo $sql;
		$rep = $bdd->query($sql);
		while ($ligne = $rep ->fetch()) {
			for($i=0;$i<$nbbat;$i++){
				$tmp = array();
				if($ligne['strtotime']>=$datedeb+$i*$pas and $ligne['strtotime']<=$datedeb+($i+1)*$pas){
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
		$marge=30;
		$taille=5;
		$moy2=$Tabmoy;
		$pas_x=count($Tabmoy);
		$width=1085;
		$height=400;
		$long_axe_x=$width-2*$marge;
		$long_axe_y=$height-2*$marge;
		$axe_x=array();
		$valide=array();
		for( $i=0 ; $i<=$pas_x ; $i++){
			$tmp=$marge+($i+0.5)*($long_axe_x/$pas_x);
			array_push($axe_x,$tmp);
			$moy2[$i]=$height-$marge-($moy2[$i]*$long_axe_y);
			$Tabdecil1[$i]=$height-$marge-($Tabdecil1[$i]*$long_axe_y);
			$Tabdecil9[$i]=$height-$marge-($Tabdecil9[$i]*$long_axe_y);
			
			$rouge = ceil($color[0]*$Tabmoy[$i]);
			$vert  = ceil($color[1]*$Tabmoy[$i]);
			$bleu  = ceil($color[2]*$Tabmoy[$i]);
			
			array_push($valide , "rgb(".$rouge.",".$vert.",".$bleu.")");
		}
		$titre_x = $width/2;
		$titre_y = $marge/2;
		$courbe = '<text x="'.$titre_x.'" y="'.$titre_y.'" fill="rgb('.$color[0].','.$color[1].','.$color[2].')">'.$titre.'</text>';
		for( $i=0 ; $i<$pas_x ; $i++){
			$courbe .= '<line x1="'.$axe_x[$i].'" y1="'.$Tabdecil1[$i].'" x2="'.$axe_x[$i].'" y2="'.$Tabdecil9[$i].'" style="stroke:'.$valide[$i].'"  />';	//affichage du bâton
			$courbe .= '<circle cx="'.$axe_x[$i].'" cy="'.$moy2[$i].'" r="3" style="stroke:'.$color.'" stroke-width="3" fill="'.$valide[$i].'" />';			//affichage de la moyenne par un point			rgb(0,0,0)
		}
		return $courbe;
	}

?>