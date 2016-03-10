<?php

class Imdb_model extends CI_Model {
	public function findMovie($name){
		$name = urlencode($name);
		$data = file_get_contents("http://www.imdb.com/xml/find?json=1&nr=1&tt=on&q=$name");
		return json_decode($data);
	}

	public function parse($id){
		include("simple_html_dom.php");
		$html = file_get_html('http://www.imdb.com/title/' . $id);

		$result = array("genres" 	=> array(),
						"tags" 		=> array(),
						"country" 	=> array(),
						"actors"	=> array());

		$subtext = $html -> find("div.title_wrapper")[0];
		foreach($html -> find('.itemprop') as $item){
			switch($item -> itemprop):
				case "keywords" :
					$result["tags"][] = $item -> innertext;
					break;
				case "actor" :
					$result["actors"][] = trim(str_replace("'", "", $item -> plaintext));
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
		$result["length"] = $line -> find("time[itemprop=duration]")[0] -> innertext;

		$tmp = explode("h", $result["length"]);
		$result["length"] = ((int)$tmp[0]) * 60;
		$result["length"] += ((int)str_replace("min", "", $tmp[1]));



		/***************
		YEAR
		***************/
		$result["year"] = $subtext -> find("h1 a")[0] -> innertext;


		/***************
		TITLE
		***************/
		$tmp = $subtext -> find("div.originalTitle");
		if(count($tmp))
			$result["title"] = explode("(", $tmp[0] -> plaintext)[0];

		$data = explode("(" . $result["year"] . ")", $subtext -> find("h1")[0] -> plaintext);

		if(!isset($result["title"]))
			$result["title"] = $data[0];
		else
			$result["title_sk"] = $data[0];


		/***************
		DIRECTOR
		***************/
		$result["director"] = trim($html -> find("span[itemprop=director] a")[0] -> plaintext);

		/***************
		RATING
		***************/
		$result["rating"] = $html -> find(".ratingValue span")[0] -> innertext;
		

		/***************
		COUNTRY
		***************/
		foreach($html -> find("#titleDetails div") as $item)
			if($item -> first_child() -> innertext == "Country:")
				$result["country"][] = $item -> children(1) -> innertext;
		
		
		return $result;
	}
	
	public function findMaker($name){
		$name = urlencode($name);
		$data = file_get_contents("http://www.imdb.com/xml/find?json=1&nr=1&nm=on&q=$name");
		return json_decode($data);
	}
}