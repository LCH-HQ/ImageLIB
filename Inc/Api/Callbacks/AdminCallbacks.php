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

	// // dropdowns test werkend
	// public function spaceBookerDropdown() {
	// 	$waarde = esc_attr( get_option( 'tekst_dropdown' ) );
	// 	echo '<input type="text" class="regular-text" name="tekst_dropdown" value="' .$waarde . '" placeholder="Dit is de placeholder dropdown">';
	// }


	//dropdown optie
	public function spaceBookerDropdown() {
		$waarde_dropdown = esc_attr( get_option( 'tekst_dropdown' ) );
//		echo '<input type="text" class="regular-text" name="tekst_voorbeeld" value="' . $waarde . '" placeholder="Dit is de placeholder dropdown">';

// werkt schrijft naar db
		// echo '<select class="regular-text" name="tekst_dropdown" value="'.$waarde .'">
		// 	<option>optie 1</option>
		// 	<option>optie 2</option>
		// 	<option>optie 3</option>
		// 	</select>';


		// echo '<?php
		// (isset($_POST["tekst_dropdown"])) ? $company = $_POST["tekst_dropdown"] : $company=1;
		// >';

		echo '<select name="tekst_dropdown" value="'.$waarde_dropdown.'">';

		if( $waarde_dropdown == 1){
			echo "<option selected >Selecteer je keuze</option>";

		}

		else if( $waarde_dropdown == 2){
			echo "<option selected >HTC</option>";

		}
		else if ($waarde_dropdown == 3){
			echo "<option selected >T</option>";
		}

		else if( $waarde_dropdown == 4){
			echo "<option selected >C</option>";
		}

		echo '<option>Selecteer je keuze</option>;
					<option value="2">HTC</option>;
					<option value="3">T</option>;
					<option value="4">C</option>;
					</select>';
		}



		public function spaceBookerRadioButtons() {
			$waarde_radio = esc_attr( get_option( 'radio_buttons' ) );
	//		echo '<input type="text" class="regular-text" name="tekst_voorbeeld" value="' . $waarde . '" placeholder="Dit is de placeholder dropdown">';

	// werkt schrijft naar db



if( $waarde_radio == male){
		echo '<input type="radio" name="radio_buttons" checked="checked">male';

	}

	else if( $waarde_radio == female){
		echo '<input type="radio" name="radio_buttons" checked="checked">female';

	}
	else if ($waarde_radio == other){
		echo '<input type="radio" name="radio_buttons" checked="checked">other';
	}


	echo '<input type="radio" name="radio_buttons"  value="male"> Male
  			<input type="radio" name="radio_buttons" value="female"> Female
  			<input type="radio" name="radio_buttons" value="other"> Other';


			}





}
