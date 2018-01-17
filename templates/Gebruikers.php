<div class="wrap">
    <h1>Gebruikers</h1><br/><br/>
    <?php settings_errors();?>

    <div class="main_wrap">

    <?php
        global $wpdb;
        $results = $wpdb->get_results( "SELECT * FROM wp_gereserveerd WHERE email IN (SELECT email FROM wp_gereserveerd GROUP BY email HAVING count(*) > 1) ORDER BY email limit 4 ");
        foreach ( $results as $print) {
    ?>

    <div class="reserveringen_wrap">
      <h3>Gebruikers email:</h3>
      <p><?php echo $print -> email;?></p>
        <p><?php echo $print -> id_reservering;?></p>
        <p><?php echo $print -> reservering_start_tijd;?></p>
    </div>
<?php } ?>


<?php
    global $wpdb;
    $results2 = $wpdb->get_results( "SELECT * FROM wp_gereserveerd WHERE email IN (SELECT email FROM wp_gereserveerd GROUP BY email HAVING count(*) < 2) ORDER BY email limit 2 ");
    foreach ( $results2 as $print) {
?>

<div class="reserveringen_wrap">
    <h3>Gebruikers email:</h3>
    <p><?php echo $print -> email;?></p>
    <p><?php echo $print -> id_reservering;?></p>
    <p><?php echo $print -> reservering_start_tijd;?></p>
</div>
<?php } ?>


    <input type="submit" id="doaction2" class="button action" value="Bekijk alle reserveringen">
</div>
