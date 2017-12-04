<?php
/**
 *  @package SpaceBooker
 */
namespace Inc\Base;

/*
 * Deactiveren van de plug-in
 */

class Deactiveren
{
	public static function deactiveren() {
		flush_rewrite_rules();
	}
}