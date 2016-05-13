<?php
	function lemm ($a){
		$a = str_replace('"' , '' , $a);
		$mots = preg_split("/([ ])/", $a, -1, PREG_SPLIT_DELIM_CAPTURE);
		$mots = array_filter($mots); //On enlève tous les débrits d'array que l'on a créé en explosant le texte
		$a = implode(" " , $mots);
		//$a = utf8_decode($a);
		//$a = escapeshellcmd ($a);
		//echo "avant l'exec    ";
		//echo $a;
		$cmd= 'chcp 65001 | echo '.$a.' | perl C:\TreeTagger\cmd\utf8-tokenize.perl -f -a C:\TreeTagger\lib\french-abbreviations | C:\TreeTagger\bin\tree-tagger.exe C:\TreeTagger\lib\french-utf8.par -token -lemma -sgml -no-unknown';
		//echo $cmd;
		exec($cmd, $output , $retour);  
		//echo "out : ";print_r($output);
		//echo "après l'exec<br/>";
		$lemm = array();
		foreach($output as $key=>$value){
			$value = utf8_decode($value);
			//echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$value.'<br/>';
			$tmp = explode("	" , $value);
			if( strstr($tmp[2] , "être") != null ){
				$tmp[2] = "être";
			}
			else if(strstr($tmp[2] , "|") != null){
				$aze=explode( "|" , $tmp[2]);
				$tmp[2] = $aze[0];
			}
			array_push($lemm , $tmp[2]);
		}
		//print_r ($lemm);
		return $lemm;
	}
?>