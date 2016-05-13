<?php
	function vect($Taba){
		$Tab1=array();
		$Tab1pol=array();
		$Tab1mot=array();
		$vect=array(0,0,0,0,0,0,0);
		$where=array();
		foreach($Taba as $v){
			if($v != null and ctype_alpha($v[0])){
				//echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$v.'<br/>';
				$tmp = 'word like "'.$v.' %" or word = "'.$v.'"';
				array_push($where , $tmp);
			}
		}
		$where = implode(" or " , $where);
		$sql = 'select * from dico where '.$where;
		
		$j=0;
		$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8', 'root', 'root');
		$rep = $bdd->query($sql);
		while ($ligne = $rep ->fetch()) {
			$Tab1[$j]=array();
			$Tab1pol[$j]=$ligne['polarity'];
			$Tab1[$j][0]=$ligne['joy'];
			$Tab1[$j][1]=$ligne['fear'];
			$Tab1[$j][2]=$ligne['sadness'];
			$Tab1[$j][3]=$ligne['anger'];
			$Tab1[$j][4]=$ligne['surprise'];
			$Tab1[$j][5]=$ligne['disgust'];
			$Tab1mot[$j]=$ligne['word'];
			if($Tab1pol[$j]=="positive"){
				$Tab1pol[$j]=1;
			}
			else{
				$Tab1pol[$j]=0;
			}
			$j++;
		}
		
		$compt=0;
		$comptpol=0;
		$k=0;
		while($k<count($Taba)){
			if(in_array($Taba[$k]." ".$Taba[$k+1]." ".$Taba[$k+2]." ".$Taba[$k+3],$Tab1mot)){
				$a=array_search($Taba[$k]." ".$Taba[$k+1]." ".$Taba[$k+2]." ".$Taba[$k+3],$Tab1mot);
				$vect[0]+=$Tab1pol[$a];
				for($i=0;$i<6;$i++){
					$vect[$i+1]+=$Tab1[$a][$i];
				}
				if(in_array(1,$Tab1[$a])){
					$compt++;
					}	
				$comptpol++;
				$k+=4;
			}
			elseif(in_array($Taba[$k]." ".$Taba[$k+1]." ".$Taba[$k+2],$Tab1mot)){
				$a=array_search($Taba[$k]." ".$Taba[$k+1]." ".$Taba[$k+2],$Tab1mot);
				$vect[0]+=$Tab1pol[$a];
				for($i=0;$i<6;$i++){
					$vect[$i+1]+=$Tab1[$a][$i];
				}
				if(in_array(1,$Tab1[$a])){
					$compt++;
				}	
				$comptpol++;
				$k+=3;
			}
			elseif(in_array($Taba[$k]." ".$Taba[$k+1],$Tab1mot)){
				$a=array_search($Taba[$k]." ".$Taba[$k+1],$Tab1mot);
				$vect[0]+=$Tab1pol[$a];
				for($i=0;$i<6;$i++){
					$vect[$i+1]+=$Tab1[$a][$i];
				}
				if(in_array(1,$Tab1[$a])){
					$compt++;
				}	
				$comptpol++;
				$k+=2;
			}
			elseif(in_array($Taba[$k],$Tab1mot)){
				$a=array_search($Taba[$k],$Tab1mot);
				$vect[0]+=$Tab1pol[$a];
				for($i=0;$i<6;$i++){
					$vect[$i+1]+=$Tab1[$a][$i];
				}
				if(in_array(1,$Tab1[$a])){
					$compt++;
				}	
				$comptpol++;
				$k++;
			}
			else{
				$k++;
			}
		}
		if($compt==0){$compt++;}
		if($comptpol==0){$comptpol++;}
		$vect[0]/=$comptpol;
		for($i=0;$i<6;$i++){
			$vect[$i+1]/=$compt;
		}
		
		$val=0;
		
		for($i=0;$i<7;$i++){
			$vect[$i] = $vect[$i]*100;
			if($i>0 && $vect[$i] != 0 ){
				$val=1;
			}
		}
		
		array_push($vect , $val);
		
		return $vect;
	}
?>
