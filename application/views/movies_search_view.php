<?php $this -> load -> view('header_view.html'); ?>
	<div>
		<div class="row">
			<div class="well well-sm">
				<label for="search">Search:</label>
				<input type="text" placeholder="key" id="search_input" <?php if(isset($name))echo "value='$name'"; ?>>
				<input type="button" value="hladat" onclick="searchMovie()">
			</div>
		
		</div>
		<div class="row">
			<div class="col-sm-6 well" style="max-height: 300px; overflow-y: auto;">
				<table style='width: 100%'>
<?php
	foreach($names as $key => $value):
		if(!$vypis || $val > ++$i && isset($data["$key"])):
			$vypis = 1;
			echo wrapToTag(wrapToTag(wrapToTag($value, "h3"),"td"),"tr");
			foreach($data[$key] as $row):
				$row = get_object_vars($row);;
				$line = "<a target='_blank' href='$link/" . $row["id"] . " '>" . $row["title"] . " </a>";
				echo "<tr>". wrapToTag($line, "td");
				wrapToTag($row["description"], "td", TRUE);
				$but = wrapToTag("detail", "button",false, "onclick='loadMovieDetail(\"" . $row["id"] . "\")'");
				echo wrapToTag($but, "td") . "</tr>";
			endforeach;
		endif;			
	endforeach;
?>
				</table>
			</div>
			<div class="col-sm-6 well" id="movie_detail_holder"></div>
		</div>
	</div>
<?php $this -> load -> view('footer_view.html'); ?>