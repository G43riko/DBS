<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Updater extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this -> load -> model("imdb_model");
		$this -> load -> model("movies_model");

		$data = $this -> movies_model -> getMakersForUpdate();
		foreach($data as $maker){
			$id = $maker["imdb_id"];
			$res = $this -> imdb_model -> parseMaker($id);
			pre_r($res);
			echo "datum: " . $id ." <br/>";
			$this -> movies_model -> updateMaker($id, array("d_birthday"	=> $res["birthday"],
															"avatar"		=> $res["avatar"]));
		}
	}
}