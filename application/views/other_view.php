<?php $this -> load -> view('header_view.html'); ?>
	<h3><?= $title ?></h3>	
	<table class="table table-striped">
		<?php
			echo "<tr>";
			foreach($columns as $key => $value):
				if($value)
					echo wrapToTag($value, "th");
			endforeach; 
			echo "</tr>";
			foreach($data as $genre):
				echo "<tr>";
				foreach($columns as $key => $value):
					if($value){
						if($key == "name"){
							$n = $genre[$key];
							wrapToTag(wrapToTag($n, "a", false, "href='" . $path . $n . "'"), "td", true);
						}
						else
							wrapToTag($genre[$key], "td", true);
					}
				endforeach;
				echo "</tr>";
			endforeach; 
		?>
	</table>
<?php $this -> load -> view('footer_view.html'); ?>