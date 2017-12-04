<?php

/*
 *   @package SpaceBooker
 */
namespace Inc\Api\Callbacks;

/*
 * Verwerk alle callbacks voor de adminpagina's in de back-end van WordPress
 */

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
	public function adminDashboard()
	{
		return require_once( "$this->plugin_pad/templates/Admin.php");
	}

	public function adminRuimtes()
	{
		return require_once( "$this->plugin_pad/templates/Ruimtes.php");
	}

	public function adminReserveringen()
	{
		return require_once( "$this->plugin_pad/templates/Reserveringen.php");
	}

	public function adminGebruikers()
	{
		return require_once( "$this->plugin_pad/templates/Gebruikers.php");
	}

	/*
	 * Verwerk alle data van de custom fields voor de plug-in
	 * Bekijk de Inc/Pages/Admin.php voor alle data
	 */

	public function optieGroepVoorbeeld( $input )
	{
		return $input;
	}

	public function optieGroepSectie()
	{
		echo 'Dit is de beschrijving van de sectie';
	}

	public function spaceBookerTekstveldVoorbeeld() {
		$waarde = esc_attr( get_option( 'tekst_voorbeeld' ) );
		echo '<input type="text" class="regular-text" name="tekst_voorbeeld" value="' . $waarde . '" placeholder="Dit is de placeholder">';
	}
}