<?php $this -> load -> view('header_view.html'); ?>
	<div>
		<div>
			<h3>
				<?= $name . " (" . (!isset($d_birthday) ? "undefined" : $d_birthday) . ")" ?>

			</h3>
			<div>
				<table>
<?php
	$movies = explode(", ", $movies);
	$path = "/movies/movies/detail/";
	foreach($movies as $val){
		$tmp = explode(":", $val);
		wrapToTag(wrapToTag(wrapToTag($tmp[0], "a", false, " href='" . $path . $tmp[1] . "'"), "td"),"tr", 1);
	}
?>
				</table>
			</div>
		</div>
	</div>
<?php $this -> load -> view('footer_view.html'); ?>