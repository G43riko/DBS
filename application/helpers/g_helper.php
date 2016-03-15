<?php
	define("movieURL", 		"/movies/movies/");
	define("genreURL", 		"/movies/genres/");
	define("yearURL", 		"/movies/years/");
	define("countryURL",	"/movies/countries/");
	define("makerURL", 		"/movies/makers/");
	define("loanURL", 		"/movies/loans/");
	define("tagURL", 		"/movies/tags/");
	define("loginURL", 		"/movies/login/");
	define("regURL", 		"/movies/register/");
	define("logoutURL", 	"/movies/logout/");	

	function pre_r($data){
		echo "<pre>";
		print_r($data);
		echo "<pre/>";
	}

	function getLang(){
		return "sk";
	}

	function showSimpleMovies($array, $coll, $path, $currency = ""){
		foreach($array as $val){
			$title = checkStringLength($val["title"]);
			$title = "<a href='" . $path . $val["movie_id"] . "'>" . $title . "</a>";
			wrapToTag(wrapToTag($title, "td") . wrapToTag($val[$coll] . $currency, "td"), "tr", TRUE);
		}
	}

	function checkStringLength($string, $num = 14, $append = "..."){
		if(strlen($string) > $num)
			$string = substr($string, 0, $num) . $append;
		return $string;
	}

	function word($word){
		$dictionary = array();
		$dictionary["addLoan"]["sk"] 		= "Pridať novú pôžičku";
		$dictionary["clearBasket"]["sk"] 	= "Vyprázdniť koší";
		$dictionary["countries"]["sk"] 		= "Krajny";
		$dictionary["email"]["sk"] 			= "Email";
		$dictionary["enterMovies"]["sk"]	= "Zadaj názov filmu";
		$dictionary["genres"]["sk"] 		= "Žánre";
		$dictionary["numOfMovies"]["sk"] 	= "Celková cena";
		$dictionary["register"]["sk"] 		= "Registrovať";
		$dictionary["logout"]["sk"] 		= "Odhlásiť";
		$dictionary["movies"]["sk"] 		= "Filmy";
		$dictionary["makers"]["sk"] 		= "Tvorcovia";
		$dictionary["loans"]["sk"] 			= "Pôžičky";
		$dictionary["login"]["sk"] 			= "Prihlásiť";
		$dictionary["tags"]["sk"] 			= "Tagy";
		$dictionary["years"]["sk"] 			= "Roky";
		$dictionary["firstName"]["sk"] 		= "Krsné meno";
		$dictionary["secondName"]["sk"] 	= "Priezvisko";
		$dictionary["pass"]["sk"] 			= "Heslo";
		$dictionary["totalPrice"]["sk"] 	= "Počet filmov";
		$dictionary["pass"]["sk"] 			= "Heslo";
		$dictionary["remove"]["sk"]			= "Odstrániť";
		$dictionary["title"]["sk"]			= "Názov";
		$dictionary["titleSK"]["sk"]		= "SK názov";
		$dictionary["year"]["sk"]			= "Rok";
		$dictionary["length"]["sk"]			= "Dĺžka";
		$dictionary["rating"]["sk"]			= "Hodnotenie";
		$dictionary["genres"]["sk"]			= "Žánre";
		$dictionary["countries"]["sk"]		= "Krajny";
		$dictionary["created"]["sk"]		= "Vytvorené";
		$dictionary["director"]["sk"]		= "Režisér";
		$dictionary["imdbID"]["sk"]			= "IMDb";
		$dictionary["returned"]["sk"]		= "Vrátené";
		$dictionary["name"]["sk"]			= "Meno";
		$dictionary["id"]["sk"]				= "ID";
		$dictionary["birthday"]["sk"]		= "Dátum narodenia";
		$dictionary["workOnMovies"]["sk"]	= "Pracoval na filmoch";
		$dictionary["inMovies"]["sk"]		= "Vo filmoch";
		$dictionary["popular"]["sk"]		= "Populárne";
		$dictionary["exact"]["sk"]			= "Presná zhoda";
		$dictionary["substring"]["sk"]		= "Čiastočná zoda";
		$dictionary["actors"]["sk"]			= "Herci";
		$dictionary["searchMovie"]["sk"]	= "Hladať filmy";
		$dictionary["addMovie"]["sk"]		= "Pridať film";
		$dictionary["searchMovies"]["sk"]	= "Vyhladávanie filmou";
		$dictionary["persons"]["sk"]		= "Uživatelov";
		$dictionary["undefinedWord"]["sk"]	= "Neznáme slovo";



		if(!isset($dictionary[$word]))
			return $dictionary["undefinedWord"][getLang()] . ": " . $word;

		return $dictionary[$word][getLang()];
	}

	function is_login(){
		return getSession("logged_in");
	}

	function getSession($string){
		$CI = & get_instance();
		return $CI -> session -> userdata($string);
	}

	function quotte($string, $quotte = "'"){
		if(isset($string) && !empty($string))
			return $quotte . $string . $quotte;
	}

	function quotteArray($data, $recursive = TRUE){
		$num = count($data);
		for($i=0 ; $i<$num ; $i++)
			if($recursive && is_array($data[$i]))
				$data[$i] = quotteArray($data[$i]);
			else
				$data[$i] = $actor = "'" . trim($data[$i]) . "'";
		return $data;
	}

	function nvl($in, $res){
		return is_null($in) ? $res : $in;
	}

	function echoArray($val, $delimiter, $show = FALSE){
		$res = is_array($val) ? join($delimiter, $val) : $val;
		if($show)
			echo $res;
		return $res;
	}

	function echoIf($val, $str = ""){
		if(isset($val))
			echo empty($str) ? $val : $str;
	}

	function makeLink($text, $link, $show = FALSE){
		$res = wrapToTag($text, "a", FALSE, "href='" . $link . "'");
		if($show)
			echo $res;
		return $res;
	}

	function prepareData($data, $path){
		$data = explode(", ", $data);
		foreach($data as $key => $val){
			$tmp = explode(":", $val);
			$data[$key] = wrapToTag($tmp[0], "a", false, "href='" . $path . $tmp[1] . "'");
		}
		return join(", ", $data);
	}

	function drawInputField($name, $id, $label, $type = "input"){
		$attributes = array("class" => "form-control", "id" => $id, "placeholder" => "Enter $name");
		echo '<div class="form-group">';
    	echo form_label($label . ":", $id);
    	switch($type):
    		case "input":
				echo form_input("$name", set_value("$name", ""), $attributes);
				break;
			case "password":
				echo form_password("$name", set_value("$name", ""), $attributes);
				break;
		endswitch;
		echo "</div>";
	}

	function wrapToTag($value, $tag, $show = false, $params = NULL){
		$res = "<$tag" . (is_null($params) ? " " : " " . $params) . ">" . $value . "</$tag>";
		if($show)
			echo $res;

		return $res;
	}

	function lowerTrim($string){
		return strtolower(trim($string));
	}