<?php $this -> load -> view('header_view.html'); ?>
		<h3><?= word("loans") ?></h3>
		<a href="<?=loanURL?>add"><?= word("addLoan")?></a>
		<table class="table table-striped">
		<?php
			echo "<tr>";
			foreach($data as $key => $value)
					if($value)
						wrapToTag($value, "th", TRUE);
			echo "</tr>";
			if($loans)
				foreach($loans as $loan):
					echo "<tr>";
					foreach($data as $key => $value):
						if($value)
							if($key == "d_returned")
								wrapToTag($loan[$key] ? $loan[$key] : "No", "td", TRUE);
							else
								wrapToTag($loan[$key], "td", TRUE);
					endforeach;
					echo "</tr>";
				endforeach;
			else
				wrapToTag(wrapToTag("No results", "td", 0, "colspan='" . count($data). "'"), "tr", 1);
		?>
		</table>
<?php $this -> load -> view('footer_view.html'); ?>