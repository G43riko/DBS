<?php

if(!defined("BASEPATH")) 
	exit("No direct script access allowed");

class Makers extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this -> columns = array("maker_id" 	=> word("id"),
								 "name"			=> word("name"),
								 "d_birthday" 	=> word("birthday"),
								 "movies_num"	=> word("workOnMovies"),
								 "d_created"	=> word("created"));
	}

	public function index(){
		$this -> load -> model("movies_model");
		$this -> load -> view('makers_view.php', array("makers" => $this -> movies_model -> getAllMakers(),
					  								   "data"   => $this -> columns));
	}

	public function search($name, $val = 1){
		$this -> load -> model("imdb_model");
		$data = $this -> imdb_model -> findMaker($name);
		pre_r($data);

		echo "<table>";
		$link = "http://www.imdb.com/name";

		$names = array("name_popular" 	=> word("popular"),
					   "name_exact"		=> word("exect"),
					   "name_substring"	=> word("substring"));
		
		if(property_exists($data, "name_popular")):
			echo wrapToTag(wrapToTag(wrapToTag("Popular", "h3"),"td"),"tr");
			foreach($data -> name_popular as $row):
				$line = "<a target='_blank'href='$link/" . $row -> id . " '>" . $row -> name . " </a>";
				echo "<tr>". wrapToTag($line, "td");
				echo wrapToTag($row -> description, "td") . "</tr>";
			endforeach;
		endif;

		if(!property_exists($data, "name_popular") ||$val > 1 && property_exists($data, "name_exact")):
			echo wrapToTag(wrapToTag(wrapToTag("Exact", "h3"),"td"),"tr");
			foreach($data -> name_exact as $row):
				$line = "<a target='_blank'href='$link/" . $row -> id . " '>" . $row -> name . " </a>";
				echo "<tr>". wrapToTag($line, "td");
				echo wrapToTag($row -> description, "td") . "</tr>";
			endforeach;
		endif;
		
		if($val > 2 && property_exists($data, "name_substring")):
			echo wrapToTag(wrapToTag(wrapToTag("Substring", "h3"),"td"),"tr");
			foreach($data -> name_substring as $row):
				$line = "<a target='_blank'href='$link/" . $row -> id . " '>" . $row -> name . " </a>";
				echo "<tr>". wrapToTag($line, "td");
				echo wrapToTag($row -> description, "td") . "</tr>";
			endforeach;
		endif;
		echo "</table>";
	}

	public function detail($makerId){
		$this -> load -> model("movies_model");
		$data = $this -> movies_model -> getMaker($makerId);
		//pre_r($data);
		if(!$data)
			die("nenašiel sa maker s ID: " . $makerId);
		$this -> load -> view("maker_detail_view.php", $data);
	}
}