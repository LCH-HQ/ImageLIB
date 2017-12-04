<?php

/*
 *   @package SpaceBooker
 */
namespace Inc\Pages;

/*
 * Genereer een sectie voor SpaceBooker in de WordPress back-end
 */

use Inc\Base\BaseController;
use Inc\Api\InstellingenApi;
use Inc\Api\Callbacks\AdminCallbacks;

class Admin extends BaseController
{

	public $instellingen;

	public $callbacks;

	public $paginas = array();

	public $subpaginas = array();

	public function registreren() 
	{ 
		$this->instellingen = new InstellingenApi();

		$this->callbacks = new AdminCallbacks(); 

		$this->plaatsPaginas();

		$this->plaatsSubPaginas();

		$this->stelInstellingenIn();
		$this->stelSectiesIn();
		$this->stelVeldenIn();

		$this->instellingen->paginasToevoegen( $this->paginas )->metSubPagina( 'Dashboard' )->subPaginasToevoegen( $this->subpaginas)->registreren();
	}

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

	public function plaatsSubPaginas() 
	{
		$this->subpaginas = array(
			array(
				'parent_slug' => 'spacebooker',
				'page_title' => 'Ruimtes beheren',
				'menu_title' => 'Ruimtes',
				'capability' => 'manage_options',
				'menu_slug' => 'spacebooker_ruimtes',
				'callback' => array( $this->callbacks, 'adminRuimtes')
			),
			array(
				'parent_slug' => 'spacebooker',
				'page_title' => 'Reserveringen',
				'menu_title' => 'Reserveringen',
				'capability' => 'manage_options',
				'menu_slug' => 'spacebooker_reserveringen',
				'callback' => array( $this->callbacks, 'adminReserveringen')
			),
			array(
				'parent_slug' => 'spacebooker',
				'page_title' => 'Gebruikers',
				'menu_title' => 'Gebruikers',
				'capability' => 'manage_options',
				'menu_slug' => 'spacebooker_gebruikers',
				'callback' => array( $this->callbacks, 'adminGebruikers')
			)
		);
	}

	public function stelInstellingenIn() 
	{
		$args = array(
			array(
				'option_group' => 'optie_groep_voorbeeld',
				'option_name' => 'tekst_voorbeeld',
				'callback' => array( $this->callbacks, 'optieGroepVoorbeeld' )
			)
		);

		$this->instellingen->stelInstellingenIn( $args );
	}

	public function stelSectiesIn() 
	{
		$args = array(
			array(
				'id' => 'sectie_id_voorbeeld',
				'title' => 'Dit is de titel van de sectie',
				'callback' => array( $this->callbacks, 'optieGroepSectie'),
				// Gebruik hiervoor de menu_slug van de (sub)pagina
				'page' => 'spacebooker'

			)
		);

		$this->instellingen->stelSectiesIn( $args );
	}

	public function stelVeldenIn() 
	{
		$args = array(
			array(
				// Moet identiek zijn aan de naam van de option_name in stelInstellingenIn();
				'id' => 'tekst_voorbeeld',
				'title' => 'Dit is de titel van het invoerveld',
				'callback' => array( $this->callbacks, 'spaceBookerTekstveldVoorbeeld'),
				// Gebruik hiervoor de menu_slug van de (sub)pagina
				'page' => 'spacebooker',
				// Moet identiek zijn aan de id van stelSectiesIn();
				'section' => 'sectie_id_voorbeeld',
				'args' => array(
					'label_for' => 'tekst_voorbeeld',
					'class' => 'voorbeeld_class'
				)
			)
		);

		$this->instellingen->stelVeldenIn( $args );
	}
}