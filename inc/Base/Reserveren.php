<?php

/*
 *   @package SpaceBooker
 */
namespace Inc\Base;

/*
 * Genereer form voor de gebruiker om een reservering te maken
 */

class Reserveren extends BaseController
{

	public $post_id;
	public $post_titel;
	public $verzenddatum;

	public function registreren() {

		// Maak de tabel aan voor de reserveringen
		self::maakGereserveerdTable();

		// Maak het mogelijk om de form te renderen in een post
		add_shortcode('reservering-form', array($this, 'maakReserveringForm') );
		add_shortcode('agenda-toevoegen',array($this,'agenda'));

	}


	public function agendaAanmaken(){

		wp_enqueue_style( 'fullcalendar_styling', $this->plugin_url . 'vendor/fullcalendar/fullcalendar.min.css' );
		wp_enqueue_script( 'jquery_script', $this->plugin_url . 'vendor/fullcalendar/lib/jquery.min.js' );
		wp_enqueue_script( 'moment_script', $this->plugin_url . 'vendor/fullcalendar/lib/moment.min.js' );
		wp_enqueue_script( 'locale_script', $this->plugin_url . 'vendor/fullcalendar/nl.js' );
		wp_enqueue_script( 'fullcalendar_script', $this->plugin_url . 'vendor/fullcalendar/fullcalendar.min.js' );

		echo "<script>
			$(document).ready(function calenderAanmaken() {

				$('#calendar').fullCalendar({
						firstDay: 1,
						weekends: false,
						timezone: 'UTC',
						defaultView: 'agendaWeek',
						businessHours: true,
						locale: 'nl',
						nowIndicator: true,
						aspectRatio: 4
				});

				$('.agendaItemVerzenden').on('click', function(e){
					e.preventDefault();

					// Find form and submit it
					verzendAgendaItem();
			});

				function verzendAgendaItem() {
				$('#calendar').fullCalendar('renderEvent',
				{
						title: $('.agendaItemNaam').val(),
						start: new Date($('.agendaItemStart').val()),
						end: new Date($('.agendaItemEinde').val()),
						editable: true
				},
				true
				);
		};
		});


		</script>";

	}

	public function agenda() {

		echo "
<link rel='stylesheet' href='vendor/fullcalendar/fullcalendar.min.css' />
<script src='vendor/fullcalender/lib/jquery.min.js'></script>
<script src='vender/fullcalender/lib/moment.min.js'></script>
<script src='vender/fullcalendar/fullcalendar.js'></script>";

echo '<div id="calendar"></div>';

		echo '<form id="agendaItemInvoeren">
			<input type="text" class="agendaItemNaam">
			<input type="datetime-local" class="agendaItemStart">
			<input type="datetime-local" class="agendaItemEinde">
			<input type="submit" class="agendaItemVerzenden">
		</form>

		<div class="wrap">

		</div>';

		$this->agendaAanmaken();
}













	function register_shortcodes(){
	   add_shortcode('agenda-toevoegen',array($this,'agenda'));
	}



