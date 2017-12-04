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
	public function registreren() {
		add_action( 'admin_enqueue_scripts', array( $this, 'opstartWachtrij' ) );
	}

	function opstartWachtrij() {
		// enqueue all our scripts
		wp_enqueue_style( 'styling', $this->plugin_url . 'assets/spacebookerstyle.css' );
	}

}