<?php

/*
 *   @package SpaceBooker
 */
namespace Inc\Pages;

/*
 * Genereer een sectie voor SpaceBooker in de WordPress back-end
 */

// Paden definiëren voor de classes
use Inc\Base\BaseController;
use Inc\Api\InstellingenApi;
use Inc\Api\Callbacks\AdminCallbacks;

class Admin extends BaseController
{

	public $instellingen;

	public $callbacks;

	public $paginas = array();

	public $subpaginas = array();

	// Verwerk de pagina, subpagina's en content en push deze naar de back-end
	public function registreren() 
	{ 
		$this->instellingen = new InstellingenApi();

		$this->callbacks = new AdminCallbacks();

		$this->plaatsPaginas();

		$this->plaatsSubPaginas();

		$this->instellingen->paginasToevoegen( $this->paginas )->metSubPagina( 'Overzicht' )->subPaginasToevoegen( $this->subpaginas)->registreren();
	}

	// Genereer de pagina van SpaceBooker voor de back-end
	public function plaatsPaginas() 
	{
		$this->paginas = array(
			array(
				'page_title' => 'SpaceBooker Plugin',
				'menu_title' => 'SpaceBooker',
				'capability' => 'manage_options',
				'menu_slug' => 'spacebooker',
				'callback' => array( $this->callbacks, 'adminDashboard'),
				'icon_url' => 'dashicons-store',
				'position' => 110
			)
		);
	}

	// Genereer de subpagina's voor SpaceBooker voor de back-end
	public function plaatsSubPaginas() 
	{
		$this->subpaginas = array(
			array(
				'parent_slug' => 'spacebooker',
				'page_title' => 'Agenda',
				'menu_title' => 'Agenda',
				'capability' => 'manage_options',
				'menu_slug' => 'spacebooker_agenda',
				'callback' => array( $this->callbacks, 'adminAgenda')
			),
			array(
				'parent_slug' => 'spacebooker',
				'page_title' => 'Reserveringen',
				'menu_title' => 'Reserveringen',
				'capability' => 'manage_options',
				'menu_slug' => 'spacebooker_reserveringen',
				'callback' => array( $this->callbacks, 'adminReserveringen')
			)
		);
	}
}