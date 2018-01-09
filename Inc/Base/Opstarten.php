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
		wp_enqueue_style( 'styling', $this->plugin_url . 'assets/spacebookerstyle.css' );

	}







}
