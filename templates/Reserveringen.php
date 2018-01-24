<div class="wrap">
<h1>Reserveringen</h1><br/>
<?php settings_errors();?>

<div class="reserveer_wrap">
<h2>Zoek naar een reservering</h2>
<form action="" method="post">
	Studentnummer <input type="text" name="term" value=""/><br /><br />
	<input type="submit" id="doaction2" class="button action" value="Zoek reservering"><br /><br />
</form>
</div>

<div class="reserveer_wrap">
<h2>Annuleer een reservering</h2>
<form action="" method="post">
	Reserveringsnummer <input type="text" name="annuleer" value=""/><br /><br />
	<input type="submit" id="doaction2" class="button action" value="Annuleer reservering"><br /><br />
</form>
</div>

<?php

if (!empty($_POST['annuleer'])) {
	global $wpdb;
	$id_reservering = $_POST['annuleer'];
	$results3 = $wpdb->get_results($wpdb->prepare("DELETE FROM wp_gereserveerd WHERE id_reservering LIKE %s", "%".$id_reservering."%"));
}
?>

<?php
if (!empty($_POST['term'])) {
	global $wpdb;
	$term = $_POST['term'];
	$results = $wpdb->get_results($wpdb->prepare( "SELECT * FROM wp_gereserveerd WHERE studentennummer LIKE %s", "%".$term."%"));
	foreach ($results as $result) { ?>
		<div class="reserveringen_wrap">
			<h3>Reserveringsnummer:</h3>
			<p><?php echo $result -> id_reservering;?></p>
			<h3>Studentnummer:</h3>
			<p><?php echo $result -> studentennummer;?></p>
			<h3>Gebruikers email:</h3>
			<p><?php echo $result -> email;?></p>
			<h3>Ruimte:</h3>
			<p><?php echo $result -> naam_ruimte;?></p>
			<h3>Datum:</h3>
			<p><?php echo $result -> datum;?></p>
			<h3>Begintijd</h3>
			<p><?php echo $result -> reservering_start_tijd;?></p>
			<h3>Eindtijd</h3>
			<p><?php echo $result -> reservering_eind_tijd;?></p>
			<h3>Aantal personen</h3>
			<p><?php echo $result -> reserveren_personen;?></p>
		</div>


	<?php }
}
?>
<br /><br /><br />
<div class="break_">
<h2>Reserveringslijst</h2>
</div>
<br />
<?php
global $wpdb;
$results2 = $wpdb->get_results( "SELECT * FROM wp_gereserveerd WHERE id_reservering IN (SELECT id_reservering FROM wp_gereserveerd GROUP BY id_reservering HAVING count(*) > 0)
ORDER BY datum && reservering_start_tijd ");
foreach ( $results2 as $print) {
	?>


	<div class="reserveringen_wrap">
		<h3>Reserveringsnummer:</h3>
		<p><?php echo $print -> id_reservering;?></p>
		<h3>Studentnummer:</h3>
		<p><?php echo $print -> studentennummer;?></p>
		<h3>Gebruikers email:</h3>
		<p><?php echo $print -> email;?></p>
		<h3>Ruimte:</h3>
		<p><?php echo $print -> naam_ruimte;?></p>
		<h3>Datum:</h3>
		<p><?php echo $print -> datum;?></p>
		<h3>Begintijd</h3>
		<p><?php echo $print -> reservering_start_tijd;?></p>
		<h3>Eindtijd</h3>
		<p><?php echo $print -> reservering_eind_tijd;?></p>
		<h3>Aantal personen</h3>
		<p><?php echo $print -> reserveren_personen;?></p>
	</div>
<?php } ?>





<input type="submit" id="doaction2" class="button action" value="Bekijk alle reserveringen">
</div>
