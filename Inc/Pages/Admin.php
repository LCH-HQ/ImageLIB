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
			    'menu-position' => 5,
			    'capability_type' => 'post',
			    'show_in_menu' => 'spacebooker',
			    'has_archive' => true,
			    'map_meta_cap' => true,
			    'rewrite' => array( 'slug' => _x( 'ruimte', 'ruimtes') ),
			    'supports' => array('title', 'custom-fields', 'page-attributes', 'post-formats')
		    );

		register_post_type('ruimte_posts', $args);

		flush_rewrite_rules();

		add_shortcode( 'print_ruimtes', array( $this, 'activeerRuimteCPT' ) );
		add_shortcode( 'custom-form', array($this, 'printRuimteFormulier') );
	}

	public function activeerRuimteCPT() {
		$print_alle_posts = get_posts( array(
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'post_parent' => null
		) );

		if ( $print_alle_posts ) {
			foreach ( $print_alle_posts as $post ) {
				setup_postdata( $post );
				the_title();
				the_meta();
			}
			wp_reset_postdata();
		}
	}

	public function printRuimteFormulier() {
		?>
		<form id="custom-post-type" name="custom-post-type" method="post" action="">
		 
		<p><label for="title">Post Title</label><br />
		 
		<input type="text" id="title" value="" tabindex="1" size="20" name="title" />
		 
		</p>
		 
		<p><?php wp_dropdown_categories( 'show_option_none=Category&tab_index=4&taxonomy=category' ); ?></p>
		
		 
		<p align="right"><input type="submit" value="Publish" tabindex="6" id="submit" name="submit" /></p>
		 
		<input type="hidden" name="post-type" id="post-type" value="ruimte_posts" />
		 
		<input type="hidden" name="action" value="ruimte_posts" />
		 
		<?php wp_nonce_field( 'name_of_my_action','name_of_nonce_field' ); ?>
		 
		</form>
		<?php

		if($_POST){
			$this->slaRuimteOp();
		}
	}

	public function slaRuimteOp() {
		if ( empty($_POST) || !wp_verify_nonce($_POST['name_of_nonce_field'],'name_of_my_action') )
		{
		print 'Sorry, your nonce did not verify.';
		exit;
		 
		}else{
		 
		// Do some minor form validation to make sure there is content
		if (isset ($_POST['title'])) {
		$title =  $_POST['title'];
		} else {
		echo 'Please enter a title';
		exit;
		}
		 
		// Add the content of the form to $post as an array
		$post = array(
		'post_title' => wp_strip_all_tags( $title ),
		'post_category' => $_POST['cat'],  // Usable for custom taxonomies too
		'post_status' => 'publish',            // Choose: publish, preview, future, etc.
		'post_type' => $_POST['post-type']  // Use a custom post type if you want to
		);
		wp_insert_post($post);  // http://codex.wordpress.org/Function_Reference/wp_insert_post
		 
		$location = home_url(); // redirect location, should be login page
		 
		echo "<meta http-equiv='refresh' content='0;url=$location' />"; exit;
		} // end IF
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