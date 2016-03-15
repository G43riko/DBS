<?php $this -> load -> view('header_view.html'); ?>
	<h3><?= word("makers") ?></h3>
	<label for="movie_key"> Hladať tvorcu:</label>
	<input type="text" class="form-control-input" id="movie_key" placeholder="Hladať film">
	<table class="table table-striped sortable">
		<thead>
			<tr>
				<?php
					foreach($data as $key => $value)
						if($value)
							wrapToTag($value, "th", TRUE);
				?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($makers as $maker):
				echo "<tr>";
				foreach($data as $key => $value)
					if($value){
						if($key == "name"){
							$link = makerURL . "detail/" . $maker["maker_id"];
							$link = makeLink("$maker[$key]", $link);
							wrapToTag($link, "td", TRUE);
						}
						else
							wrapToTag($maker[$key], "td", TRUE);
					}
			
				echo "</tr>";
			endforeach; 
		?>
		</tbody>
	</table>
<?php $this -> load -> view('footer_view.html'); ?>