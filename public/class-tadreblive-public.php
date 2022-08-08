<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.coderfish.com.eg
 * @since      1.0.0
 *
 * @package    tadreblive
 * @subpackage tadreblive/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    tadreblive
 * @subpackage tadreblive/public
 * @author     codefish <info@codefish.com.eg>
 */
class tadreblive_Public
{

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
	 * @param      string    $tadreblive       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($tadreblive, $version)
	{

		$this->tadreblive = $tadreblive;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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

		wp_enqueue_style($this->tadreblive, plugin_dir_url(__FILE__) . 'css/tadreblive-public.css', array(), $this->version, 'all');
		wp_enqueue_style($this->tadreblive, '//vjs.zencdn.net/7.10.2/video-js.min.css', array(), $this->version, 'all');
	
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

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

		wp_enqueue_script($this->tadreblive, plugin_dir_url(__FILE__) . 'js/tadreblive-public.js', array('jquery'), $this->version, false);
		wp_enqueue_script('video-tadreb', '//vjs.zencdn.net/7.10.2/video.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script('moodle', 'http://localhost:5000/lib/javascript-static.js', array('jquery'), $this->version, false);
		wp_localize_script($this->tadreblive, 'add_user_to_cpnel', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce(action: 'add_user_to_cpnel_nonce'),

		));
	}
}
