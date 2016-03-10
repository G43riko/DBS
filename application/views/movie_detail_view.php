
<?php if(!isset($hideHeader))$this -> load -> view('header_view.html'); ?>
	<div>
		<div>
			<h1><?= (isset($title_sk) ? $title_sk : $title) . " (" . $year . ")" ?></h1>
			<?php
				if(isset($title_sk))
					wrapToTag($title, "h3", TRUE);
			?>
			<span>
				<?php
					echo $rating  . " | " . $length  . " | ";
					echo echoArray($genres, ", ") . " | ";
					echo echoArray($countries, ", ")	 . " | ";
					
					echo $director . " | ";
					wrapToTag("imdb", "a", 1, "target='_blank' href='http://www.imdb.com/title/" . $imdbId . "'");
				?>
			</span>
			<div>
				<h4>Tags</h4>
				<?= echoArray($tags, ", ") ?>
			</div>
			<div style="width:100%;">
				<h4>Actors</h4>
				<?= echoArray($actors, ", ") ?>
			</div>
		</div>
	</div>
<?php if(!isset($hideFooter))$this -> load -> view('footer_view.html'); ?>