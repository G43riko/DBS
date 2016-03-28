<?php

if(!defined("BASEPATH")) 
	exit("No direct script access allowed");

class Movies extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this -> load -> model("imdb_model");
		$this -> load -> model("movies_model");

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


		$this -> linksArray = array("countries"	=> countryURL,
									"genres" 	=> genreURL,
									"year" 		=> yearURL,
									"tags"		=> tagURL,
									"actors"	=> makerDetailURL,
									"director" 	=> makerDetailURL);
	}

	public function debug(){
		$debug = debug_backtrace();
		//pre_r($debug["line"]);
	}

	public function parse($id){
		$data = $this -> imdb_model -> parse($id);
		$data["imdb_id"] = $id;
		$data["hideHeader"] = 1;
		$data["hideFooter"] = 1;
		$this -> load -> view("movie_detail_view", $data);
	}

	public function search($q = ""){
		if(empty($q))
			$this -> index();
		else{
			$data = $this -> movies_model -> getSearchMovies($q);
			
			if($data)
				$data = prepareLocalData($data, $this -> linksArray);

			$this -> load -> view('movies_view.html', array("movies" => $data,
						  									"data"   => $this -> columns,
						  									"search" => $q));
		}
	}

	public function test(){
		pre_r($this -> movies_model -> getAllDirectors());
	}

	public function searchIMDB($name = "", $val = 1){
		$names = array("title_popular" 		=> word("popular"),
					   "title_exact"		=> word("exact"),
					   "title_substring"	=> word("substring"));
		$name = urldecode($name);
		$data = empty($name) ? array() : get_object_vars($this -> imdb_model -> findMovie($name));

		if($data)
			foreach($data["title_popular"] as $key => $value)
				if($movie = $this -> movies_model -> getMovieByImdbId($value -> id))
					$data["title_popular"][$key] -> dbId = $movie[0]["movie_id"];
			
		$this -> load -> view("movies_search_view", array("data"	=> $data,
														  "names"	=> $names,
														  "name"	=> $name,
														  "vypis"	=> 0,
														  "val"		=> $val,
														  "i" 		=> 0,
														  "link"	=> imdbMovieURL));
	}

	public function add($id){
		$data = $this -> imdb_model -> parse($id);
		$data["imdb_id"] = $id;

		$this -> movies_model -> addMovieArray($data);
		redirect("/");
	}

	public function addArray($ides){
		foreach($ides as $id){
			$data = $this -> imdb_model -> parse($id);
			$data["imdb_id"] = $id;
			$this -> movies_model -> addMovieArray($data);
		}
		redirect("/");
	}

	public function searchDetail($imdb_id){
		$data =  $this -> imdb_model -> parse($imdb_id);
		$this -> load  -> view("movie_detail_view.php", $data);
	}

	public function detail($movieId, $hideHeadAndFoot = 0){
		$data  = $this  -> movies_model -> getMovieById($movieId);
		if(!$data)
			die("nenašiel sa film s ID: " . $movieId);

		$data = prepareLocalData($data, $this -> linksArray, 0);
		
		if($hideHeadAndFoot)
			$data["hideHeader"] = $data["hideFooter"] = 1;

		$this -> load -> view("movie_detail_view.php", $data);
	}

	public function edit($movieId, $hideHeadAndFoot = 0){
		$data  = $this  -> movies_model -> getMovieById($movieId);
		if(!$data)
			die("nenašiel sa film s ID: " . $movieId);
		
		/*
		$dir = explode(":", $data["director"]);
		$data["director"]  = "<input type='text' name='director' class='form-control' value='";
		$data["director"] .= $dir[0] . "' alt='" . $dir[1] . "'>";

		*/
		$data["director"] = $this -> prepareEditableData($data["director"], "maker_id", 
														 $this  -> movies_model -> getAllDirectors());
	
		$data["tags"] = $this -> prepareEditableData($data["tags"], "tag_id",
													 $this -> movies_model -> getAllTags());	

		$data["genres"] = $this -> prepareEditableData($data["genres"], "genre_id", 
													   $this  -> movies_model -> getAllGenres());

		$data["countries"] = $this -> prepareEditableData($data["countries"], "country_id", 
													 	  $this  -> movies_model -> getAllCountries());

		$data["actors"] = $this -> prepareEditableData($data["actors"], "maker_id", 
													   $this  -> movies_model -> getAllMakers());

		if($hideHeadAndFoot)
			$data["hideHeader"] = $data["hideFooter"] = 1;

		$this -> load -> view("movie_detail_edit.php", $data);
	}

	private function prepareEditableData($data, $id, $allData){
		$tmp = array();
		foreach($allData as $maker)
			$tmp[$maker[$id]] = array("name" => /*strtolower*/($maker["name"]), "exist" => "");

		$data = explode(",", $data);
		foreach($data as $mainKey => $val)
			$tmp[explode(":", $val)[1]]["exist"] = "selected='selected'";

		$data = array();
		foreach($tmp as $key => $val)
			$data[] = "<option value='$key' " . $val["exist"] . ">" . $val["name"] . "</option>";

		return $data;
	}

	public function updateMovie(){
		$this->db->trans_start();

		$id = $this -> movies_model -> createUpdatedMovie($_REQUEST["movie_id"], $_REQUEST);
		$this -> movies_model -> updateMovie($_REQUEST["movie_id"], array("replaced_by" => $id));

		$this -> db -> trans_complete();
		redirect("movies/detail/" . $id);
	}

	public function index(){
		$data = $this -> movies_model -> getAllMovies();
		if($data)
			$data = prepareLocalData($data, $this -> linksArray);

		$this -> load -> view('movies_view.html', array("movies" => $data,
					  									"data"   => $this -> columns));

	}

	public function searchInDb($name){
		$data = $this -> movies_model -> getSearchMovies($name);
		if($data):
			echo "<ul class='list-group'>";
			foreach($data as $value):
				$a = "alt='" . $value["movie_id"] . "' onclick='addMovie(this)' class='glist list-group-item'";
				$text = $value["title"] . "(" . $value["year"] . ")";
				wrapToTag($text, "li", 1, $a);
			endforeach;
			echo "</ul>";
		else:
			echo "<ul class='list-group'><li class='list-group-item'>" . word("noResults") . "</li></ul>";
		endif;
	}
}	