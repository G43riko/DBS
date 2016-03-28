<?php if(!isset($hideHeader))$this -> load -> view('header_view.html'); ?>
	<div class="row">
		<div class="modal-header">
			<h1>
				<?= $first_name . " " . $second_name . " (" . $d_birthday . ")"?>
			</h1>
		</div>
		<div class="modal-body">
			<table>
				<tr>
					<td>Požičané dňa: </td>
					<td><?= $d_created ?></td>
				</tr>
				<tr>
					<td>Filmy:</td>
					<td><?= prepareData($movies, movieDetailURL, NULL, "</td></tr><tr><td></td><td>")?></td>
				</tr>
			</table>
			
		</div>
		<div class="modal-footer">
			<?php
				$class = 'class="btn btn-default"';
				wrapToTag("Dokončiť", "button", 1, $class);
			?>
		</div>
	</div>
<?php if(!isset($hideFooter))$this -> load -> view('footer_view.html'); ?>