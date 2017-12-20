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

		$this->stelInstellingenIn();
		$this->stelSectiesIn();
		$this->stelVeldenIn();

		$this->instellingen->paginasToevoegen( $this->paginas )->metSubPagina( 'Dashboard' )->subPaginasToevoegen( $this->subpaginas)->registreren();

		// Activeer submenu voor Ruimtes
		add_action('admin_menu', array( $this, 'activeerRuimteCPT') );
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

	/*
	 * Genereer CPT voor de ruimtes
	 */

	public function activeerRuimteCPT() {

		$enkelvoud = 'ruimte';
		$meervoud = 'ruimtes';

		register_post_type( 'ruimte',
		    array(
		            'labels' => array(
		                    'name' => __( 'Ruimtes' ),
		                    'singular_name' => __( 'Ruimte' ),
		                    'add_new_item' => ( "Nieuwe $enkelvoud toevoegen" ),
		                    'edit_item' => ( "Bewerk $enkelvoud" ),
		                    'new_item' => ( "Nieuwe $enkelvoud" ),
		                    'view_item' => ( "Bekijk $enkelvoud" ),
		                    'view_items' => ( "Bekijk $meervoud" ),
		                    'search_items' => ( "Zoeken naar $meervoud" ),
		                    'not_found' => ( "Geen $meervoud gevonden" ),
		                    'not_found_in_trash' => ( "Geen $meervoud gevonden in de prullenbak" ),
		                    'all_items' => ( "Alle $meervoud" ),
		                    'archives' => ( "Archief voor $meervoud" ),
		                    'attributes' => ( "Attributen voor $meervoud"),
		                    'insert_into_item' => ( "Voeg in $enkelvoud" ),
		                    'uploaded_to_this_item' => ( "Geüpload naar deze $enkelvoud" )
		            ),
		    'public' => true,
		    'publicly_queryable' => true,
		    'has_archive' => true,
		    'menu-position' => 5,
		    'capability_type' => 'post',
		    'show_in_menu' => 'spacebooker',
		    'map_meta_cap' => true,
		    'rewrite' => array( 'slug' => 'ruimtes', 'with_front' => true, 'pages' => true, 'feeds' => true),
		    'supports' => array('title', 'custom-fields', 'page-attributes', 'post-formats')
		    )
		);

		add_submenu_page('spacebooker', 'Ruimtes beheren', 'Ruimtes', 'manage_options', 'edit.php?post_type=ruimte');
	}

	/*
	 * Genereer de custom fields voor de plug-in in de back-end
	 */

	// Stel de instellingen in voor de custom fields
	public function stelInstellingenIn()
	{
		$args = array(
			array(
				'option_group' => 'optie_groep_voorbeeld',
				'option_name' => 'tekst_voorbeeld',
				'callback' => array( $this->callbacks, 'optieGroepVoorbeeld' )
			),
			array(
				'option_group' => 'optie_groep_voorbeeld',
				'option_name' => 'tekst_dropdown',
				'callback' => array( $this->callbacks, 'optieGroepDropdown' )
			),
			array(
				'option_group' => 'optie_groep_voorbeeld',
				'option_name' => 'radio_buttons',
				'callback' => array( $this->callbacks, 'optieGroepRadioButtons' )
			),
			array(
				'option_group' => 'optie_groep_voorbeeld',
				'option_name' => 'check_box',
				'callback' => array( $this->callbacks, 'optieGroepCheckbox' )
			),
			array(
				'option_group' => 'optie_groep_voorbeeld',
				'option_name' => 'omschrijving_box',
				'callback' => array( $this->callbacks, 'optieGroepCheckbox' )
			)
		);

		$this->instellingen->stelInstellingenIn( $args );
	}

	// Stel de secties in waar de custom fields in geplaatst worden
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

	// Stel de velden (van inputs naar dropdowns) in voor de secties
	// en plaats deze in de secties van de subpagina's
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
			),
			array(
				// Moet identiek zijn aan de naam van de option_name in stelInstellingenIn();
				'id' => 'tekst_dropdown',
				'title' => 'Specificeer het soort ruimte',
				'callback' => array( $this->callbacks, 'spaceBookerDropdown'),
				// Gebruik hiervoor de menu_slug van de (sub)pagina
				'page' => 'spacebooker',
				// Moet identiek zijn aan de id van stelSectiesIn();
				'section' => 'sectie_id_voorbeeld',
				'args' => array(
					'label_for' => 'tekst_dropdown',
					'class' => 'voorbeeld_class'
				)
			),
			array(
				// Moet identiek zijn aan de naam van de option_name in stelInstellingenIn();
				'id' => 'radio_buttons',
				'title' => 'Dit is de titel van het dropdown',
				'callback' => array( $this->callbacks, 'spaceBookerRadioButtons'),
				// Gebruik hiervoor de menu_slug van de (sub)pagina
				'page' => 'spacebooker',
				// Moet identiek zijn aan de id van stelSectiesIn();
				'section' => 'sectie_id_voorbeeld',
				'args' => array(
					'label_for' => 'radio_buttons',
					'class' => 'voorbeeld_class'
				)
			),
			array(
				// Moet identiek zijn aan de naam van de option_name in stelInstellingenIn();
				'id' => 'check_box',
				'title' => 'checkbox',
				'callback' => array( $this->callbacks, 'spaceBookerRadioCheckbox'),
				// Gebruik hiervoor de menu_slug van de (sub)pagina
				'page' => 'spacebooker',
				// Moet identiek zijn aan de id van stelSectiesIn();
				'section' => 'sectie_id_voorbeeld',
				'args' => array(
					'label_for' => 'check_box',
					'class' => 'voorbeeld_class'
				)
			),
			array(
				// Moet identiek zijn aan de naam van de option_name in stelInstellingenIn();
				'id' => 'omschrijving_box',
				'title' => 'omschrijving',
				'callback' => array( $this->callbacks, 'spaceBookerOmschrijving'),
				// Gebruik hiervoor de menu_slug van de (sub)pagina
				'page' => 'spacebooker',
				// Moet identiek zijn aan de id van stelSectiesIn();
				'section' => 'sectie_id_voorbeeld',
				'args' => array(
					'label_for' => 'omschrijving_box',
					'class' => 'voorbeeld_class'
				)
			)
		);

		$this->instellingen->stelVeldenIn( $args );
	}
}