	private static function maakGereserveerdTable() {

		global $wpdb;
		// creates my_table in database if not exists
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
				studentennummer VARCHAR(10) NOT NULL,
				email VARCHAR(50) NOT NULL,
				reserveren_personen INT(3) NOT NULL,
				UNIQUE(id_reservering)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	public function maakReserveringForm() {

		// Genereer de data voor de hidden fields
		$post_id = get_the_ID();
		$post_titel = get_the_title( $post_id );
		$verzenddatum = date("Y-m-d G:i:s");

		// Render het formulier
		echo '<form id="reserveren-form" name="reserveren-form" method="post" action="">';
		echo "<input type='hidden' id='reserveren_id_ruimte' value='$post_id' name='reserveren_id_ruimte' />";
		echo "<input type='hidden' id='reserveren_timestamp' value='$verzenddatum' name='reserveren_timestamp' />";
		echo "<input type='hidden' id='reserveren_ruimte' value='$post_titel' name='reserveren_ruimte' />";
		echo "<h2>Reserveren</h2>";
		echo '<p><label for="reserveren_datum">Datum</label><br />';
		echo '<input type="date" id="reserveren_datum" name="reserveren_datum">';
		echo '<p><label for="reserveren_start_tijd">Begintijd</label><br />';
		echo '<input type="time" id="reserveren_start_tijd" name="reserveren_start_tijd">';
		echo '<p><label for="reserveren_eind_tijd">Eindtijd</label><br />';
		echo '<input type="time" id="reserveren_eind_tijd" name="reserveren_eind_tijd">';
		echo '<p><label for="reserveren_capaciteit">Capaciteit</label><br />';
		echo '<input type="number" id="reserveren_capaciteit" name="reserveren_capaciteit">';
		echo '<p><label for="reserveren_studentennummer">Studentennummer</label><br />';
		echo '<input type="text" id="reserveren_studentennummer" name="reserveren_studentennummer"><br>';
		echo '<p><label for="reserveren_email">Mailadres</label><br />';
		echo '<input type="email" id="reserveren_email" name="reserveren_email"><br>';
		echo '<input type="submit" value="Verzenden" tabindex="6" id="submit" name="submit" />';
		echo '<input type="hidden" name="action" value="reservering_form_nonce" />';
		echo '</form>';

		// Controleer of de data komt van dezelfde website
		wp_nonce_field( 'action','reservering_form_nonce' );

		// In het geval het formulier wordt verzonden,
		// sla het formulier op
		if($_POST){
		$this->slaReserveringFormOp();
		}
	}

	public function slaReserveringFormOp() {
		global $wpdb;

		// Controleer de ingevulde velden van het formulier
		// en geef een foutmelding waar nodig
		if (isset ($_POST['reserveren_id_ruimte'])) {
			$id_ruimte =  $_POST['reserveren_id_ruimte'];
		} else if ( ! isset ($_POST['reserveren_timestamp']) && ! isset ($_POST['reserveren_timestamp']) ) {
			echo 'Deze ruimte bestaat niet meer! Je reservering kon niet worden voltooid.';
			exit;
		}
		if (isset ($_POST['reserveren_timestamp']) ) {
			$timestamp = $_POST['reserveren_timestamp'];
		} else {
			echo 'Timestamp mislukt.';
			exit;
		}
		if (isset ($_POST['reserveren_ruimte']) ) {
			$ruimte = $_POST['reserveren_ruimte'];
		} else {
			echo 'Ruimte ophalen mislukt.';
			exit;
		}
		if (isset ($_POST['reserveren_datum']) ) {
			$datum = $_POST['reserveren_datum'];
		} else {
			echo 'Voer een datum in.';
			exit;
		}
		if (isset ($_POST['reserveren_start_tijd']) ) {
			$start_tijd = $_POST['reserveren_start_tijd'];
		} else {
			echo 'Voer een starttijd in.';
			exit;
		}
		if (isset ($_POST['reserveren_eind_tijd']) ) {
			$eind_tijd = $_POST['reserveren_eind_tijd'];
		} else {
			echo 'Voer een eindtijd in.';
			exit;
		}
		if (isset ($_POST['reserveren_capaciteit']) ) {
			$capaciteit = $_POST['reserveren_capaciteit'];
		} else {
			echo 'Voer het aantal personen in dat gebruikmaakt van de ruimte.';
			exit;
		}
		if (isset ($_POST['reserveren_studentennummer']) ) {
			$studentennummer = $_POST['reserveren_studentennummer'];
		} else {
			echo '<p>Voer je studentennummer in.</p>';
			exit;
		}
		if (isset ($_POST['reserveren_email']) ) {
			$email = $_POST['reserveren_email'];
		} else {
			echo 'Voer een emailadres in.';
			exit;
		}

		// Voeg de reservering toe aan de reserveringstabel
		$table = $wpdb->prefix . "gereserveerd";
		$sql = "INSERT INTO $table (
				id_ruimte,
				datum,
				reservering_start_tijd,
				reservering_eind_tijd,
				datum_reservering,
				naam_ruimte,
				studentennummer,
				email,
				reserveren_personen
		) VALUES (
				'$id_ruimte',
				'$timestamp',
				'$start_tijd',
				'$eind_tijd',
				'$datum',
				'$ruimte',
				'$studentennummer',
				'$email',
				'$capaciteit'
		);";
		dbDelta( $sql );
	}
}
