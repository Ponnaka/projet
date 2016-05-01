<?php
	function uni_to_utf ($a){
		$a = str_replace('\n' , '' , $a);
		$a = str_replace('\/' , '/' , $a);
		$a = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
			return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UTF-16BE');
		}, $a);
		return $a;
	}
		
	function src_date ($a){
		$table = array();
		for ($i = 0; $i <= strlen($a); $i++) {
			if($a[$i] != ' '){$tmp.=$a[$i];}
			else{
				array_push($table , $tmp);
				$tmp = null;
			}
		}
		array_push($table , $tmp);
		$date = $table[5].'-';
		switch ($table[1]){
			case "Jan":		$date .= "01";		break;
			case "Feb":		$date .= "02";		break;
			case "Mar":		$date .= "03";		break;
			case "Apr":		$date .= "04";		break;
			case "May":		$date .= "05";		break;
			case "Jun":		$date .= "06";		break;
			case "Jul":		$date .= "07";		break;
			case "Aug":		$date .= "08";		break;
			case "Sep":		$date .= "09";		break;
			case "Oct":		$date .= "10";		break;
			case "Nov":		$date .= "11";		break;
			case "Dec":		$date .= "12";		break;
		}
		$date .= '-'.$table[2];
		return array($date , $table[3] , strtotime($date." ".$table[3]) );
	}
		
	function recup_tweet($mc){
		require_once('twitter_api/TwitterAPIExchange.php');	
		/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
		$settings = array(
			'oauth_access_token' => "4098963449-q0qkYEQxguoyGgBKX08RQ1KV042rGqGPRPlSxlN",
			'oauth_access_token_secret' => "RdB1PoQuKMacdLvw8pz74h1zTPMCDe3laQN6KArgi7F70",
			'consumer_key' => "rZMQDEcxkANRAY31VQ9pPtcPj",
			'consumer_secret' => "AaPBr26fm8YHPfy8ZxlHw8UjaIi3uYkEIbPn0FQbRweFQT4ETB"
		);
		//$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
		$url = "https://api.twitter.com/1.1/search/tweets.json";	
		$requestMethod = "GET";
		$getfield = '?q='.$mc.'&lang=fr';
		$twitter = new TwitterAPIExchange($settings);			
		$raw = $twitter->setGetfield($getfield)
					  ->buildOauth($url, $requestMethod)
					  ->performRequest();
		$raw = strstr($raw , 'metadata');
		$tab_tweet=array();
		while( $raw != null ){
			$raw = substr($raw , 8);
			$tmp = strstr($raw , 'metadata',true);
			array_push($tab_tweet , $tmp);
			$raw = strstr($raw , 'metadata');
		}
		unset($tab_tweet[count($tab_tweet)-1]);
		unset($tab_tweet[count($tab_tweet)-1]);
		//echo count($tab_tweet);
		
		$tab=array();
		$j=1;
		$bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root', 'root');
		foreach($tab_tweet as $key=>$value){
			//Recherche de la date et de l'heure
			$tweet = array();
			$a = strstr($value , '{"created_at":"');
			$tmp = substr($a,15,30);
			$date = src_date($tmp);
			array_push($tweet , $date);
			//Recherche du pseudo
			$a = strstr($value , '","location":"', true);
			$a = strstr($a , '"screen_name"');
			$nom = substr($a,strlen('screen_namee":"'));
			array_push($tweet , $nom);
			//Recherche du tweet
			$a = strstr($value , '","text":"');
			$a = strstr($a , '","entities":' , true);
			$text = uni_to_utf(substr($a,strlen('","text":"')));
			array_push($tweet , $text);
			array_push($tab , $tweet);
			
			$where = '`auteur` = "'.$tweet[1].'" and `date` = "'.$tweet[0][0].'" and `heure` = "'.$tweet[0][1].'" and `post` = "'.$tweet[2].'"';
			$sql = "SELECT * FROM `post` WHERE ".$where;
			$rep = $bdd->query($sql);
			$data = $rep->fetch();
			if($data == null){
				$tweet[2] = utf8_decode(utf8_encode($tweet[2]));
				$value = 'VALUES ("'.$tweet[1].'", "'.$tweet[0][0].'", "'.$tweet[0][1].'" ,"'.$tweet[2].'" ,"'.$tweet[0][2].'")';
				$sql = "INSERT INTO `post`( `auteur`, `date`, `heure`, `post`, `strtotime`) ".$value;
				//echo $sql;
				$rep = $bdd->query($sql);
				//echo '<br/><br/>';
			}
			unset($tweet);
			$j++;
		}
	}
	//recup_tweet('%23Prince');
	//recup_tweet($_POST['mc']);
	//recup_tweet('Bataclan');
	//recup_tweet('LoiTravail');
	//recup_tweet('NuitDebout');
	//recup_tweet('JoeyStarr');
	//recup_tweet('gillesverdez');
	//recup_tweet('TeamG1');
?>