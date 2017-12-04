<?php
/**
 *  @package SpaceBooker
 */

namespace Inc\Base;

/*
 * Constante variabelen definiëren voor paden doorgaans de plug-in
 */

class BaseController 
{
	public $plugin_pad;

	public $plugin_url;
	
	public $plugin;

	public function __construct() {
		$this->plugin_pad = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/spacebooker-plugin.php';
	}
}