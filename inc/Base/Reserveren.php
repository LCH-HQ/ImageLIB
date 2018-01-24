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

	private $tijden_sql_ruimte;
	private $datum_sql_ruimte;
	private $post_sql_ruimte;
	private $post_id_waarde;

	public function registreren() {

		// Maak de tabel aan voor de reserveringen
		self::maakGereserveerdTable();

		// Maak het mogelijk om de form te renderen op een pagina
		add_shortcode('spacebooker-form', array($this, 'maakForms') );
	}

	private static function maakGereserveerdTable() {
		global $wpdb;
		// Maakt een database aan voor reserveringen wanneer die niet bestaat
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
				UNIQUE(id_reservering)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	// Haal de data op uit de ingevoerde data van de filter
	private function haalFilterDataOp($datum, $begintijd, $eindtijd) {
		global $wpdb;
		global $tijden_sql;
		global $datum_sql;
		global $post_sql;
			
		$table_meta = $wpdb->prefix . "postmeta";
		$table_posts = $wpdb->prefix . "posts";
		$tijden_sql = $wpdb->get_results("SELECT * FROM $table_meta WHERE meta_key = 'begintijd' OR meta_key = 'eindtijd' AND meta_value BETWEEN '$begintijd' AND '$eindtijd'", ARRAY_A);
		$datum_sql = $wpdb->get_results("SELECT * FROM $table_meta WHERE meta_key = 'begindatum' AND meta_value <= '$datum' OR meta_key = 'einddatum' AND meta_value >= '$datum' OR meta_key = 'hele_jaar_beschikbaar' AND meta_value = 'ja'", ARRAY_A);
		$post_sql = $wpdb->get_results("SELECT post_title, ID FROM $table_posts WHERE post_type = 'ruimte_posts' AND post_status = 'publish'", ARRAY_A);

	}

	// Maak forms van de zoekopdracht
	public function maakForms() {
		if (!empty($_POST['ruimte_datum']) && !empty($_POST['ruimte_begintijd']) && !empty($_POST['ruimte_eindtijd'])) {

			$datum = $_POST['ruimte_datum'];
			$begintijd = $_POST['ruimte_begintijd'];
			$eindtijd = $_POST['ruimte_eindtijd'];

			$this->haalFilterDataOp($datum, $begintijd, $eindtijd);
			$this->renderRuimteFilter($datum, $begintijd, $eindtijd);
			$this->verzamelFilterData();
			// Voorkomen van bug met het niet laden van de admin bar
			wp_footer();

		} else {
			$this->renderRuimteFilter('', '', '');			
		}
	}

	private function renderRuimteFilter($datum, $begintijd, $eindtijd) {
		echo 	"<form action='' name='ruimte-zoeken' method='post'>
				<label for='ruimte_datum'>Datum</label>
				<input type='date' name='ruimte_datum' value='$datum'><br>
				<label for='ruimte_begintijd'>Begintijd</label>
				<input type='time' name='ruimte_begintijd' value='$begintijd'><br />
				<label for='ruimte_eindtijd'>Eindtijd</label> 
				<input type='time' name='ruimte_eindtijd' value='$eindtijd'><br>
				<input type='submit' name='ruimte-zoeken-opslaan' value='Submit'>
				</form>";
	}

	// Verzamel alle zoekresultaten uit de zoekopdracht van de filter
	private function verzamelFilterData() {
        global $wpdb;
        global $tijden_sql;
        global $datum_sql;
        global $post_sql;

        $tijden_sql_ruimte = [];
        $datum_sql_ruimte = [];
        $post_sql_ruimte = [];

        // Loop door alle data heen om de post-ids te achterhalen
        for ( $aantalRows = 0 ; $aantalRows < count($tijden_sql) ; $aantalRows++ ) {
            $tijden_sql_ruimte[] = $tijden_sql[$aantalRows]['post_id'];
        }

        for ( $aantalRows = 0 ; $aantalRows < count($datum_sql) ; $aantalRows++ ) {
            if( $datum_sql[$aantalRows]['meta_key'] == "hele_jaar_beschikbaar" ) {
                $datum_sql_ruimte[] = $datum_sql[$aantalRows]['post_id'];
                $datum_sql_ruimte[] = $datum_sql[$aantalRows]['post_id'];
            } else {
                $datum_sql_ruimte[] = $datum_sql[$aantalRows]['post_id'];
            }
        }

        for ( $aantalRows = 0 ; $aantalRows < count($post_sql) ; $aantalRows++ ) {
            $post_sql_ruimte[] = $post_sql[$aantalRows]['ID'];
        }

        // Haal de individuele rijen op uit de database
        // door middel van te controleren met IDs of het dezelfde post is
        for ( $aantalRows = 0 ; $aantalRows < count($post_sql_ruimte); $aantalRows++ ) {
            $id = $post_sql_ruimte[$aantalRows];
            
            $check_datum = array_count_values($datum_sql_ruimte);
            $check_tijd = array_count_values($tijden_sql_ruimte);
            
            if( $check_tijd[$id] == 2 && $check_datum[$id] == 2 ) {

                // SQL
                $table_meta = $wpdb->prefix . "postmeta";
		        $table_posts = $wpdb->prefix . "posts";

		        $post_id_sql = $wpdb->get_results(
		                               "SELECT post_id FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]'",
		                               ARRAY_A );
		        $post_titel_sql = $wpdb->get_results(
		                               "SELECT post_title FROM $table_posts WHERE ID = '$post_sql_ruimte[$aantalRows]'",
		                               ARRAY_A );
		        $post_slug_sql = $wpdb->get_results(
		                               "SELECT post_name FROM $table_posts WHERE ID = '$post_sql_ruimte[$aantalRows]'",
		                               ARRAY_A );
		        $stad_sql = $wpdb->get_results(
		                               "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'stad'",
		                               ARRAY_A );
		        $adres_sql = $wpdb->get_results(
		                                        "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'adres'",
		                                        ARRAY_A );
		        $begindatum_sql = $wpdb->get_results(
		                                             "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'begindatum'",
		                                             ARRAY_A );
		        $einddatum_sql = $wpdb->get_results(
		                                            "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'einddatum'",
		                                            ARRAY_A );
		        $begintijd_sql = $wpdb->get_results(
		                                            "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'begintijd'",
		                                            ARRAY_A );
		        $eindtijd_sql = $wpdb->get_results(
		                                           "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'eindtijd'",
		                                           ARRAY_A );
		        $hele_jaar_beschikbaar_sql = $wpdb->get_results(
		                                            "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'hele_jaar_beschikbaar'",
		                                            ARRAY_A );
		        $televisie_sql = $wpdb->get_results(
		                                            "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'televisie'",
		                                            ARRAY_A );
		        $beamer_sql = $wpdb->get_results(
		                                         "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'beamer'",
		                                         ARRAY_A );
		        $whiteboard_sql = $wpdb->get_results(
		                                             "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'whiteboard'",
		                                             ARRAY_A );
		        $anders_sql = $wpdb->get_results(
		                                         "SELECT meta_value FROM $table_meta WHERE post_id = '$post_sql_ruimte[$aantalRows]' AND meta_key = 'anders'",
		                                         ARRAY_A );
       			
       			// Geef de informatie door om te kunnen renderen
       			self::renderFilterResultaten($post_id_sql, $post_titel_sql, $post_slug_sql, $stad_sql, $adres_sql, $begindatum_sql, $einddatum_sql, $begintijd_sql, $eindtijd_sql, $hele_jaar_beschikbaar_sql, $televisie_sql, $beamer_sql, $whiteboard_sql, $anders_sql);            
            }

        }
        $this->renderReserveringsFormulier();
	}

	// Render alle zoekresultaten op de front-end 
	private static function renderFilterResultaten($post_id, $post_titel, $slug, $stad, $adres, $begindatum, $einddatum, $begintijd, $eindtijd, $jaar, $televisie, $beamer, $whiteboard, $overige_faciliteiten) {
        global $wpdb;
        global $post_id_titel;

        if ( isset($post_id[0]) && isset($post_titel[0]) ) {
 			echo '<input type="checkbox" name="post_naam" value="$post_id_titel">';       	
        } else {
        	echo "&nbsp;";
        }
        if ( isset($stad[0]) && isset($adres[0]) ) {
            $stad = array_shift($stad);
            $adres = array_shift($adres);

            $stad_naam = implode(", ", $stad);
            $adres_naam = implode(", ", $adres);

            echo "<h4>Locatie</h4>";
            echo $adres_naam . "<br>";
            echo $stad_naam . "<br>";
        } else {
            echo '<p>Geen adres beschikbaar</p>';
        }

        if ( isset($begindatum[0]) && isset($einddatum[0]) ) {
            $begindatum = array_shift($begindatum);
            $einddatum = array_shift($einddatum);

            $ruimte_open = implode(", ", $begindatum);
            $ruimte_dicht = implode(", ", $einddatum);

            echo "<h4>Beschikbaarheid</h4>";
            echo "<p>$ruimte_open - $ruimte_dicht<br>";
        } else if ( isset($jaar[0]) ) {
            echo "<h4>Beschikbaarheid</h4>";
            echo "<p>Hele jaar beschikbaar<br>";
        } else {
            echo '<p>Niet beschikbaar</p>';
        }

        if ( isset($begintijd[0]) && isset($eindtijd[0]) ) {
            $begintijd = array_shift($begintijd);
            $eindtijd = array_shift($eindtijd);

            $openingstijd = implode(", ", $begintijd);
            $sluitingstijd = implode(", ", $eindtijd);

            echo "van $openingstijd tot $sluitingstijd</p>";
        } else {
            echo '<p>Hele jaar open</p>';
        }

        if ( isset($televisie[0]) ) {
            echo "<h4>Faciliteiten</h4>";
            echo "<strong>Televisie -</strong> aanwezig<br>";
        } else {
            echo "<h4>Faciliteiten</h4>";
            echo "<strong>Televisie -</strong> niet aanwezig<br>";
        }

        if( isset($beamer[0]) ) {
            echo "<strong>Beamer -</strong> aanwezig<br>";
        } else {
            echo "<strong>Beamer -</strong> niet aanwezig<br>";
        }

        if( isset($whiteboard[0]) ) {
            echo "<strong>Whiteboard -</strong> aanwezig<br>";
        } else {
            echo "<strong>Whiteboard -</strong> niet aanwezig<br>";
        }

        if( isset($overige_faciliteiten[0]) ) {
            $overige_faciliteiten = array_shift($overige_faciliteiten);

            $overige_faciliteiten_aanwezig = implode(", ", $overige_faciliteiten);

            echo "<h5>Overige faciliteiten</h5>";
            echo "$overige_faciliteiten_aanwezig<br>";
        } else {
            echo '&nbsp;';
        }
	}

	// Render het formulier waarmee een ruimte gereserveerd kan worden
	private function renderReserveringsFormulier() {
		// Genereer de data voor de hidden fields
		$verzenddatum = date("Y-m-d G:i:s");

		// Render het formulier
		echo "<h3>Reserveren</h3>";
		echo '<form id="reserveren-form" name="reserveren-form" method="post" action="">'; 
		echo "<input type='hidden' id='reserveren_id_ruimte' value='1' name='reserveren_id_ruimte' />";
		echo "<input type='hidden' id='reserveren_timestamp' value='$verzenddatum' name='reserveren_timestamp' />";
		echo '<input type="hidden" id="reserveren_ruimte" value="C0.09" name="reserveren_ruimte">';
		echo '<p><label for="reserveren_datum">Datum</label><br />';
		echo '<input type="date" id="reserveren_datum" name="reserveren_datum" required>';
		echo '<p><label for="reserveren_start_tijd">Begintijd</label><br />';
		echo '<input type="time" id="reserveren_start_tijd" name="reserveren_start_tijd" required>';
		echo '<p><label for="reserveren_eind_tijd">Eindtijd</label><br />';
		echo '<input type="time" id="reserveren_eind_tijd" name="reserveren_eind_tijd" required>';
		echo '<p><label for="reserveren_studentennummer">Studentennummer</label><br />';
		echo '<input type="text" id="reserveren_studentennummer" name="reserveren_studentennummer" required><br>';
		echo '<p><label for="reserveren_email">Mailadres</label><br />';
		echo '<input type="email" id="reserveren_email" name="reserveren_email" required><br>';
		echo '<input type="hidden" id="reserveren_personen" name="reserveren_personen" value="1"><br>';
		echo '<input type="submit" id="reservering-form-opslaan" name="reservering-form-opslaan" value="Submit">';
		echo '<input type="hidden" name="action" value="reservering_form_nonce" />';
		echo '</form>';

		// Controleer of de data komt van dezelfde website
		wp_nonce_field( 'action','reservering_form_nonce' );

		// In het geval het formulier wordt verzonden,
		// sla het formulier op
		if( isset($_POST['reservering-form-opslaan']) ) {
			$this->slaReserveringFormOp();
		}
	}

	public function slaReserveringFormOp() {
		global $wpdb;

		// Controleer de ingevulde velden van het formulier
		// en geef een foutmelding waar nodig
		if (isset ($_POST['reserveren_id_ruimte'])) {
			$id_ruimte =  $_POST['reserveren_id_ruimte'];
		}
		if (isset ($_POST['reserveren_ruimte']) ) {
			$ruimte = $_POST['reserveren_ruimte'];
		}
		if (isset ($_POST['reserveren_timestamp']) ) {
			$timestamp = $_POST['reserveren_timestamp'];
		}
		if (isset ($_POST['reserveren_datum']) ) {
			$datum = $_POST['reserveren_datum'];
		}
		if (isset ($_POST['reserveren_start_tijd']) ) {
			$start_tijd = $_POST['reserveren_start_tijd'];
		}
		if (isset ($_POST['reserveren_eind_tijd']) ) {
			$eind_tijd = $_POST['reserveren_eind_tijd'];
		}
		if (isset ($_POST['reserveren_studentennummer']) ) {
			$studentennummer = $_POST['reserveren_studentennummer'];
		}
		if (isset ($_POST['reserveren_email']) ) {
			$email = $_POST['reserveren_email'];
		}
		if (isset ($_POST['reserveren_personen']) ) {
			$reserveren = $_POST['reserveren_personen'];
		}

		// Voeg de reservering toe aan de reserveringstabel
		$table_gereserveerd = $wpdb->prefix . "gereserveerd";
		$wpdb->insert( $table_gereserveerd, array(
			'id_ruimte' => $id_ruimte,
			'datum' => $timestamp,
			'reservering_start_tijd' => $start_tijd,
			'reservering_eind_tijd' => $eind_tijd,
			'datum_reservering' => $datum,
			'naam_ruimte' => $ruimte,
			'studentennummer' => $studentennummer,
			'email' => $email,
			'reserveren_personen' => $reserveren)
		);
	}
}
