<?php

if(!defined("BASEPATH")) exit("No direct script access allowed");

class Auth extends CI_Controller {

	function register(){
		$this -> load -> model("persons_model");
		$this -> load -> library("form_validation");

		$this -> form_validation -> set_rules("meno", "Meno", "trim|required");
		$this -> form_validation -> set_rules("priezvisko", "Priezvisko", "trim|required");
		$this -> form_validation -> set_rules("email", "Email", "trim|required|valid_email");
		$this -> form_validation -> set_rules("heslo", "Heslo", "trim|required|min_length[4]");	
		
		if($this -> form_validation -> run() && $this -> persons_model -> register())
			redirect("/movies");

		$this -> load -> view("register_view");
	}

	function login(){
		$this -> load -> model("persons_model");
		$this -> load -> library("form_validation");

		$this -> form_validation -> set_rules("email", "Email", "trim|required");
		$this -> form_validation -> set_rules("heslo", "Heslo", "trim|required");

		if($this -> form_validation -> run())
			if($this -> persons_model -> check()){
				$data = $this -> persons_model -> getUserData($_POST["email"])[0];
				$data["logged_in"] = true;
				$this -> session -> set_userdata($data);
				redirect("/movies");
			}
			else
				echo "niesi zaregistrovaný";

		$this -> load -> view("login_view");
	}

	function logout(){
		$this -> session -> unset_userdata(array("first_name", "second_name", "person_id", 
												 "email", "password", "d_created", "d_birthday"));
		$this -> session -> set_userdata("logged_in", 0);
		redirect("/login");
	}
}