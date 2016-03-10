<?php

if(!defined("BASEPATH")) 
	exit("No direct script access allowed");

class Movies extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this -> columns = array("movie_id" 	=> FALSE,//"ID",
								 "title" 		=> "Názov",
								 "title_sk" 	=> "SK názov",
								 "year" 		=> "Rok",
								 "length" 		=> "Dĺžka",
								 "rating" 		=> "Hodnotenie",
								 "genres" 		=> "Žáner",
								 "tags" 		=> FALSE,//"Tagy",
								 "countries" 	=> "Krajna pôvodu",
								 "actors" 		=> FALSE,//"Herci",
								 "d_created" 	=> "Vytvorený",
								 "director" 	=> "Režisér",
								 "imdb_id" 		=> "IMDb ID");
	}

	public function parse($id = "tt0114369"){
		$this -> load -> model("imdb_model");
		$data = $this -> imdb_model -> parse($id);
		$data["imdbId"] = $id;
		$data["hideHeader"] = 1;
		$data["hideFooter"] = 1;
		//pre_r($data);

		$this -> load -> view("movie_detail_view", $data);
	}

	public function search($name, $val = 1){
		$this -> load -> model("imdb_model");
		//pre_r($data);


		$names = array("title_popular" 		=> "Popular",
					   "title_exact"		=> "Exact",
					   "title_substring"	=> "Substring");

		
		
		$data = array("data"	=> get_object_vars($this -> imdb_model -> findMovie($name)),
					  "names"	=> $names,
					  "name"	=> $name,
					  "vypis"	=> 0,
					  "val"		=> $val,
					  "i" 		=> 0,
					  "link"	=> "http://www.imdb.com/title");
		$this -> load -> view("movies_search_view", $data);
		$vypis = $i = 0;
		
		//$this -> load -> model("movies_model");
		//pre_r($this -> movies_model -> getMovieByName($name));
	}

	public function add($id){
		//$id = "tt0114369"; //se7en
		//$id = "tt1663202"; //revenant
		//$id = "tt0095016"; //die hard
		//$id = "tt0241527"; //harry potter 1
		$this -> load -> model("movies_model");
		$this -> load -> model("imdb_model");
		$data = $this -> imdb_model -> parse($id);
		pre_r($data);
		$data["imdb_id"] = $id;
		$this -> movies_model -> addMovieArray($data);
	}

	public function searchDetail($imdb_id){
		$this -> load -> model("imdb_model");
		$data = $this -> imdb_model -> parse($imdb_id);
		pre_r($data);
		$this -> load -> view("movie_detail_view.php", $data);
	}

	public function detail($movieId){
		$this -> load -> model("movies_model");
		$data = $this -> movies_model -> getMovieById($movieId);

		$data["imdbId"] 	= $data["imdb_id"];
		$data["genres"] 	= prepareData($data["genres"], "/movies/genres/");
		$data["tags"] 		= prepareData($data["tags"], "/movies/tags/");
		$data["countries"] 	= prepareData($data["countries"], "/movies/countries/");
		$data["actors"] 	= prepareData($data["actors"], "/movies/makers/detail/");
		$data["director"] 	= prepareData($data["director"], "/movies/makers/detail/");
		
		if(!$data)
			die("nenašiel sa film s ID: " . $movieId);
		
		$this -> load -> view("movie_detail_view.php", $data);
	}

	public function index(){
		$this -> load -> model("movies_model");
		$data = $this -> movies_model -> getAllMovies();
		foreach($data as $key => $movie){
			$data[$key]["genres"] 		= prepareData($data[$key]["genres"], "/movies/genres/");
			$data[$key]["tags"] 		= prepareData($data[$key]["tags"], "/movies/tags/");
			$data[$key]["countries"] 	= prepareData($data[$key]["countries"], "/movies/countries/");
			$data[$key]["actors"] 		= prepareData($data[$key]["actors"], "/movies/makers/detail/");
			$data[$key]["director"] 	= prepareData($data[$key]["director"], "/movies/makers/detail/");
		}
		$this -> load -> view('movies_view.html', array("movies" => $data,
					  									"data"   => $this -> columns));
	}
}	