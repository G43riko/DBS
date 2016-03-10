<?php $this -> load -> view('header_view.html'); ?>
		<table class="table table-striped">
			<?php
				echo "<tr>";

				foreach($data as $key => $value)
					if($value)
						wrapToTag($value, "th", TRUE);

				echo "</tr>";
				foreach($makers as $maker):
					echo "<tr>";

					foreach($data as $key => $value)
						if($value){
							if($key == "name"){
								$link = "/movies/makers/detail/" . $maker["maker_id"];
								wrapToTag("<a href=\"$link\">" . $maker[$key]. "</a>", "td", TRUE);
							}
							else
								wrapToTag($maker[$key], "td", TRUE);
						}
				
					echo "</tr>";
				endforeach; 
			?>
		</table>
<?php $this -> load -> view('footer_view.html'); ?>