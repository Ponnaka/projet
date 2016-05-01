<!DOCTYPE html> 
<html> 
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	
	<body>
	<?php	
		$tmp="j'ai tellement envie d'être en été, les soirées, les potes, la plage, les vacances, le camping, les rencontres, le soleil, c'est ouf";
		$cmd= 'chcp 65001 | echo '.$tmp.' | perl C:\TreeTagger\cmd\utf8-tokenize.perl -f -a C:\TreeTagger\lib\french-abbreviations | C:\TreeTagger\bin\tree-tagger.exe C:\TreeTagger\lib\french-utf8.par -token -lemma -sgml -no-unknown';
		$lemmatiseur=exec($cmd, $output);
		foreach($output as $key=>$value){
			$value = utf8_decode($value);
			$tmp = explode("	" , $value);
			echo $tmp[2].'<br/>';
		}
	?>
	
	</body>
</html>