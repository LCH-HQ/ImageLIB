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


	public function optieGroepDropdown( $input )
	{
		return $input;
	}

	public function optieGroepRadioButtons( $input )
	{
		return $input;
	}

	public function optieGroepSectie()
	{
		echo 'Dit is de beschrijving van de sectie pagina';
	}

	public function spaceBookerTekstveld() {
		$waarde = esc_attr( get_option( 'tekst_voorbeeld' ) );
		echo '<input type="text" class="regular-text" name="tekst_voorbeeld" value="' .$waarde . '" placeholder="Dit is de placeholder">';
	}

	// In het geval dat er een optie geselecteerd is van de dropdown,
	// push opties met het geselecteerde item wanneer er opgeslagen wordt
	public function spaceBookerDropdown() {
		$waarde_dropdown = esc_attr( get_option( 'tekst_dropdown' ) );
			echo '<select name="tekst_dropdown" value="' . $waarde_dropdown . '">';

			if ( $waarde_dropdown == 1) {
				echo 	"<option selected>Selecteer je keuze</option>
						<option value='2'>HTC</option>
						<option value='3'>T</option>
						<option value='4'>C</option>
						</select>";
			}
			else if ( $waarde_dropdown == 2) {
				echo 	"<option>Selecteer je keuze</option>
						<option selected value='2'>HTC</option>
						<option value='3'>T</option>
						<option value='4'>C</option>
						</select>";
			}
			else if ( $waarde_dropdown == 3) {
				echo 	"<option>Selecteer je keuze</option>
						<option value='2'>HTC</option>
						<option selected value='3'>T</option>
						<option value='4'>C</option>
						</select>";
			}
			else if ( $waarde_dropdown == 4 ) {
				echo 	"<option>Selecteer je keuze</option>
						<option value='2'>HTC</option>
						<option value='3'>T</option>
						<option selected value='4'>C</option>
						</select>";
			}
		}

	// In het geval dat er een optie geselecteerd is van de radio,
	// push de opties met het geselecteerde item wanneer er opgeslagen wordt
	public function spaceBookerRadioButtons() {
		$waarde_radio = esc_attr( get_option( 'radio_buttons' ) );

			if ( $waarde_radio == 'male' ) {
				echo "<input type='radio' name='radio_buttons' value='male' checked>Male";
				echo "<input type='radio' name='radio_buttons' value='female'>Female";
				echo "<input type='radio' name='radio_buttons' value='other'>Other";
			}
			else if ( $waarde_radio == 'female' ) {
				echo "<input type='radio' name='radio_buttons' value='male'>Male";
				echo "<input type='radio' name='radio_buttons' value='female' checked>Female";
				echo "<input type='radio' name='radio_buttons' value='other'>Other";
			}
			else if ( $waarde_radio == 'other' ) {
				echo "<input type='radio' name='radio_buttons' value='male'>Male";
				echo "<input type='radio' name='radio_buttons' value='female'>Female";
				echo "<input type='radio' name='radio_buttons' value='other' checked>Other";
			}
			else {
				echo "<input type='radio' name='radio_buttons' value='male'>Male";
				echo "<input type='radio' name='radio_buttons' value='female'>Female";
				echo "<input type='radio' name='radio_buttons' value='other'>Other";
			}
		}

