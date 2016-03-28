<?php

if(!defined("BASEPATH")) 
	exit("No direct script access allowed");

class Others extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this -> load -> model("movies_model");

		$this -> genres = array("genre_id" 	=> FALSE,//"ID",
								"name" 		=> word("title"),
								"d_created" => word("created"),
								"movies" 	=> word("inMovies"));

		$this -> countries = array("country_id" => FALSE,//"ID",
								   "name" 		=> word("title"),
								   "d_created"	=> word("created"),
								   "movies" 	=> word("inMovies"));

		$this -> tags = array("tag_id" 		=> FALSE,//"ID",
							  "name" 		=> word("title"),
							  "d_created"	=> word("created"),
							  "movies"		=> word("inMovies"));

		$this -> years = array("year" 		=> word("year"),
							   "movies"		=> word("inMovies"));

		$this -> columns = array("movie_id" 	=> FALSE,//"ID",
								 "title" 		=> word("title"),
								 "title_sk" 	=> word("titleSK"),
								 "year" 		=> word("year"),
								 "length" 		=> word("length"),
								 "rating" 		=> word("rating"),
								 "genres" 		=> FALSE,
								 "tags" 		=> FALSE,//"Tagy",
								 "countries" 	=> FALSE,
								 "actors" 		=> FALSE,//"Herci",
								 "d_created" 	=> word("created"),
								 "director" 	=> word("director"),
								 "imdb_id" 		=> word("imdbID"));
	}

	public function genres($genre = "all", $name = ""){
		if($genre == "all")
			$this -> load -> view("other_view", array("data" 	=> $this -> movies_model -> getAllGenres(),
													  "columns"	=> $this -> genres,
													  "title" 	=> word("genres"),
													  "path"	=> genreURL));
		else
			$this -> show($this -> movies_model -> getMoviesByGenre($genre, $name), "genres/$genre/");
	}


	public function countries($country = "all", $name = ""){
		if($country == "all")
			$this -> load -> view("other_view", array("data" 	=> $this -> movies_model -> getAllCountries(),
													  "columns"	=> $this -> countries,
													  "title" 	=> word("countries"),
													  "path"	=> countryURL));
		else
			$this -> show($this -> movies_model -> getMoviesByCountry($country, $name), "countries/$country/");
	}

	public function tags($tag = "all", $name = ""){
		if($tag == "all")
			$this -> load -> view("other_view", array("data" 	=> $this -> movies_model -> getAllTags(),
													  "columns"	=> $this -> tags,
													  "title" 	=> word("tags"),
													  "path"	=> tagURL));
		else
			$this -> show($this -> movies_model -> getMoviesByTag($tag, $name), "tags/$tag/");
	}

	public function years($year = "all", $name = ""){
		if($year == "all")
			$this -> load -> view("other_view", array("data" 	=> $this -> movies_model -> getAllYears(),
													  "columns"	=> $this -> years,
													  "title" 	=> word("years"),
													  "path"	=> yearURL));
		else
			$this -> show($this -> movies_model -> getMoviesByYear($year, $name), "years/$year/");
	}

	private function show($data, $path){
		if($data)
			$data = prepareLocalData($data, array("year" 	 	 => yearURL,
												  "director"	 => makerDetailURL));

		$this -> load -> view('movies_view.html', array("movies" => $data,
				  										"data"   => $this -> columns,
				  										"path"	 => $path));
	}
}