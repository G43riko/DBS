<?php if(!isset($hideHeader))$this -> load -> view('header_view.html'); ?>
	<?php if($page == 1): ?>
		<h3>Prehľad</h3>
		<div class="row">
			<div class="col-sm-6" style="height: 300px; overflow-y: auto;">
				<h5>Filmy</h5>
				<div class="well">
					<ul class="list-group">
						<li class="list-group-item">First item</li>
						<li class="list-group-item">Second item</li>
						<li class="list-group-item">Third item</li>
					</ul>
				</div>
			</div>
			<div class="col-sm-6" style="height: 300px; overflow-y: auto;">
				<h5>Tvorcovia</h5>
				<div class="well">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Firstname</th>
								<th>Lastname</th>
								<th>Email</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>John</td>
								<td>Doe</td>
								<td>john@example.com</td>
							</tr>
							<tr>
								<td>Mary</td>
								<td>Moe</td>
								<td>mary@example.com</td>
							</tr>
							<tr>
								<td>July</td>
								<td>Dooley</td>
								<td>july@example.com</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3" style="height: 300px; overflow-y: auto;">
				<h5>Žánre</h5>
				<div class="well">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>Thriller</td>
								<td>12</td>
							</tr>
							<tr>
								<td>Comedy</td>
								<td>11</td>
							</tr>
							<tr>
								<td>Crime</td>
								<td>9</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-sm-3" style="height: 300px; overflow-y: auto;">
				<h5>Dĺžka</h5>
				<div class="well">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>Titanic</td>
								<td>169min</td>
							</tr>
							<tr>
								<td>Dark knight</td>
								<td>143min</td>
							</tr>
							<tr>
								<td>Avatar</td>
								<td>127</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-sm-2" style="height: 300px; overflow-y: auto;">
				<h5>Roky</h5>
				<div class="well">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>2015</td>
								<td>16</td>
							</tr>
							<tr>
								<td>2013</td>
								<td>8</td>
							</tr>
							<tr>
								<td>2014</td>
								<td>4</td>
							</tr>
						</tbody>
					</table>

				</div>
			</div>
			<div class="col-sm-2" style="height: 300px; overflow-y: auto;">
				<h5>Krajny</h5>
				<div class="well">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>USA</td>
								<td>22</td>
							</tr>
							<tr>
								<td>UK</td>
								<td>13</td>
							</tr>
							<tr>
								<td>CZ</td>
								<td>2</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php elseif($page == 2): ?>
		<h3>Požičky</h3>
	<?php else: ?>
		<h3>Tvorcovia</h3>
	<?php endif; ?>
<?php if(!isset($hideFooter))$this -> load -> view('footer_view.html'); ?>