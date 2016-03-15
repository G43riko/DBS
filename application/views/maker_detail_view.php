<?php $this -> load -> view('header_view.html'); ?>
	<div>
		<div>
			<div class="row">
			<h1>
				<?= $name . " (" . (!isset($d_birthday) ? "undefined" : $d_birthday) . ")" ?>
			</h1>
			<?php if(isset($avatar)): ?>
				<div class="col-sm-2">
					<img class="img-thumbnail" src="<?= $avatar ?>">
				</div>
			<?php endif; ?>
			<div>
				<table>
<?php
	$movies = explode(", ", $movies);
	$path = movieURL. "detail/";
	foreach($movies as $val){
		$tmp = explode(":", $val);
		$d = wrapToTag($tmp[0], "a", 0, " href='" . $path . $tmp[1] . "'");
		wrapToTag(wrapToTag($d , "td") . wrapToTag(" - " . $tmp[2] , "td"), "tr", 1);
	}
?>
				</table>
			</div>
		</div>
	</div>
<?php $this -> load -> view('footer_view.html'); ?>