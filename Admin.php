<!-- De HTML/PHP voor de Dashboard subpagina in de back-end -->

<div class="wrap">
	<h1>SpaceBooker</h1><br/><br/>
	<?php settings_errors();?>

	<div class="main_wrap">
	<div class="h1_wrap"><h1>Recente reserveringen</h1></div><br/>

	<?php
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM wp_gereserveerd ORDER BY id_reservering DESC LIMIT 4" );
		foreach ( $results as $print) {
	?>
	<div class="reserveringen_wrap">
	<h3>Ruimte:</h3>
	<p><?php echo $print -> naam_ruimte;?></p>
	<h3>Datum van reservering:</h3>
	<p><?php echo $print -> datum;?></p>
	<h3>Begintijd</h3>
	<p><?php echo $print -> reservering_start_tijd;?></p>
	<h3>Eindtijd</h3>
	<p><?php echo $print -> reservering_eind_tijd;?></p>
	<h3>Aantal personen</h3>
	<p><?php echo $print -> reserveren_personen;?></p>
	<h3>Email</h3>
	<p><?php echo $print -> email;?></p>
	</div>
	<?php } ?>
	<input type="submit" id="doaction2" class="button action" value="Bekijk alle reserveringen">
	</div>

	<br/><br/><h1>Recente aanpassingen</h1>
	<?php
		global $wpdb;
		$changes = $wpdb->get_results("SELECT CHECKSUM_AGG(BINARY_CHECKSUM(*)) FROM wp_gereserveerd WITH (NOLOCK)");
		foreach ( $changes as $print) { ?>
			<p><?php echo $print -> $changes;?></p>
	<?php } ?>


	<!-- <form method="post" action="options.php">
		<?php
			settings_fields( 'optie_groep_voorbeeld' );
			do_settings_sections( 'spacebooker' );
			submit_button();
		?>
	</form> -->
</div>
