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
		    	flush_rewrite_rules();
		    }
}