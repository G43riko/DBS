<?php
	function pre_r($data){
		echo "<pre>";
		print_r($data);
		echo "<pre/>";
	}

	function quotteArray($data, $recursive = TRUE){
		$num = count($data);
		for($i=0 ; $i<$num ; $i++)
			if($recursive && is_array($data[$i]))
				$data[$i] = quotteArray($data[$i]);
			else
				$data[$i] = $actor = "'" . trim($data[$i]) . "'";
		return $data;
	}

	function echoArray($val, $delimiter, $show = FALSE){
		$res = is_array($val) ? join($delimiter, $val) : $val;
		if($show)
			echo $res;
		return $res;
	}

	function echoIf($val){
		if(isset($val))
			echo $val;
	}

	function prepareData($data, $path){
		$data = explode(", ", $data);
		foreach($data as $key => $val){
			$tmp = explode(":", $val);
			$data[$key] = wrapToTag($tmp[0], "a", false, "href='" . $path . $tmp[1] . "'");
		}
		return join(", ", $data);
	}

	function drawInputField($name, $id, $label, $type = "input"){
		$attributes = array("class" => "form-control", "id" => $id, "placeholder" => "Enter $name");
		echo '<div class="form-group">';
    	echo form_label($label . ":", $id);
    	switch($type):
    		case "input":
				echo form_input("$name", set_value("$name", ""), $attributes);
				break;
			case "password":
				echo form_password("$name", set_value("$name", ""), $attributes);
				break;
		endswitch;
		echo "</div>";
	}

	function wrapToTag($value, $tag, $show = false, $params = NULL){
		$res = "<$tag" . (is_null($params) ? " " : " " . $params) . ">" . $value . "</$tag>";
		if($show)
			echo $res;

		return $res;
	}

	function lowerTrim($string){
		return strtolower(trim($string));
	}