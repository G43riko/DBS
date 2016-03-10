<?php

if(!defined("BASEPATH")) 
	exit("No direct script access allowed");

class Others extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this -> genres = array("genre_id" 	=> FALSE,//"ID",
								"name" 		=> "Názov",
								"d_created" => "Vytvorený",
								"movies" 	=> "Vo filmoch");
		$this -> countries = array("country_id" => FALSE,//"ID",
								   "name" 		=> "Názov",
								   "d_created"	=> "Vytvorený",
								   "movies" 	=> "Vo filmoch");
		$this -> tags = array("tag_id" 	=> FALSE,//"ID",
							  "name" 		=> "Názov",
							  "d_created"	=> "Vytvorený",
							  "movies"		=> "Vo filmoch");
		$this -> years = array("year" 		=> "Rok",
							   "movies"		=> "Natočené filmy");

		$this -> columns = array("movie_id" 	=> FALSE,//"ID",
								 "title" 		=> "Názov",
								 "title_sk" 	=> "SK názov",
								 "year" 		=> "Rok",
								 "length" 		=> "Dĺžka",
								 "rating" 		=> "Hodnotenie",
								 "genres" 		=> FALSE,
								 "tags" 		=> FALSE,//"Tagy",
								 "countries" 	=> FALSE,
								 "actors" 		=> FALSE,//"Herci",
								 "d_created" 	=> "Vytvorený",
								 "director" 	=> "Režisér",
								 "imdb_id" 		=> "IMDb ID");
	}

	public function genres($id = "all"){
		$this -> load -> model("movies_model");
		if($id == "all"):
			$data = $this -> movies_model -> getAllGenres();
			$this -> load -> view("other_view", array("data" 	=> $data,
													  "columns"	=> $this -> genres,
													  "title" 	=> "Genres",
													  "path"	=> "/movies/genres/"));
		else:
			$path = "/movies/years/";
			$data = $this -> movies_model -> getMoviesByGenre($id);
			foreach($data as $key => $val){
				$data[$key]["director"] = prepareData($val["director"], "/movies/makers/detail/");
				$data[$key]["year"] = wrapToTag($val["year"], "a", false, "href='" . $path . $val["year"] . "'");
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
													  "title" 	=> "Countries",
													  "path"	=> "/movies/countries/"));
		else:
			$path = "/movies/years/";
			$data = $this -> movies_model -> getMoviesByCountry($id);
			foreach($data as $key => $val){
				$data[$key]["director"] = prepareData($val["director"], "/movies/makers/detail/");
				$data[$key]["year"] = wrapToTag($val["year"], "a", false, "href='" . $path . $val["year"] . "'");
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
													  "title" 	=> "Tags",
													  "path"	=> "/movies/tags/"));
		else:
			$data = $this -> movies_model -> getMoviesByTag($id);
			$path = "/movies/years/";
			foreach($data as $key => $val){
				$data[$key]["director"] = prepareData($val["director"], "/movies/makers/detail/");
				$data[$key]["year"] = wrapToTag($val["year"], "a", false, "href='" . $path . $val["year"] . "'");
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
			$path = "/movies/years/";
			$data = $this -> movies_model -> getAllYears();

			foreach($data as $key => $val)
				$data[$key]["year"] = wrapToTag($val["year"], "a", false, "href='" . $path . $val["year"] . "'");

			$this -> load -> view("other_view", array("data" 	=> $data,
													  "columns"	=> $this -> years,
													  "title" 	=> "Tags",
													  "path"	=> "/movies/years/"));
		else:
			$data = $this -> movies_model -> getMoviesByYear($year);
			if($data){
				foreach($data as $key => $val)
					$data[$key]["director"] = prepareData($val["director"], "/movies/makers/detail/");
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