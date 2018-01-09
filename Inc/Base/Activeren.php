<?php
/**
 *  @package SpaceBooker
 */
namespace Inc\Base;

/*
 * Activeren van de plug-in
 */

class Activeren
{
	public static function activeren() {
	    // Controleer of de benodigde plug-ins geïnstalleerd zijn
		    if ( ! is_plugin_active( 'google-calendar-events/google-calendar-events.php' ) and current_user_can( 'activate_plugins' ) ) {
		        // Stop de activering van de plug-in
		        wp_die('<h1>Oh nee! :(</h1><p>SpaceBooker kan niet worden geactiveerd omdat er een benodigde plugin niet actief is!<br>De volgende plugin is niet actief of mogelijk niet geïnstaleerd:</p><ul><li>Simple Calendar | <a href="https://wordpress.org/plugins/google-calendar-events/">Downloaden</a></li></ul><p><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Ga terug naar plugins</a></p>');
		    } else {
		    	flush_rewrite_rules();
		    }

		}
}
