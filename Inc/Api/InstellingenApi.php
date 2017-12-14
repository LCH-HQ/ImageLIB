<?php
/**
*   @package SpaceBooker
*/
namespace Inc\Api;

/*
 * Opstart-service voor SpaceBooker bij het activeren van de plug-in
 */

class InstellingenApi
{
	public $admin_paginas = array();

	public $admin_subpaginas = array();

	// Zal niet botsen met "instellingen" uit de Admin-class
	// omdat deze niet 'extended' wordt naar InstellingenApi
	public $instellingen = array();

	public $secties = array();

	public $velden = array();

	/*
	 * Verwerk alle gegevens voor de pagina en subpagina's
	 */

	// In het geval dat de pagina's en instellingen niet leeg zijn,
	// update de gegevens van de pagina's en de custom fields
	public function registreren()
	{
		if ( ! empty($this->admin_paginas) ) {
			add_action( 'admin_menu', array( $this, 'adminMenuToevoegen') );
		}

		if ( ! empty($this->instellingen) ) {
			add_action( 'admin_init', array( $this, 'registreerCustomFields' ) );
		}
	}

	// Voeg de admin pagina's toe
	public function paginasToevoegen( array $paginas)
	{
		$this->admin_paginas = $paginas;

		return $this;
	}

	// Verwerk de gegevens van de subpagina's
	// en geef een custom title mee aan het eerste child in de subpagina's
	public function metSubPagina( string $title = null )
	{
		// In het geval dat er geen custom title is meegegeven,
		// print de standaardtitel van de parent pagina
		if ( empty($this->admin_paginas) ) {
			return $this;
		}

		$admin_pagina = $this->admin_paginas[0];

		// Genereer de subpagina's
		$subpagina = array(
			array(
				'parent_slug' => $admin_pagina['menu_slug'],
				'page_title' => $admin_pagina['page_title'],
				'menu_title' => ($title) ? $title : $admin_pagina['menu_title'],
				'capability' => $admin_pagina['capability'],
				'menu_slug' => $admin_pagina['menu_slug'],
				'callback' => $admin_pagina['callback']
			)
		);

		$this->admin_subpaginas = $subpagina;

		return $this;

	}

	// Voeg de subpagina's toe aan de back-end
	public function subPaginasToevoegen( array $paginas)
	{
		$this->admin_subpaginas = array_merge( $this->admin_subpaginas, $paginas );

		return $this;
	}

	// Loop door alle pagina's en subpagina's heen voordat ze toegevoegd worden
	public function adminMenuToevoegen()
	{
		foreach($this->admin_paginas as $pagina) {
			add_menu_page( $pagina['page_title'], $pagina['menu_title'], $pagina['capability'], $pagina['menu_slug'], $pagina['callback'], $pagina['icon_url'], $pagina['position']);
		}

		foreach($this->admin_subpaginas as $pagina) {
			add_submenu_page( $pagina['parent_slug'], $pagina['page_title'], $pagina['menu_title'], $pagina['capability'], $pagina['menu_slug'], $pagina['callback'] );
		}
	}

	/*
	 * Verwerk alle gegevens van de custom fields
	 */

	// Stel alle instellingen in van de custom fields
	public function stelInstellingenIn( array $instellingen)
	{
		$this->instellingen = $instellingen;

		return $this;
	}

	// Stel alle secties in van de custom fields
	public function stelSectiesIn( array $secties)
	{
		$this->secties = $secties;

		return $this;
	}

	// Stel alle velden in van de custom fields
	public function stelVeldenIn( array $velden)
	{
		$this->velden = $velden;

		return $this;
	}

	// Loop door alle custom fields heen voordat ze toegevoegd worden
	public function registreerCustomFields()
	{
		// Registreer de instellingen
		foreach($this->instellingen as $instelling) {
			register_setting( $instelling['option_group'], $instelling['option_name'], ( isset( $instelling['callback'] ) ? $instelling['callback'] : '' ) );
		}

		// Voeg de instellingen-sectie toe
		foreach($this->secties as $sectie) {
			add_settings_section( $sectie['id'], $sectie['title'], ( isset( $sectie['callback'] ) ? $sectie['callback'] : '' ), $sectie['page'] );
		}

		// Voeg instelling-velden toe
		foreach($this->velden as $veld) {
			add_settings_field( $veld['id'], $veld['title'], ( isset( $veld['callback'] ) ? $veld['callback'] : '' ), $veld['page'], $veld['section'], ( isset( $veld['args'] ) ? $veld['args'] : '' ) );
		}
	}

}
