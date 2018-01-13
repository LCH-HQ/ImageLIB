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

	public $posts;

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
		add_action('admin_menu', array( $this, 'genereerRuimteCPT') );
		add_action( 'init', array( $this, 'genereerRuimteCPT') );
		add_action( 'save_post', array($this, 'slaRuimteVeldenOp'), 1, 2 );
 
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

	public function genereerRuimteCPT() {

		$enkelvoud = 'ruimte';
		$meervoud = 'ruimtes';

	        $labels = array(
	                'name' => _x( 'Ruimtes', 'De ruimtes' ),
	                'singular_name' => _x( 'Ruimte', 'De ruimte' ),
	                'add_new_item' => _x( "Nieuwe $enkelvoud toevoegen", 'ruimte' ),
	                'edit_item' => __( "Bewerk $enkelvoud" ),
	                'new_item' => __( "Nieuwe $enkelvoud" ),
	                'view_item' => __( "Bekijk $enkelvoud" ),
	                'view_items' => __( "Bekijk $meervoud" ),
	                'search_items' => __( "Zoeken naar $meervoud" ),
	                'not_found' => __( "Geen $meervoud gevonden" ),
	                'not_found_in_trash' => ( "Geen $meervoud gevonden in de prullenbak" ),
	                'all_items' => __( "Ruimtes" ),
	                'archives' => __( "Archief voor $meervoud" ),
	                'attributes' => __( "Attributen voor $meervoud"),
	                'insert_into_item' => __( "Voeg in $enkelvoud" ),
	                'uploaded_to_this_item' => __( "Geüpload naar deze $enkelvoud" ),
	                'menu_name' => __('Ruimtes')
	        );
	    	$args = array(
			    'labels' => $labels,
			    'public' => true,
			    'publicly_queryable' => true,
			    'show_ui' => true,
			  	'query_var' => true,
			    'menu-position' => 60,
			    'capability_type' => 'post',
			    'show_in_menu' => 'spacebooker',
			    'has_archive' => true,
			    'map_meta_cap' => true,
			    'rewrite' => array( 'slug' => _x( "$enkelvoud", "$meervoud") ),
			    'supports' => array('title'),
			    // Voeg custom fields doe aan de CPT
			    'register_meta_box_cb' => array($this, 'ruimteCPTVelden'),
		    );

	    // Maak CPT aan en refresh de permalinks in WP
		register_post_type('ruimte_posts', $args);
		flush_rewrite_rules();
	}

	/*
	 * Genereer de custom fields voor de plug-in in de back-end
	 */

	public function ruimteCPTVelden() {
		add_meta_box(
			'ruimteLocatie',
			'Locatie',
			array($this, 'ruimteLocatie'),
			'ruimte_posts',
			'normal',
			'default'
		);

		add_meta_box(
			'ruimteBeschikbaarheid',
			'Beschikbaarheid',
			array($this, 'ruimteBeschikbaarheid'),
			'ruimte_posts',
			'normal',
			'default'
		);

		add_meta_box(
			'ruimteAttributen',
			'Attributen',
			array($this, 'ruimteAttributen'),
			'ruimte_posts',
			'normal',
			'default'
		);
	}

	public function ruimteLocatie() {
		global $post;
		// Nonce field valideren of het van de huidige site komt
		wp_nonce_field( basename( __FILE__ ), 'locatie_velden' );
		// Haal de data op uit de velden
		$stad = get_post_meta( $post->ID, 'stad', true );
		$adres = get_post_meta( $post->ID, 'adres', true );
		$extra_details = get_post_meta( $post->ID, 'extra_details', true );
		// Render het veld
		echo '<span class="post-attributes-label">Stad</span>';
		echo '<input type="text" name="stad" value="' . esc_textarea( $stad )  . '" class="widefat">';
		echo '<span class="post-attributes-label">Adres</span>';
		echo '<input type="text" name="adres" value="' . esc_textarea( $adres )  . '" class="widefat">';
		echo '<span class="post-attributes-label">Extra details</span>';
		echo '<input type="text" name="extra_details" value="' . esc_textarea( $extra_details )  . '" class="widefat">';
	}

	public function ruimteBeschikbaarheid() {
		global $post;
		// Nonce field valideren of het van de huidige site komt
		wp_nonce_field( basename( __FILE__ ), 'beschikbaarheid_velden' );
		// Haal de data op uit de velden
		$begindatum = get_post_meta( $post->ID, 'begindatum', true );
		$einddatum = get_post_meta( $post->ID, 'einddatum', true );
		$begintijd = get_post_meta( $post->ID, 'begintijd', true );
		$eindtijd = get_post_meta( $post->ID, 'eindtijd', true );
		$hele_jaar_beschikbaar = get_post_meta( $post->ID, 'hele_jaar_beschikbaar', true );
		// Render het veld
		echo '<select id="beschikbaarheid_dropdown" name="beschikbaarheid_dropdown" onchange="verbergInputVelden()">';
		echo '<option value="hele_jaar_beschikbaar" name="hele_jaar_beschikbaar">Hele jaar beschikbaar</option>';
		echo '<option id="beperkt_beschikbaar" value="beperkt_beschikbaar" name="beperkt_beschikbaar">Beperkt beschikbaar</option>';
		echo '</select>';
		echo '<span class="post-attributes-label">Begindatum</span>';
		echo '<input type="date" id="begindatum" name="begindatum" value="' . esc_textarea( $begindatum )  . '" class="widefat">';
		echo '<span class="post-attributes-label">Einddatum</span>';
		echo '<input type="date" id="einddatum" name="einddatum" value="' . esc_textarea( $einddatum )  . '" class="widefat">';
		echo '<span class="post-attributes-label">Begintijd</span>';
		echo '<input type="time" id="begintijd" name="begintijd" value="' . esc_textarea( $begintijd )  . '" class="widefat">';
		echo '<span class="post-attributes-label">Eindtijd</span>';
		echo '<input type="time" id="eindtijd" name="eindtijd" value="' . esc_textarea( $eindtijd )  . '" class="widefat">';
	}

	public function ruimteAttributen() {
		global $post;
		// Nonce field valideren of het van de huidige site komt
		wp_nonce_field( basename( __FILE__ ), 'attributen_velden' );
		// Haal de data op uit de velden
		$televisie = get_post_meta( $post->ID, 'televisie', true );
		$beamer = get_post_meta( $post->ID, 'beamer', true );
		$whiteboard = get_post_meta( $post->ID, 'whiteboard', true );
		$anders = get_post_meta( $post->ID, 'anders', true );
		// Render het veld
		echo '<span class="post-attributes-label">Televisie</span>';
		// echo '<input type="checkbox" name="televisie" class="widefat" >';
		// echo '<span class="post-attributes-label">Beamer</span>';
		// echo '<input type="checkbox" name="beamer" value="' . esc_attr__( $beamer )  . '" class="widefat">';
		// echo '<span class="post-attributes-label">Whiteboard</span>';
		// echo '<input type="checkbox" name="whiteboard" value="' . esc_attr__( $whiteboard )  . '" class="widefat">';
		// echo '<span class="post-attributes-label">Anders, namelijk...</span>';
		echo '<input type="text" name="anders" value="' . esc_textarea( $anders )  . '" class="widefat">';
	}

	public function slaRuimteVeldenOp( $post_id, $post ) {
		// Weiger toegang als de gebruiker niet de juiste gebruikersrechten heeft
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		// Verifiëren dat het opslaan van dezelfde gebruiker komt,
		// in het geval dat iemand anders toegang wilt krijgen tot het opslaan
		if ( ! isset( $_POST['stad'] ) || ! wp_verify_nonce( $_POST['locatie_velden'], basename(__FILE__) ) ) {
			return $post_id;
		}
		if ( ! isset( $_POST['adres'] ) || ! wp_verify_nonce( $_POST['locatie_velden'], basename(__FILE__) ) ) {
			return $post_id;
		}
		if ( ! isset( $_POST['extra_details'] ) || ! wp_verify_nonce( $_POST['locatie_velden'], basename(__FILE__) ) ) {
			return $post_id;
		}
		if ( ! isset( $_POST['begindatum'] ) || ! wp_verify_nonce( $_POST['beschikbaarheid_velden'], basename(__FILE__) ) ) {
			return $post_id;
		}
		if ( ! isset( $_POST['einddatum'] ) || ! wp_verify_nonce( $_POST['beschikbaarheid_velden'], basename(__FILE__) ) ) {
			return $post_id;
		}
		if ( ! isset( $_POST['begintijd'] ) || ! wp_verify_nonce( $_POST['beschikbaarheid_velden'], basename(__FILE__) ) ) {
			return $post_id;
		}
		if ( ! isset( $_POST['eindtijd'] ) || ! wp_verify_nonce( $_POST['beschikbaarheid_velden'], basename(__FILE__) ) ) {
			return $post_id;
		}
		// if ( ! isset( $_POST['televisie'] ) || ! wp_verify_nonce( $_POST['attributen_velden'] ? true : false, basename(__FILE__) ) ) {
		// 	return $post_id;
		// }
		// if ( ! isset( $_POST['beamer'] ) || ! wp_verify_nonce( $_POST['attributen_velden'], basename(__FILE__) ) ) {
		// 	return $post_id;
		// }
		// if ( ! isset( $_POST['whiteboard'] ) || ! wp_verify_nonce( $_POST['attributen_velden'], basename(__FILE__) ) ) {
		// 	return $post_id;
		// }
		if ( ! isset( $_POST['anders'] ) || ! wp_verify_nonce( $_POST['attributen_velden'], basename(__FILE__) ) ) {
			return $post_id;
		}
		// Nadat de authenticatie is gelukt, slaan we op.
		// Data uit de velden opslaan in een array genaamd $ruimtes_meta
		$ruimtes_meta['stad'] = esc_textarea( $_POST['stad'] );
		$ruimtes_meta['adres'] = esc_textarea( $_POST['adres'] );
		$ruimtes_meta['extra_detais'] = esc_textarea( $_POST['extra_details'] );
		$ruimtes_meta['begindatum'] = esc_attr__($_POST['begindatum'] );
		$ruimtes_meta['einddatum'] = esc_textarea($_POST['einddatum'] );
		$ruimtes_meta['begintijd'] = esc_textarea($_POST['begintijd'] );
		$ruimtes_meta['eindtijd'] = esc_textarea($_POST['eindtijd'] );
		// $ruimtes_meta['televisie'] = esc_attr__($_POST['televisie'] ? true : false);
		// $ruimtes_meta['beamer'] = esc_attr__($_POST['beamer'] );
		// $ruimtes_meta['whiteboard'] = esc_attr__($_POST['whiteboard'] );
		$ruimtes_meta['anders'] = esc_textarea($_POST['anders'] );
		// Neem de $ruimtes_meta array door
		foreach ( $ruimtes_meta as $key => $value ) :
			// Sla niet twee keer dezelfde data op
			if ( 'revision' === $post->post_type ) {
				return;
			}
			if ( get_post_meta( $post_id, $key, false ) ) {
				// In het geval er al data geschreven is naar db, updaten
				update_post_meta( $post_id, $key, $value );
			} else {
				// Als het veld geen data bevat, voeg de data toe
				add_post_meta( $post_id, $key, $value);
			}
			if ( ! $value ) {
				// Verwijder de meta key als er geen data in het veld zit
				delete_post_meta( $post_id, $key );
			}
		endforeach;
	}

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
				'title' => 'Dit is de titel van het dropdown',
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
			)
		);

		$this->instellingen->stelVeldenIn( $args );
	}
}