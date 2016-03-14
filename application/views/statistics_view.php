<?php if(!isset($hideHeader))$this -> load -> view('header_view.html'); ?>
	<h3>Prehľad</h3>
	<div class="row">
		<div class="col-sm-3" >
			<h5>Počty</h5>
			<div class="well gScrollable">
				<table class="table table-striped">
					<?php
						foreach($data["number"]["body"] as $key => $val)
							wrapToTag(wrapToTag($key, "td") . wrapToTag($val, "td"), "tr", TRUE);
					?>
				</table>
			</div>
		</div>
		<div class="col-sm-3">
			<h5>Najlepšie</h5>
			<div class="well gScrollable">
				<table class="table table-striped">
			<?php
				showSimpleMovies($data["movies"]["best"], "rating", movieURL . "detail/");
			?>
				</table>
			</div>
		</div>
		<div class="col-sm-3">
			<h5>Najdlhšie</h5>
			<div class="well gScrollable">
				<table class="table table-striped">
			<?php
				showSimpleMovies($data["movies"]["longest"], "length", movieURL . "detail/", " min");
			?>
				</table>
			</div>
		</div>
		<div class="col-sm-3">
			<h5>Najnovšie</h5>
			<div class="well gScrollable">
				<table class="table table-striped">
			<?php
				showSimpleMovies($data["movies"]["newest"], "d_created", movieURL . "detail/");
			?>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3">
			<h5>Žánre</h5>
			<div class="well gScrollable">
				<table class="table table-striped">
					<thead>
						<tr>
							<?php
								foreach($data["genres"]["head"] as $genre)
									wrapToTag($genre, "td", TRUE);
							?>
						</tr>
					</thead>
					<tbody>
			<?php
				foreach($data["genres"]["body"] as $genre):
					$d = makeLink($genre["name"], genreURL . $genre["name"]);
					$d = wrapToTag($d, "td");
					$d .= wrapToTag($genre["num"], "td");
					wrapToTag($d, "tr", TRUE);
				endforeach;
			?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-sm-3">
			<h5>Roky</h5>
			<div class="well gScrollable">
				<table class="table table-striped">
					<thead>
						<tr>
							<?php
								foreach($data["years"]["head"] as $year)
									wrapToTag($year, "td", TRUE);
							?>
						</tr>
					</thead>
					<tbody>
				<?php
					foreach($data["years"]["body"] as $year):
						$d = makeLink($year["name"], yearURL . $year["name"]);
						$d = wrapToTag($d, "td");
						$d .= wrapToTag($year["num"], "td");
						wrapToTag($d, "tr", TRUE);
					endforeach;
				?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-sm-3">
			<h5>Krajny</h5>
			<div class="well gScrollable">
				<table class="table table-striped">
					<thead>
						<tr>
							<?php
								foreach($data["countries"]["head"] as $country)
									wrapToTag($country, "td", TRUE);
							?>
						</tr>
					</thead>
					<tbody>
				<?php
					foreach($data["countries"]["body"] as $country):
						$d = makeLink($country["name"], countryURL . $country["name"]);
						$d = wrapToTag($d, "td");
						$d .= wrapToTag($country["num"], "td");
						wrapToTag($d, "tr", TRUE);
					endforeach;
				?>
					</tbody>
				</table>

			</div>
		</div>

		<div class="col-sm-3">
			<h5>Tvorcovia</h5>
			<div class="well gScrollable">
				<table class="table table-striped">
					<thead>
						<tr>
							<?php
								foreach($data["makers"]["head"] as $maker)
									wrapToTag($maker, "td", TRUE);
							?>
						</tr>
					</thead>
					<tbody>
				<?php
					foreach($data["makers"]["body"] as $maker){
						$url = makerURL . "detail/" . $maker["maker_id"];
						$link = makeLink(checkStringLength($maker["name"], 20), $url);
						echo "<tr>" . wrapToTag($link, "td") . wrapToTag($maker["num"], "td") . "</tr>";
						}
				?>
					</tbody>
				</table>

			</div>
		</div>
	</div>
<?php if(!isset($hideFooter))$this -> load -> view('footer_view.html'); ?>