<?php

class Movies_model extends CI_Model {
	public function addDataToMovie($data, $table, $data_id, $movie_id){
		foreach($data as $id){
			$arr = array("movie_id"	=> $movie_id,
						 $data_id 	=> $id[$data_id]);
			if(!$this -> db -> where($arr) -> get("movies." . $table) -> num_rows())
				$this -> db -> insert("movies." . $table, $arr);
		}
	}

	public function getDataIn($select, $from, $where, $in){
		$sql = "SELECT $select
				FROM   $from 
				WHERE  $where IN (" . join(", ", $in). ")";
		return $this -> db -> query($sql);
	}



	/**************************************
	MAKERS
	**************************************/

	public function getAllMakers(){
		//SELECT * FROM movies.makers
		$q = $this -> db -> get("movies.makers_view");
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getMaker($id){
		if(is_numeric($id)):
			return $this -> getMakerById($id);
		else:
			return $this -> getMakerByName(urldecode($id));
		endif;
	}

	private function getMakerByName($name){
		//SELECT * FROM movies.makers WHERE name = 'N'
		$name = urldecode($name);
		$qe = $this -> db -> query("SELECT * 
									FROM   movies.makers_view
									WHERE  lower(name) = '" . lowerTrim($name) . "'");
		return $qe -> num_rows() ? $qe -> result_array() : false;
	}

	private function getMakerById($id){
		//SELECT * FROM movies.makers WHERE maker_id = ID
		$q = $this -> db ->get_where("movies.makers_view", array("maker_id" => $id));
		return $q -> num_rows() ? $q -> result_array()[0] : false;
	}
	/**************************************
	MOVIES
	**************************************/

	public function getAllMovies(){
		//SELECT * FROM movies.movies
		$q = $this -> db -> get("movies.movies_view");
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getMovieByImdbId($id){
		$q = $this -> db ->get_where("movies.movies", array("imdb_id" => $id));
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getMovieByName($name){
		//SELECT * FROM movies.movies WHERE title LIKE '%NAME%';
		$q = $this -> db -> like('title', $name) -> get("movies.movies");
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getMoviesByYear($year){
		//SELECT * FROM movies.movies WHERE year = YEAR;
		$q = $this -> db -> get_where("movies.movies_view", array("year" => $year));
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getMoviesByGenre($name){
		$name = urldecode($name);
		$sql = "SELECT mm.*
				FROM   movies.movies_view mm
				JOIN   movies.mtm_movie_genre mmg ON mm.movie_id = mmg.movie_id
				JOIN   movies.c_genres mcg ON mmg.genre_id = mcg.genre_id ";

		if(is_numeric($name))
			$sql .= "WHERE mcg.genre_id = $name";
		else
			$sql .= "WHERE lower(mcg.name) = '" . strtolower($name) . "' ";
		$q = $this -> db -> query($sql);
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getMoviesByCountry($name){
		$name = urldecode($name);
		$sql = "SELECT mm.*
				FROM   movies.movies_view mm
				JOIN   movies.mtm_movie_country mmg ON mm.movie_id = mmg.movie_id
				JOIN   movies.c_countries mcg ON mmg.country_id = mcg.country_id ";

		if(is_numeric($name))
			$sql .= "WHERE mcg.country_id = $name";
		else
			$sql .= "WHERE lower(mcg.name) = '" . strtolower($name) . "' ";
		$q = $this -> db -> query($sql);
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getMoviesByTag($name){
		$name = urldecode($name);
		$sql = "SELECT mm.*
				FROM   movies.movies_view mm
				JOIN   movies.mtm_movie_tag mmg ON mm.movie_id = mmg.movie_id
				JOIN   movies.c_tags mcg ON mmg.tag_id = mcg.tag_id ";

		if(is_numeric($name))
			$sql .= "WHERE mcg.tag_id = $name";
		else
			$sql .= "WHERE lower(mcg.name) = '" . strtolower($name) . "' ";
		$q = $this -> db -> query($sql);
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getMovieByNameAndYear($name, $year){
		//SELECT * FROM movies.movies WHERE title = 'T' AND year = 'Y';
		$sql = "SELECT  * 
				FROM    movies.movies 
				WHERE   lower(title) = '" . lowerTrim($this -> db -> escape_like_str($name)) . "'
						AND year  = " . $year;
		$qe = $this -> db -> query($sql);
		return $qe -> num_rows() ? $qe -> result_array() : false;
	}

	public function getMovieById($id){
		//SELECT * FROM movies.moveis WHERE movie_id = ID
		$q = $this -> db -> get_where("movies.movies_view", array("movie_id" => $id));
		return $q -> num_rows() ? $q -> result_array()[0] : false;
	}


	/**************************************
	CLEARS
	**************************************/

	/**************************************
	OTHERS
	**************************************/

	public function getAllYears(){
		$sql = "SELECT year, count(*) AS movies FROM movies.movies GROUP BY year";
		$q = $this -> db -> query($sql);
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getAllGenres(){
		$q = $this -> db -> get("movies.genres_view");
		return $q -> num_rows() ? $q -> result_array() : false;
	}


	public function getGenreById($id){
		$q = $this -> db -> get_where("movies.genres_view", array("genre_id" => $id));
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getGenreByName($name){
		$q = $this -> db -> query("SELECT * 
								   FROM movies.genres_view 
								   WHERE lower(name) = '" . strtolower($name) . "'");
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getAllCountries(){
		$q = $this -> db -> get("movies.countries_view");
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function getAllTags(){
		$q = $this -> db -> get("movies.tags_view");
		return $q -> num_rows() ? $q -> result_array() : false;
	}

	public function addMovieArray($data){
		//pozrie sa či film už neexistuje
	
		$result = $this -> getMovieByImdbId($data["imdb_id"]);
		
		$data["country"] 	= quotteArray($data["country"]);
		$data["actors"] 	= quotteArray($data["actors"]);
		$data["genres"] 	= quotteArray($data["genres"]);
		$data["tags"] 		= quotteArray($data["tags"]);

		$data["title"] = str_replace("'", "\"", $data["title"]);

		if($result)
			die("film " . $data["title"] . " (". $data["year"]. ") už existuje");
		//pridá film
		$arr = array("year" 	=> $data["year"], 
					 "title" 	=> $data["title"], 
					 "rating" 	=> $data["rating"], 
					 "length" 	=> $data["length"], 
					 "imdb_id" 	=> $data["imdb_id"]);

		if(isset($data["title_sk"]))
			$arr["title_sk"] = $data["title_sk"];

		$this -> db -> insert("movies.movies", $arr);

		//zisti ID filmu
		$movie_id = $this -> db -> select("movie_id") 
								-> where("imdb_id", $data["imdb_id"]) 
								-> get("movies.movies")
								-> result_array()[0]["movie_id"];

		//pridá režiséra

		$dir = $this -> getMakerByName($data["director"]);

		if(!$dir){
			$this -> db -> insert("movies.makers", array("name" => $data["director"]));
			$dir = $this -> getMakerByName($data["director"]);
		}
		$arr = array("maker_id" => $dir[0]["maker_id"],
					 "movie_id" => $movie_id,
					 "role"		=> "director");
		if(!$this -> db -> where($arr) -> get("movies.mtm_movie_maker") -> num_rows())
			$this -> db -> insert("movies.mtm_movie_maker", $arr);

		//pridá hercov

		foreach($data["actors"] as $name):
			$sql = "INSERT INTO movies.makers (name)
					SELECT $name
					WHERE  NOT EXISTS (
						SELECT name
						FROM   movies.makers
						WHERE  name = $name
					)
					returning maker_id, 'name'";
			$this -> db -> query($sql);
		endforeach;

		//pridá genre


		foreach($data["genres"] as $name):
			$sql = "INSERT INTO movies.c_genres (name)
					SELECT $name
					WHERE  NOT EXISTS (
						SELECT 'name'
						FROM   movies.c_genres
						WHERE  name = $name 
					)
					returning genre_id, 'name'";
			$this -> db -> query($sql);
		endforeach;

		//pridá krajny

		foreach($data["country"] as $name):
			$sql = "INSERT INTO movies.c_countries (name)
					SELECT $name
					WHERE  NOT EXISTS (
						SELECT 'name'
						FROM   movies.c_countries
						WHERE  name = $name 
					)
					returning country_id, 'name'";
			$this -> db -> query($sql);
		endforeach;

		//pridá tagy

		foreach($data["tags"] as $name):
			$sql = "INSERT INTO movies.c_tags (name)
					SELECT $name
					WHERE  NOT EXISTS (
						SELECT 'name'
						FROM   movies.c_tags
						WHERE  name = $name 
					)
					returning tag_id, 'name'";
			$this -> db -> query($sql);
		endforeach;

		//pridá k filmu genre

		$result = $this -> getDataIn("genre_id", "movies.c_genres", "name", $data["genres"]);
		$this -> addDataToMovie($result -> result_array(), "mtm_movie_genre", "genre_id", $movie_id);
		
		//pridá k filmu krajny

		$result = $this -> getDataIn("country_id", "movies.c_countries", "name", $data["country"]);
		$this -> addDataToMovie($result -> result_array(), "mtm_movie_country", "country_id", $movie_id);

		//pridá k filmu tagy
		
		$result = $this -> getDataIn("tag_id", "movies.c_tags", "name", $data["tags"]);
		$this -> addDataToMovie($result -> result_array(), "mtm_movie_tag", "tag_id", $movie_id);

		//pridá k filmu hercov
		
		$sql = "SELECT maker_id
				FROM   movies.makers
				WHERE  name IN (" . join(", ", $data["actors"]). ")";

		//$result = $this -> db -> query($sql); //presunuté priamo do foreachu
		foreach($this -> db -> query($sql) -> result_array() as $id){
			$arr = array("movie_id"	=> $movie_id,
						 "maker_id" => $id["maker_id"]);
			//ak už film nemá priradený tag
			if(!$this -> db -> where($arr) -> get("movies.mtm_movie_maker") -> num_rows())
				$this -> db -> insert("movies.mtm_movie_maker", $arr);
		}
	}
}