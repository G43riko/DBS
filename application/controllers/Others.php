<?php

if(!defined("BASEPATH")) 
	exit("No direct script access allowed");

class Others extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this -> genres = array("genre_id" 	=> FALSE,//"ID",
								"name" 		=> word("title"),
								"d_created" => word("created"),
								"movies" 	=> word("inMovies"));
		$this -> countries = array("country_id" => FALSE,//"ID",
								   "name" 		=> word("title"),
								   "d_created"	=> word("created"),
								   "movies" 	=> word("inMovies"));
		$this -> tags = array("tag_id" 	=> FALSE,//"ID",
							  "name" 		=> word("title"),
							  "d_created"	=> word("created"),
							  "movies"		=> word("inMovies"));
		$this -> years = array("year" 		=> word("year"),
							   "movies"		=> word("inMovies"));

		$this -> columns = array("movie_id" 	=> FALSE,//"ID",
								 "title" 		=> word("title"),
								 "title_sk" 	=> word("titleSK"),
								 "year" 		=> word("year"),
								 "length" 		=> word("year"),
								 "rating" 		=> word("rating"),
								 "genres" 		=> FALSE,
								 "tags" 		=> FALSE,//"Tagy",
								 "countries" 	=> FALSE,
								 "actors" 		=> FALSE,//"Herci",
								 "d_created" 	=> word("created"),
								 "director" 	=> word("director"),
								 "imdb_id" 		=> word("imdbId"));
	}

	public function genres($id = "all"){
		$this -> load -> model("movies_model");
		if($id == "all"):
			$data = $this -> movies_model -> getAllGenres();
			$this -> load -> view("other_view", array("data" 	=> $data,
													  "columns"	=> $this -> genres,
													  "title" 	=> word("genres"),
													  "path"	=> genreURL));
		else:
			$data = $this -> movies_model -> getMoviesByGenre($id);
			foreach($data as $key => $val){
				$data[$key]["director"] = prepareData($val["director"], makerURL . "detail/");
				$data[$key]["year"] = wrapToTag($val["year"], "a", false, "href='" . yearURL . $val["year"] . "'");
			}
			if($data)
				$this -> load -> view('movies_view.html', array("movies" => $data,
						  										"data"   => $this -> columns));
			else
				echo "nenašli sa žiadny filmy žánru $id";
		endif;
	}

	public function countries($id = "all"){
		$this -> load -> model("movies_model");
		if($id == "all"):
			$this -> load -> view("other_view", array("data" 	=> $this -> movies_model -> getAllCountries(),
													  "columns"	=> $this -> countries,
													  "title" 	=> word("countries"),
													  "path"	=> countryURL));
		else:
			$data = $this -> movies_model -> getMoviesByCountry($id);
			foreach($data as $key => $val){
				$data[$key]["director"] = prepareData($val["director"], makerURL . "detail/");
				$data[$key]["year"] = wrapToTag($val["year"], "a", false, "href='" . yearURL . $val["year"] . "'");
			}
			
			if($data){
				$this -> load -> view('movies_view.html', array("movies" => $data,
						  										"data"   => $this -> columns));
			}
			else
				echo "nenašli sa žiadny filmy vyrobené v $id";
		endif;
	}

	public function tags($id = "all"){
		$this -> load -> model("movies_model");
		if($id == "all"):
			$this -> load -> view("other_view", array("data" 	=> $this -> movies_model -> getAllTags(),
													  "columns"	=> $this -> tags,
													  "title" 	=> word("tags"),
													  "path"	=> tagURL));
		else:
			$data = $this -> movies_model -> getMoviesByTag($id);
			foreach($data as $key => $val){
				$data[$key]["director"] = prepareData($val["director"], makerURL . "detail/");
				$data[$key]["year"] = wrapToTag($val["year"], "a", false, "href='" . yearURL . $val["year"] . "'");
			}
			if($data){
				$this -> load -> view('movies_view.html', array("movies" => $data,
						  										"data"   => $this -> columns));
			}
			else
				echo "nenašli sa žiadne filmy s tagom $id";
		endif;
	}
	public function years($year = "all"){
		$this -> load -> model("movies_model");
		if($year == "all"):
			$data = $this -> movies_model -> getAllYears();

			foreach($data as $key => $val)
				$data[$key]["year"] = wrapToTag($val["year"], "a", false, "href='" . yearURL . $val["year"] . "'");

			$this -> load -> view("other_view", array("data" 	=> $data,
													  "columns"	=> $this -> years,
													  "title" 	=> word("years"),
													  "path"	=> yearURL));
		else:
			$data = $this -> movies_model -> getMoviesByYear($year);
			if($data){
				foreach($data as $key => $val)
					$data[$key]["director"] = prepareData($val["director"], makerURL . "detail/");
				$tmp = $this -> columns;
				$tmp["year"] = FALSE;
				$this -> load -> view('movies_view.html', array("movies" => $data,
						  										"data"   => $tmp));
			}
			else
				echo "nenašli sa žiadne filmy vyrobene v roku $year";
		endif;
	}
}