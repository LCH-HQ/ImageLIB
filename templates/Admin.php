<!-- De HTML/PHP voor de Dashboard subpagina in de back-end -->

<div class="wrap">
	<h1>SpaceBooker</h1>
	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php
			settings_fields( 'optie_groep_voorbeeld' );
			do_settings_sections( 'spacebooker' );
			submit_button();
		?>
	</form>
</div>
