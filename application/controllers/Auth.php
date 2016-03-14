<?php

if(!defined("BASEPATH")) exit("No direct script access allowed");

class Auth extends CI_Controller {

	function register($page = ""){
		$this -> load -> model("persons_model");
		$this -> load -> library("form_validation");
		$w = word("firstName");
		$this -> form_validation -> set_rules(strtolower($w), $w, "trim|required");
		$w = word("secondName");
		$this -> form_validation -> set_rules(strtolower($w), $w, "trim|required");
		$w = word("email");
		$this -> form_validation -> set_rules(strtolower($w), $w, "trim|required|valid_email");
		$w = word("pass");
		$this -> form_validation -> set_rules(strtolower(word("pass")), $w, "trim|required|min_length[4]");	
		
		if($this -> form_validation -> run() && $this -> persons_model -> register())
			redirect("/" . $page);

		$this -> load -> view("register_view", array("page" => $page));
	}

	function login($page = ""){

		$this -> load -> model("persons_model");
		$this -> load -> library("form_validation");

		$w = word("email");
		$this -> form_validation -> set_rules(strtolower($w), $w, "trim|required");
		$w = word("pass");
		$this -> form_validation -> set_rules(strtolower(word("pass")), $w, "trim|required");	

		if($this -> form_validation -> run())
			if($this -> persons_model -> check()){
				$data = $this -> persons_model -> getUserData($_POST["email"])[0];
				$data["logged_in"] = 1;
				$this -> session -> set_userdata($data);
				redirect("/" . str_replace("_", "/", $page));
			}
			else
				echo "niesi zaregistrovaný";

		$this -> load -> view("login_view", array("page" => $page));
	}

	function logout($page = ""){
		$this -> session -> unset_userdata(array("first_name", 
												 "second_name", 
												 "person_id", 
												 "email", 
												 "password", 
												 "d_created", 
												 "d_birthday"));
		$this -> session -> set_userdata("logged_in", 0);
		redirect("/" . $page);
	}
}