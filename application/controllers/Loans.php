<?php

if(!defined("BASEPATH")) 
	exit("No direct script access allowed");

class Loans extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this -> columns = array("loan_id" 		=> FALSE,//"Loan Id",
								 "first_name" 	=> word("firstName"),
								 "second_name" 	=> word("secondName"),
								 "d_created" 	=> word("created"),
								 "before" 		=> word("length"),
								 "d_returned"	=> word("returned"),
								 "movies"		=> word("movies"));
	}
	public function index(){
		$this -> load -> model("movies_model");
		$data = $this -> movies_model -> getAllLoans();
		if($data)
			foreach($data as $key => $loan){
				$data[$key]["movies"] = prepareData($loan["movies"], movieURL . "detail/");
				$date = ($loan["months"] ? $loan["months"] . " mesiacov, ": NULL);
				$date .= ($loan["days"] ? $loan["days"] . " dní, ": NULL);
				$date .= ($loan["hours"] ? $loan["hours"] . " hodín ": NULL);
				$data[$key]["before"] = $date;
			}
		$this -> load -> view("loans_view", array("loans" 	=> $data,
												  "data"	=> $this -> columns));
	}

	public function add(){
		$this -> load -> view("loan_add_view");
	}

	public function finish($id){

	}
}
