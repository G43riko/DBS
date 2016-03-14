<?php $this -> load -> view('header_view.html'); ?>
	<h3><?= word("searchMovies") ?></h3>
	<div>
		<div class="row">
			<div class="well well-sm">
				<label for="search">Search:</label>
				<input type="text" placeholder="key" id="search_input" <?php echoIf($name, "value='$name'"); ?>>
				<input type="button" value="hladat" onclick="searchMovie()">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 well gScrollable">
				<table style='width: 100%' class="table table-striped">
<?php
	foreach($names as $key => $value):
		if((!$vypis || $val > ++$i) && isset($data[$key])):
			$vypis = 1;
			echo wrapToTag(wrapToTag(wrapToTag($value, "h3"),"td", 0, "colspan='3'"),"tr");

			foreach($data[$key] as $row):
				$v = get_object_vars($row);;
				$line = "<a target='_blank' href='$link/" . $v["id"] . " '>" . $v["title"] . " </a>";
				$line = wrapToTag($line, "td");
				$line .= wrapToTag(explode(",", $v["description"])[0], "td");
				$v = wrapToTag("info", "button",0 , "onclick='loadMovieDetail(\"" . $v["id"] . "\")'");
				$line .= wrapToTag($v, "td");
				wrapToTag($line, "tr", TRUE);
			endforeach;
		endif;			
	endforeach;
?>
				</table>
			</div>
			<div class="col-sm-6 well" id="movie_detail_holder" style="min-height:60px"></div>
		</div>
	</div>
<?php $this -> load -> view('footer_view.html'); ?>