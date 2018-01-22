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

	public $naam_ruimte;

	public function adminDashboard()
	{
		return require_once( "$this->plugin_pad/templates/Admin.php");
	}

	public function adminAgenda()
	{
		return require_once( "$this->plugin_pad/templates/Agenda.php");
	}

	public function adminReserveringen()
	{
		return require_once( "$this->plugin_pad/templates/Reserveringen.php");
	}

    public function loopDoorReserveringen() {
        // Haal de data op uit de database
        global $wpdb;
        $tabel = $wpdb->prefix . "gereserveerd";
        $reservering_sql = $wpdb->get_results( "SELECT * FROM $tabel", ARRAY_A );

        // Render de reserverings-objecten in JavaScript
        for( $aantalRows = 0 ; $aantalRows < count($reservering_sql) ; $aantalRows++ ) {
		echo '
		    $("#calendar").fullCalendar("renderEvent",
		    {';
				
				// Sla de data op in variabelen
		    	$reservering_start_tijd = $reservering_sql[$aantalRows]['reservering_start_tijd'];
		    	$reservering_eind_tijd = $reservering_sql[$aantalRows]['reservering_eind_tijd'];
		    	$reservering_datum = $reservering_sql[$aantalRows]['datum_reservering'];
		
				// Maak de tijden en datum leesbaar voor de agenda
		    	$reservering_begin = $reservering_datum . "T" .$reservering_start_tijd . "Z";
		    	$reservering_einde = $reservering_datum . "T" .$reservering_eind_tijd . "Z";

		        echo "title: '" . htmlspecialchars($reservering_sql[$aantalRows]['naam_ruimte']) . "',";
		        echo "start: '" . htmlspecialchars($reservering_begin) . "',";
		        echo "end: '" . htmlspecialchars($reservering_einde) . "'
		    });";
		    }
    }
}