<?php

/**
*   @package SpaceBooker
*/
namespace Inc\Base;

/*
 * Opstart-service voor SpaceBooker bij het activeren van de plug-in
 */

use \Inc\Base\BaseController;

class Opstarten extends BaseController
{
	// Start de services voor de plug-in
	public function registreren() {
		add_action( 'admin_enqueue_scripts', array( $this, 'opstartWachtrij' ) );
	}

	// Lokaliseer alle services van de plug-in
	function opstartWachtrij() {
		// enqueue all our scripts
		wp_enqueue_style( 'spacebooker_styling', $this->plugin_url . 'assets/spacebookerstyle.css' );
		wp_enqueue_style( 'fullcalendar_styling', $this->plugin_url . 'vendor/fullcalendar/fullcalendar.min.css' );
		wp_enqueue_script( 'jquery_script', $this->plugin_url . 'vendor/fullcalendar/lib/jquery.min.js' );
		wp_enqueue_script( 'moment_script', $this->plugin_url . 'vendor/fullcalendar/lib/moment.min.js' );
		wp_enqueue_script( 'locale_script', $this->plugin_url . 'vendor/fullcalendar/nl.js' );
		wp_enqueue_script( 'fullcalendar_script', $this->plugin_url . 'vendor/fullcalendar/fullcalendar.min.js' );
	}

}