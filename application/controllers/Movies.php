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
								 "title_sk" 	=> FALSE,//word("titleSK"),
								 "year" 		=> word("year"),
								 "length" 		=> word("length"),
								 "rating" 		=> word("rating"),
								 "genres" 		=> word("genres"),
								 "tags" 		=> FALSE,//"Tagy",
								 "countries" 	=> FALSE,//word("countries"),
								 "actors" 		=> FALSE,//"Herci",
								 "d_created" 	=> FALSE,//word("created"),
								 "director" 	=> word("director"),
								 "imdb_id" 		=> word("imdbID"));


		$this -> linksArray = array("countries"	=> countryURL,
									"genres" 	=> genreURL,
									"year" 		=> yearURL,
									"tags"		=> tagURL,
									"actors"	=> makerDetailURL,
									"director" 	=> makerDetailURL);
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

	public function searchIMDB($name = "", $val = 1){
		$names = array("title_popular" 		=> word("popular"),
					   "title_exact"		=> word("exact"),
					   "title_substring"	=> word("substring"));
		$name = urldecode($name);
		$data = empty($name) ? array() : get_object_vars($this -> imdb_model -> findMovie($name));

		if($data && isset($data["title_popular"]))
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

	public function add($id, $redirect = true, $csfd_id = false){
		$data = $this -> imdb_model -> parse($id);
		$data["imdb_id"] = $id;

		if($csfd_id)
			$data["csfd_id"] = $csfd_id;

		$id = $this -> movies_model -> addMovieArray($data);
		if($redirect)
			$this -> detail($id);
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

	public function edit($movieId = 0, $hideHeadAndFoot = 0){
		if(!$movieId){
			$arr = array("title" => "Názov filmu",
						 "title_sk" => "Slovenský názov",
						 "year" => 1800,
						 "rating" => 0,
						 "length" => 0,
						 "movie_id" => 0,
						 "imdb_id" => "");

			$arr["director"] = $this -> prepareEditableData("", "maker_id", $this  -> movies_model -> getAllDirectors());
			$arr["tags"] = $this -> prepareEditableData("", "tag_id",$this -> movies_model -> getAllTags());	
			$arr["genres"] = $this -> prepareEditableData("", "genre_id", $this  -> movies_model -> getAllGenres());
			$arr["countries"] = $this -> prepareEditableData("", "country_id", $this  -> movies_model -> getAllCountries());
			$arr["actors"] = $this -> prepareEditableData("", "maker_id", $this  -> movies_model -> getAllActors());
			$this -> load -> view("movie_detail_edit.php", $arr);
			return;
		}

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
													   $this  -> movies_model -> getAllActors());

		if($hideHeadAndFoot)
			$data["hideHeader"] = $data["hideFooter"] = 1;

		$this -> load -> view("movie_detail_edit.php", $data);
	}

	private function prepareEditableData($data, $id, $allData){
		$tmp = array();
		foreach($allData as $maker)
			$tmp[$maker[$id]] = array("name" => /*strtolower*/($maker["name"]), "exist" => "");

		if(!empty($data)){
			$data = explode(",", $data);
			foreach($data as $mainKey => $val)
				if(strpos($val, ":::") !== false)
					$tmp[explode(":::", $val)[1]]["exist"] = "selected='selected'";
		}
		$data = array();
		foreach($tmp as $key => $val)
			$data[] = "<option value='$key' " . $val["exist"] . ">" . $val["name"] . "</option>";

		return $data;
	}

	public function updateMovie(){
		$this -> db -> trans_start();
		if(!$_REQUEST["movie_id"]){
			$id = $this -> movies_model -> createNewMovie($_REQUEST);
			$this -> db -> trans_complete();
			redirect("movies/detail/" . $id);
			return;
		}


		$id = $this -> movies_model -> createUpdatedMovie($_REQUEST["movie_id"], $_REQUEST);
		$this -> movies_model -> deleteMovie($_REQUEST["movie_id"]);
		//$this -> movies_model -> updateMovie($_REQUEST["movie_id"], $_REQUEST);

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

	public function delete($id, $delete = false){
		if($delete == $id){
			$data = $this -> movies_model -> deleteMovie($id);
			redirect("/");
		}
		redirect("/movies/detail/$id");
	}

	public function posters($numY = 4, $numX = 6){
		$this -> load -> model("statistics_model");
		$arr = array("movies" 	=> $this -> statistics_model -> getNBestMovies($numX * $numY),
					 "numX" 	=> $numX,
					 "numY" 	=> $numY);
		$this -> load -> view("movies_posters_view", $arr);
	}	

	public function searchInDb($name){
		$data = $this -> movies_model -> getSearchMovies($name);
		if($data):
			echo "<ul class='list-group'>";
			foreach($data as $value):
				$a = "alt='" . $value["movie_id"] . "' price='" . $value["value"];
				$a .= "' onclick='addMovie(this, 1)' class='glist list-group-item'";
				$text = $value["title"] . "(" . $value["year"] . ")";
				wrapToTag($text, "li", 1, $a);
			endforeach;
			echo "</ul>";
		else:
			echo "<ul class='list-group'><li class='list-group-item'>" . word("noResults") . "</li></ul>";
		endif;
	}

	/*
	public function addByCSFD($csfd_id){
		$this -> load -> model("csfd_model");
		$data = $this -> csfd_model -> getMovieInfo($csfd_id);
		pre_r($data);
		if(isset($data["imdb_id"]))
			$this -> add($data["imdb_id"], false, $csfd_id);
	}

	public function addMovie($num = 1){
		for($i=0 ; $i<$num ; $i++){
			$data = $this -> movies_model -> getMovieToAdd();
			pre_r($data);
			if($data)
				$this -> addByCSFD($data[0]["csfd_id"]);
		}
	}

	public function decode($id){
		$content = get_headers("http://www.csfd.cz/film/" . $id);
		$url = explode(": ", $content[19])[1];
		echo gzdecode(file_get_contents($url));
	}

	public function decode2($name){
		$name = str_replace(" ", "+", urldecode($name));
		echo file_get_contents("http://www.csfd.cz/hledat/?q=$name");
	}
	
	public function addArray($ides, $redirect = true){
		foreach($ides as $id){
			$data = $this -> imdb_model -> parse($id);
			$data["imdb_id"] = $id;
			$this -> movies_model -> addMovieArray($data);
		}
		if($redirect)
			redirect("/");
	}
	*/
}	