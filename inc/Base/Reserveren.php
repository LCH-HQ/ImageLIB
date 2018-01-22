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
		global $post;
		global $wpdb;

        $tabel = $wpdb->prefix . "postmeta";
        $stad_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'stad'", 
        	ARRAY_A );
        $adres_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'adres'", 
        	ARRAY_A );
	    $begindatum_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'begindatum'", 
        	ARRAY_A );
        $einddatum_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'einddatum'", 
        	ARRAY_A );
        $begintijd_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'begintijd'", 
        	ARRAY_A );
        $eindtijd_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'eindtijd'", 
        	ARRAY_A );
        $hele_jaar_beschikbaar_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'hele_jaar_beschikbaar'", 
        	ARRAY_A );
        $televisie_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'televisie'", 
        	ARRAY_A );
        $beamer_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'beamer'", 
        	ARRAY_A );
        $whiteboard_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'whiteboard'", 
        	ARRAY_A );
        $anders_sql = $wpdb->get_results( 
        	"SELECT meta_value FROM $tabel WHERE post_id = $post->ID AND meta_key = 'anders'", 
        	ARRAY_A );

		// Genereer de data voor de hidden fields
		$post_id = get_the_ID();
		$post_titel = get_the_title( $post_id );
		$verzenddatum = date("Y-m-d G:i:s");

		// Render gegevens van de ruimte
		if ( isset($stad_sql[0]) && isset($adres_sql[0]) ) {
			$stad_sql = array_shift($stad_sql);
			$adres_sql = array_shift($adres_sql);

			$stad_naam = implode(", ", $stad_sql);
			$adres_naam = implode(", ", $adres_sql);

			echo "<h4>Locatie</h4>";
			echo "$adres_naam<br>";
			echo "$stad_naam<br>";
		}
		else {
			exit;
		}
		if( isset($begindatum_sql[0]) && isset($einddatum_sql[0]) ) {
			$begindatum_sql = array_shift($begindatum_sql);
			$einddatum_sql = array_shift($einddatum_sql);


			$begindatum = implode(", ", $begindatum_sql);
			$einddatum = implode(", ", $einddatum_sql);


			echo "<h4>Beschikbaarheid</h4>";
			echo "<p>$begindatum - $einddatum<br>";
		} else if( isset($hele_jaar_beschikbaar_sql[0]) ) {
			echo "<h4>Beschikbaarheid</h4>";
			echo "<p>Hele jaar beschikbaar<br>";
		} else {
			exit;
		}

		if( isset($begintijd_sql[0]) && isset($eindtijd_sql[0]) ) {
			$begintijd_sql = array_shift($begintijd_sql);
			$eindtijd_sql = array_shift($eindtijd_sql);

			$begintijd = implode(", ", $begintijd_sql);
			$eindtijd = implode(", ", $eindtijd_sql);

			echo "van $begintijd tot $eindtijd</p>";
		} else {
			exit;
		}

		if( isset($televisie_sql[0]) ) {
			$televisie_sql = array_shift($televisie_sql);

			$televisie = implode(", ", $televisie_sql);

			echo "<h4>Faciliteiten</h4>";
			echo "<strong>Televisie -</strong> aanwezig<br>";
		} else {
			echo "<h4>Faciliteiten</h4>";
			echo "<strong>Televisie -</strong> niet aanwezig<br>";
		}

		if( isset($beamer_sql[0]) ) {
			$beamer_sql = array_shift($beamer_sql);

			$beamer = implode(", ", $beamer_sql);

			echo "<strong>Beamer -</strong> aanwezig<br>";
		} else {
			echo "<strong>Beamer -</strong> niet aanwezig<br>";
		}

		if( isset($whiteboard_sql[0]) ) {
			$whiteboard_sql = array_shift($whiteboard_sql);

			$whiteboard = implode(", ", $whiteboard_sql);

			echo "<strong>Whiteboard -</strong> aanwezig<br>";
		} else {
			echo "<strong>Whiteboard -</strong> niet aanwezig<br>";
		}

		if( isset($anders_sql[0]) ) {
			$anders_sql = array_shift($anders_sql);

			$anders = implode(", ", $anders_sql);

			echo "<h5>Overige faciliteiten</h5>";
			echo "$anders<br>";
		} else {
			exit;
		}

		// Render het formulier
		echo '<form id="reserveren-form" name="reserveren-form" method="post" action="">';
		echo "<input type='hidden' id='reserveren_id_ruimte' value='$post_id' name='reserveren_id_ruimte' />";
		echo "<input type='hidden' id='reserveren_timestamp' value='$verzenddatum' name='reserveren_timestamp' />";
		echo "<input type='hidden' id='reserveren_ruimte' value='$post_titel' name='reserveren_ruimte' />";
		echo "<h2>Reserveren</h2>"; 
		echo '<p><label for="reserveren_datum">Datum</label><br />';
		echo '<input type="date" id="reserveren_datum" name="reserveren_datum" required>';
		echo '<p><label for="reserveren_start_tijd">Begintijd</label><br />';
		echo '<input type="time" id="reserveren_start_tijd" name="reserveren_start_tijd" required>';
		echo '<p><label for="reserveren_eind_tijd">Eindtijd</label><br />';
		echo '<input type="time" id="reserveren_eind_tijd" name="reserveren_eind_tijd" required>';
		echo '<p><label for="reserveren_capaciteit">Capaciteit</label><br />';
		echo '<input type="number" id="reserveren_capaciteit" name="reserveren_capaciteit" required>';
		echo '<p><label for="reserveren_studentennummer">Studentennummer</label><br />';
		echo '<input type="text" id="reserveren_studentennummer" name="reserveren_studentennummer" required><br>';
		echo '<p><label for="reserveren_email">Mailadres</label><br />';
		echo '<input type="email" id="reserveren_email" name="reserveren_email" required><br>';
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