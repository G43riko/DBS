<?php $this -> load -> view('header_view.html'); ?>
	<h3><?= $title ?></h3>	
	<table class="table table-striped sortable">
		<thead>
			<tr>
				<?php
					foreach($columns as $key => $value):
						if($value)
							echo wrapToTag($value, "th");
					endforeach; 
				?>
			</tr>
		</thead>
		<tbody>
			<?php
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
		</tbody>
	</table>
<?php $this -> load -> view('footer_view.html'); ?>