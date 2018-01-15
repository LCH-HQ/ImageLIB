<div class="wrap">
	<h1>Gebruikers</h1><br/><br/>
	<?php settings_errors();?>

	<div class="main_wrap">

	<?php
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM wp_gereserveerd ORDER BY email" );
		foreach ( $results as $print) {
      
	?>
	<div class="reserveringen_wrap">
  	<h3>Gebruikers email:</h3>
  	<p><?php echo $print -> email;?></p>
	</div>
	<?php } ?>
	<input type="submit" id="doaction2" class="button action" value="Bekijk alle reserveringen">
</div>
