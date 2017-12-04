<?php

/**
 *   @package SpaceBooker
 */
namespace Inc\Base;

/*
 * Voeg een 'Instellingen'-link toe aan de plug-in in WordPress-instellingen
 */

use \Inc\Base\BaseController;

class InstellingenLinks extends BaseController
{
	// Roep de functionaliteit aan van de 'Instellingen'
	public function registreren() {
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'instellingenLink' ) );
	}

	// Plaats de 'Instellingen'-link aan de plug-in sectie toe
	public function instellingenLink( $links ) {
		$instellingen_link = '<a href="admin.php?page=spacebooker">Instellingen</a>';
		array_push( $links, $instellingen_link );
		return $links;
	}

}