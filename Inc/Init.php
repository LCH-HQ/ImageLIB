<?php
/**
 *  @package SpaceBooker
 */

namespace Inc;

/*
 * Initialiseren van de plug-in
 */

final class Init
{
	// Activeer de service classes
	public static function haalServicesOp() {
		return [
			Pages\Admin::class,
			Base\Opstarten::class,
			Base\InstellingenLinks::class,
			Base\BaseController::class
		];
	}

	// Lokaliseer de services om deze te starten
	public static function registreerServices() {
		foreach ( self::haalServicesOp() as $class ) {
			$service = self::instantieren( $class );
			if ( method_exists( $service, 'registreren') ) {
				$service->registreren();
			}
		}
	self::maakCustomTable();

	self::maakCustomTables();


//	self::maakCustomTableReservering();
	}


		private static function maakCustomTable() {
		global $wpdb;
		// creates my_table in database if not exists
		$table = $wpdb->prefix . "ruimtes";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS $table (
				id_ruimte INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				datum DATE NOT NULL,
				beschikbaar_start_tijd TIME NOT NULL,
				beschikbaar_start_tijdeind_tijd TIME NOT NULL,
				capaciteit_personen INT(3) NOT NULL,
				naam_ruimte VARCHAR(50) NOT NULL,
				beschrijving_ruimte VARCHAR(400) NOT NULL,
				faciliteiten VARCHAR(20),
				UNIQUE(id_ruimte)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}


	private static function maakCustomTables() {
	global $wpdb;
	// creates my_table in database if not exists
	$tableRuimtes = $wpdb->prefix . "ruimtes";
	$table = $wpdb->prefix . "gereserveerd";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table (
			id_reservering INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			id_ruimte INT NOT NULL,
			datum DATE NOT NULL,
			reservering_start_tijd TIME NOT NULL,
			reservering_eind_tijd TIME NOT NULL,
			datum_reservering DATE NOT NULL,
			naam_ruimte VARCHAR(50) NOT NULL,
			email VARCHAR(50) NOT NULL,
			reserveren_personen INT(3) NOT NULL,
			FOREIGN KEY(id_ruimte) REFERENCES " . $tableRuimtes . "(id_ruimte),
			UNIQUE(id_reservering)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}

// Roep de services aan om ze te kunnen starten
private static function instantieren( $class ) {
		$service = new $class();
		return $service;
}




}
