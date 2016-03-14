<?php

class Imdb_model extends CI_Model {
	public function findMovie($name){
		$name = urlencode($name);
		$data = file_get_contents("http://www.imdb.com/xml/find?json=1&nr=1&tt=on&q=$name");
		return json_decode($data);
	}

	public function parseMaker($id){
		include_once("simple_html_dom.php");
		$html = file_get_html('http://www.imdb.com/name/' . $id);

		$result = array();

		$r = $html -> find("#overview-top h1 span");
		if(isset($r[0]))
			$result["name"] = $r[0] -> innertext;

		$r = $html -> find("time");
		if(isset($r[0]))
			$result["birthday"] = $r[0] -> datetime;

		$r = $html -> find("#name-poster");
		if(isset($r[0]))
			$result["avatar"] = $r[0] -> src;

		return $result;
	}

	public function parse($id){
		include_once("simple_html_dom.php");
		$html = file_get_html('http://www.imdb.com/title/' . $id);

		$result = array("genres" 	=> array(),
						"tags" 		=> array(),
						"countries" 	=> array(),
						"actors"	=> array());

		$subtext = $html -> find("div.title_wrapper")[0];
		foreach($html -> find('.itemprop') as $item){
			switch($item -> itemprop):
				case "keywords" :
					$result["tags"][] = $item -> innertext;
					break;
				case "actor" :
					$id = $item -> find("a");
					if(isset($id[0]))
						$id = $this -> cutImdbId($id[0] -> href);
					else
						$id = "";
					$actor  = array("name" 		=> trim(str_replace("'", "", $item -> plaintext)),
									"imdb_id"	=> $id);
					$result["actors"][] = $actor;
					break;
			endswitch;
		}


		$line = $subtext -> find("div.subtext")[0];

		/***************
		CONTENT_RATING
		***************/
		$result["contentRating"] = $line -> find("meta[itemprop=contentRating]");
		if(count($result["contentRating"]) > 0)
			$result["contentRating"] = $result["contentRating"][0] -> content;
		else
			unset($result["contentRating"]);

		/***************
		GENRES
		***************/
		foreach($line -> find("span[itemprop=genre]") as $genre)
			$result["genres"][] = $genre -> innertext;

		/***************
		LENGTH
		***************/
		$r = $line -> find("time[itemprop=duration]");
		
		if(isset($r[0])):
			$result["length"] = $r[0] -> innertext;

			$tmp = explode("h", $result["length"]);
			$result["length"] = ((int)$tmp[0]) * 60;
			$result["length"] += ((int)str_replace("min", "", $tmp[1]));
		endif;


		/***************
		YEAR
		***************/
		$r = $subtext -> find("h1 a");
		if(isset($r[0]))
			$result["year"] = $r[0] -> innertext;


		/***************
		TITLE
		***************/
		$tmp = $subtext -> find("div.originalTitle");
		if(count($tmp))
			$result["title"] = trim(explode("(", $tmp[0] -> plaintext)[0]);
		$data = explode("(" . $result["year"] . ")", $subtext -> find("h1")[0] -> plaintext);

		if(!isset($result["title"]))
			$result["title"] = trim($data[0]);
		else
			$result["title_sk"] = trim($data[0]);


		/***************
		DIRECTOR
		***************/
		$r = $html -> find("span[itemprop=director] a");
		if(isset($r[0])){
			$result["director"] = trim($r[0] -> plaintext);
			$result["director_imdb_id"] = $this -> cutImdbId($r[0] -> href);
		}
		/***************
		POSTER
		***************/
		$r = $html -> find("div.poster img");
		if(isset($r[0]))
			$result["poster"] = $r[0] -> src;

		/***************
		RATING
		***************/
		$r = $html -> find(".ratingValue span");
		if(isset($r[0]))
			$result["rating"] = $r[0] -> innertext;
		

		/***************
		COUNTRY
		***************/
		foreach($html -> find("#titleDetails div") as $item)
			if($item -> first_child() -> innertext == "Country:")
				$result["country"][] = $item -> children(1) -> innertext;
		
		
		return $result;
	}

	private function cutImdbId($href){
		return explode("?", str_replace("/name/", "", $href))[0];
	}
	
	public function findMaker($name){
		$name = urlencode($name);
		$data = file_get_contents("http://www.imdb.com/xml/find?json=1&nr=1&nm=on&q=$name");
		return json_decode($data);
	}
}