<?php $this -> load -> view('header_view.html'); ?>
	<?php 
		if(!is_login()):
			redirect("login/loans_add");
		else: 
	?>

	<script type="text/javascript">

	window.onload = function(){
		loadMoviesFromBasket();
	}

	</script>
	<h3><?= "Nová pôžička" ?></h3>
	<?= form_open("", array('role' => 'form', "class" => "form", "autocomplete" => "off"))?>
	<div>
		<div class="form-group">
			<label for="usr"><?= word("firstName") ?>:</label>
			<input type="text" class="form-control" disabled id="frst_name" value="<?= getSession('first_name')?>">
		</div>
		<div class="form-group">
			<label for="usr"><?= word("secondName") ?>:</label>
			<input type="text" class="form-control" disabled id="scnd_name" value="<?= getSession('second_name')?>">
		</div>
		<div class="form-group">
			<label for="usr"><?= word("email") ?>:</label>
			<input type="text" class="form-control" disabled id="scnd_name" value="<?= getSession('email')?>">
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 well" id="movies_list">
			<ul class='list-group'>
				<li class='list-group-item'>
					<span style="margin-left: 80px;"><?= word("numOfMovies")?>: <span id="price">0</span>€<span>
					<span style="margin-left: 80px;"><?= word("totalPrice")?>: <span id="number">0</span><span>
					<input type="button" class="btn btn-default" value="<?= word('clearBasket')?>" onclick="clearMovies($(this).parent().parent().parent().parent().parent())">
				</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<input type="text" name="movies" class="form-control" id="movies_id" placeholder="<?= word('enterMovies')?>" onkeyup="getMovies(this.value)">
			<div id="moviesHints"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<input type="submit" value="Dokončiť pôžičku" class="btn btn-default">
		</div>
	</div>
	<?php
			echo form_close();
		endif; 
	?>	
<?php $this -> load -> view('footer_view.html'); ?>