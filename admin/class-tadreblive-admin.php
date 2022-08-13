<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.coderfish.com.eg
 * @since      1.0.0
 *
 * @package    tadreblive
 * @subpackage tadreblive/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    tadreblive
 * @subpackage tadreblive/admin
 * @author     codefish <info@codefish.com.eg>
 */
class tadreblive_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tadreblive    The ID of this plugin.
	 */
	private $tadreblive;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $tadreblive       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $tadreblive, $version ) {

		$this->tadreblive = $tadreblive;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in tadreblive_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The tadreblive_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->tadreblive, plugin_dir_url( __FILE__ ) . 'css/tadreb-live-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in tadreblive_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The tadreblive_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->tadreblive, plugin_dir_url( __FILE__ ) . 'js/tadreb-live-admin.js', array( 'jquery' ), $this->version, false );
		
		wp_localize_script( $this->tadreblive, 'init_cpanel_connecions', array( 
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce( action:'init_cpanel_connecions_nonce' ),

		));
		wp_localize_script( $this->tadreblive, 'update_cptoken', array( 
				'ajaxurl' => admin_url('admin-ajax.php'),
				'ajax_nonce' => wp_create_nonce( action:'update_cptoken_nonce' ),
	
		));
		/**wp_localize_script( $this->tadreblive, 'get_moodle_courses', array( 
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce( action:'get_moodle_courses_nonce' ),

	));*/
		
	}


	/**
	 * Add a Main menu.
	 *
	 * @since    1.0.0
	 */
	public function tadreb_live_manue(){
		add_menu_page( 'tadreb.live', 'tadreb.live', 'manage_options', 'tadreblive/overview.php', array( $this, 'tadreb_live_overview_admin_page'), 'dashicons-arrow-right-alt2', 0 );
	}

	public function tadreb_live_overview_admin_page(){
		// Return View 
		require_once 'partials/tadreblive-admin-display.php';
	}
}
