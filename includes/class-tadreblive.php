<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.coderfish.com.eg
 * @since      1.0.0
 *
 * @package    tadreblive
 * @subpackage tadreblive/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    tadreblive
 * @subpackage tadreblive/includes
 * @author     codefish <info@codefish.com.eg>
 */
class tadreblive {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      tadreblive_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $tadreblive    The string used to uniquely identify this plugin.
	 */
	protected $tadreblive;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'tadreblive_VERSION' ) ) {
			$this->version = tadreblive_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->tadreblive = 'tadreblive';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - tadreblive_Loader. Orchestrates the hooks of the plugin.
	 * - tadreblive_i18n. Defines internationalization functionality.
	 * - tadreblive_Admin. Defines all hooks for the admin area.
	 * - tadreblive_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tadreblive-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tadreblive-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tadreblive-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the cpanel admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cpanel-admin.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tadreblive-public.php';

		/**
		 * The class responsible for defining all Moodle Services
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cpanel-services.php';

		/**
		 * The class responsible for defining all Moodle Users
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cpanel-users.php';

				/**
		 * The class responsible for defining all Moodle Content
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cpanel-content.php';
		
		/**
		 * The class responsible for manage wordpress users
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cpanel/tadreb_action_functions.php';
		
		
		$this->loader = new tadreblive_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the tadreblive_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new tadreblive_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new tadreblive_Admin( $this->get_tadreblive(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'tadreb_live_manue');
		
		$plugin_cpanel_admin = new cpanel_Admin( $this->get_tadreblive(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $plugin_cpanel_admin, 'tadreb_live_cpanel_manue');
		$this->loader->add_action('init', $plugin_cpanel_admin, 'register_cpanel_settings');
		#$this->loader->add_action('init', $plugin_cpanel_admin, 'register_token');
		$this->loader->add_filter('manage_edit-sfwd-courses_columns', $plugin_cpanel_admin,'cpanel_course_id_columns');
		$this->loader->add_action('manage_sfwd-courses_posts_custom_column', $plugin_cpanel_admin,'cpanel_course_id_column', 10, 2);

		#$this->loader->add_action('init', $plugin_cpanel_admin, 'get_moodle_courses');
		$this->loader->add_action( 'wp_ajax_init_cpanel_connecions',$plugin_cpanel_admin,   'init_cpanel_connecions' ) ;
		$this->loader->add_action( 'wp_ajax_nopriv_init_cpanel_connecions',$plugin_cpanel_admin,   'init_cpanel_connecions' ) ;
		$this->loader->add_action( 'wp_ajax_update_cptoken',$plugin_cpanel_admin,   'update_cptoken' ) ;
		$this->loader->add_action( 'wp_ajax_nopriv_update_cptoken',$plugin_cpanel_admin,   'update_cptoken' ) ;

		$this->loader->add_action( 'wp_ajax_get_moodle_courses',$plugin_cpanel_admin,   'get_moodle_courses' ) ;
		$this->loader->add_action( 'wp_ajax_nopriv_get_moodle_courses',$plugin_cpanel_admin,   'get_moodle_courses' ) ;

		

		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new tadreblive_Public( $this->get_tadreblive(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'enqueue_block_editor_assets',$plugin_public,'tadreb_custom_block' );
		#$this->loader->add_action( 'init', $plugin_public,   'get_bigbluebuttonbn' ) ;
		#$this->loader->add_filter( 'locale', $plugin_public, 'chnag_loacal' );
		$this->loader->add_action( 'wp_ajax_get_bigbluebuttonbn',$plugin_public,   'get_bigbluebuttonbn' ) ;
		$this->loader->add_action( 'wp_ajax_nopriv_get_bigbluebuttonbn',$plugin_public,   'get_bigbluebuttonbn' ) ;
		$this->loader->add_shortcode('bbb_meeting', $plugin_public, 'display_bigbluebuttonbn_meeting');
		

		$plugin_cpanel_user = new CPanelUsers( $this->get_tadreblive(), $this->get_version() );
		
		#$this->loader->add_action('init', $plugin_cpanel_user, 'add_user_to_cpnel');
		$this->loader->add_action( 'wp_ajax_add_user_to_cpnel',$plugin_cpanel_user,   'add_user_to_cpnel' ) ;
		$this->loader->add_action( 'wp_ajax_nopriv_add_user_to_cpnel',$plugin_cpanel_user,   'add_user_to_cpnel' ) ;
		#$this->loader->add_action( 'init',$plugin_cpanel_user,   'enroll_user_to_cpanel' ) ;

		$this->loader->add_action( 'wp_ajax_enroll_user_to_cpanel',$plugin_cpanel_user,   'enroll_user_to_cpanel' ) ;
		$this->loader->add_action( 'wp_ajax_nopriv_enroll_user_to_cpanel',$plugin_cpanel_user,   'enroll_user_to_cpanel' ) ;

		$plugin_manage_users = new tadreb_action_functions($this->get_tadreblive(), $this->get_version());
		$this->loader->add_action('user_register', $plugin_manage_users, 'save_pw_to_meta_data');
		$this->loader->add_action('profile_update', $plugin_manage_users, 'update_pw_to_meta_data');


		

	}



	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_tadreblive() {
		return $this->tadreblive;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    tadreblive_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
