<?php $this -> load -> view('header_view.html'); ?>
		<h1>Prihlásenie</h1>
		<?php
			echo validation_errors();
			echo form_open("auth/login", array('role' => 'form', "class" => "form"));

			drawInputField("email", "email_id", "Email");
			drawInputField("heslo", "heslo_id", "Heslo", "password");
			
			echo form_submit("submit", "prihlásiť", array("class" => "btn btn-default"));
			echo form_close();
		?>
<?php $this -> load -> view('footer_view.html'); ?>