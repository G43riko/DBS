<?php

if(!defined("BASEPATH")) 
	exit("No direct script access allowed");

class Movies extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this -> columns = array("movie_id" 	=> FALSE,//"ID",
								 "title" 		=> word("title"),
								 "title_sk" 	=> word("titleSK"),
								 "year" 		=> word("year"),
								 "length" 		=> word("length"),
								 "rating" 		=> word("rating"),
								 "genres" 		=> word("genres"),
								 "tags" 		=> FALSE,//"Tagy",
								 "countries" 	=> word("countries"),
								 "actors" 		=> FALSE,//"Herci",
								 "d_created" 	=> word("created"),
								 "director" 	=> word("director"),
								 "imdb_id" 		=> word("imdbID"));
	}

	public function parse($id = "tt0114369"){
		$this -> load -> model("imdb_model");
		$data = $this -> imdb_model -> parse($id);
		$data["imdbId"] = $id;
		$data["hideHeader"] = 1;
		$data["hideFooter"] = 1;
		pre_r($data);

		$this -> load -> view("movie_detail_view", $data);
	}

	public function searchInDb($name){
		$this -> load -> model("movies_model");
		$data = $this -> movies_model -> getMovieByName($name);
		if($data):
			//pre_r($data);
			echo "<ul class='list-group'>";
			foreach($data as $value):
				echo "<li alt='" . $value["movie_id"] . "' onclick='addMovie(this)' class='glist list-group-item' style='hover:'>";
				echo $value["title"] . "(" . $value["year"] . ")";
				echo "</li>";
			endforeach;
			echo "</ul>";
		else:
			echo "<ul class='list-group'><li class='list-group-item'>No results</	li></ul>";
		endif;
	}

	public function search($name = "", $val = 1){
		$this -> load -> model("imdb_model");
		//pre_r($data);

		$names = array("title_popular" 		=> "Popular",
					   "title_exact"		=> "Exact",
					   "title_substring"	=> "Substring");
		$name = urldecode($name);
		$data = empty($name) ? array() : get_object_vars($this -> imdb_model -> findMovie($name));

		$data = array("data"	=> $data,
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
		
		$movies = array("tt0114369", "tt1663202", "tt0095016", "tt0241527", 
						"tt0137523", "tt0482571", "tt0401792", "tt0208092", 
						"tt1853728", "tt0110912", "tt1431045", "tt0365748", 
						"tt0109830");
		
		$this -> load -> model("movies_model");
		$this -> load -> model("imdb_model");
		foreach($movies as $movie){
			$id = $movie;
			$data = $this -> imdb_model -> parse($id);
			pre_r($data);
			$data["imdb_id"] = $id;
			$this -> movies_model -> addMovieArray($data);
		}
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
		$data["genres"] 	= prepareData($data["genres"], genreURL);
		$data["tags"] 		= prepareData($data["tags"], tagURL);
		$data["countries"] 	= prepareData($data["countries"], countryURL);
		$data["actors"] 	= prepareData($data["actors"], makerURL . "detail/");
		$data["director"] 	= prepareData($data["director"], makerURL . "detail/");
		$data["year"] 		= makeLink($data["year"], yearURL . $data["year"]);
		
		if(!$data)
			die("nenašiel sa film s ID: " . $movieId);
		
		$this -> load -> view("movie_detail_view.php", $data);
	}

	public function index(){
		$this -> load -> model("movies_model");
		$data = $this -> movies_model -> getAllMovies();
		foreach($data as $key => $movie){
			$data[$key]["genres"] 		= prepareData($movie["genres"], genreURL);
			$data[$key]["tags"] 		= prepareData($movie["tags"], tagURL);
			$data[$key]["countries"] 	= prepareData($movie["countries"], countryURL);
			$data[$key]["actors"] 		= prepareData($movie["actors"], makerURL . "detail/");
			$data[$key]["director"] 	= prepareData($movie["director"], makerURL . "detail/");
			$data[$key]["year"] 		= makeLink($movie["year"], yearURL . $movie["year"]);
		}
		$this -> load -> view('movies_view.html', array("movies" => $data,
					  									"data"   => $this -> columns));

	}
}	