<?php
/**
 *  @package SpaceBooker
 */

namespace Inc;

final class Init
{
	public static function haalServicesOp() {
		return [
			Pages\Admin::class,
			Base\Opstarten::class,
			Base\InstellingenLinks::class
		];
	}


	public static function registreerServices() {
		foreach ( self::haalServicesOp() as $class ) {
			$service = self::instantieren( $class );
			if ( method_exists( $service, 'registreren') ) {
				$service->registreren();
			}
		}
	}

	private static function instantieren( $class ) {
		$service = new $class();

		return $service;	
	}
}