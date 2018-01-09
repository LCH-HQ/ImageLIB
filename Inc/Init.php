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
	}





	private static function maakCustomTable() {

	global $wpdb;
	// creates my_table in database if not exists
	$table = $wpdb->prefix . "mijn___Stoelentafel";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`name` text NOT NULL,
	UNIQUE (`id`)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	echo "test";
}

		// public static function registreerTable(){
		// 	self::maakCustomTable();
		// }





	// Roep de services aan om ze te kunnen starten
	private static function instantieren( $class ) {
		$service = new $class();
		return $service;
	}









}
