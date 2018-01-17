<?php
/**
 *
 *  @package SpaceBooker
 *
 */
/*
Plugin Name: SpaceBooker
Plugin URI:
Description: Een plug-in waarmee je gemakkelijk ruimtes kunt boeken.
Version: 0.9
Author: Lenin Chipantiza, Jim Kraan, Jeffry Pas
Author URI:
License: GPLv2 or later
Text Domain: SpaceBooker
*/

// Wanneer de plug-in buiten WordPress opgeroepen wordt, stop de gehele plug-in
defined( 'ABSPATH' ) or die( 'Better look somewhere else, mate.' );

// Composer's AutoLoader inladen
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/*
 * Activatie en deactivatie van de plug-in
 */

// Activeer plug-in
function activeerSpaceBooker() {
	Inc\Base\Activeren::activeren();
}
register_activation_hook( __FILE__, 'activeerSpaceBooker');

// Deactiveer plug-in
function deactiveerSpaceBooker() {
	Inc\Base\Deactiveren::deactiveren();
}
register_deactivation_hook( __FILE__, 'deactiveerSpaceBooker');

// Als de initalisatie-class beschikbaar is, voer alle plug-in services uit
if ( class_exists( 'Inc\\Init') ) {
	Inc\Init::registreerServices();
}