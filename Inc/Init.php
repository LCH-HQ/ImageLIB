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
			Base\InstellingenLinks::class
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
	}

	// Roep de services aan om ze te kunnen starten
	private static function instantieren( $class ) {
		$service = new $class();

		return $service;	
	}
}