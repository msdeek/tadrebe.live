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
		wp_enqueue_style('video-tadreb', 'https://vjs.zencdn.net/7.20.2/video-js.css', array(), $this->version, 'all');

	
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
		wp_enqueue_script('video-tadreb', 'https://vjs.zencdn.net/7.20.2/video.min.js', array('jquery'), $this->version, false);
		wp_localize_script($this->tadreblive, 'add_user_to_cpnel', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce(action: 'add_user_to_cpnel_nonce'),

		));
		wp_localize_script($this->tadreblive, 'enroll_user_to_cpanel', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce(action: 'enroll_user_to_cpanel_nonce'),

		));
		wp_localize_script($this->tadreblive, 'display_bigbluebuttonbn_meeting', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce(action: 'display_bigbluebuttonbn_meeting_nonce'),

		));
	
	}
	public function tadreb_custom_block(){
		wp_enqueue_script('palyer', plugin_dir_url(__FILE__) . 'js/player.js', array('jquery'), $this->version, false);

	}

	public function chnag_loacal($post_id){
		$lang = get_post_meta($post_id, 'cplang', true);

		switch ($lang){
			case 'ar' :
				return 'ar_EG';
				break;
			case 'en' :
				return 'en_US';
				break;
		}

	}

	/*public function get_bigbluebuttonbn(){
		if ( is_user_logged_in() ) {
			$content = new CPanel_Content;
			$content = $content->bigbluebuttonbn_add();
			$response = json_encode($content);
			echo $response;
			die();
			
		}

	}*/
	public function bigbluebuttonbn_meeting($baseurl, $token, $topic_id){
		$content = new CPanel_Content;
		$content = $content->bigbluebuttonbn($baseurl, $token, $topic_id);
		return $content;

	}
	public function display_bigbluebuttonbn_meeting($attr){
		$baseurl = get_option('cpurl');
		if ( is_user_logged_in() ) {

			$args = shortcode_atts( array(
     
				'topicid' => '#',
		
	 
			), $attr );
			$pcurrent_user = wp_get_current_user();
			$user_id = $pcurrent_user->ID;
			$token =  get_user_meta($user_id,  'cp_token', true);
			#echo $token;
			$usecode = __('Join&nbsp;Session', 'tadreblive');
			$topic_id = $args['topicid'];
			$back= $this->bigbluebuttonbn_meeting($baseurl, $token, $topic_id);
			
			$opentxt = __('This&nbsp;Session&nbsp;Opening Time&nbsp;: ', 'tadreblive');
			$closingtxt = __('This&nbsp;Session&nbsp;Closing Time&nbsp;: ', 'tadreblive');
			$content = '';
			if (isset($back['openingtime'])){
				$openingtime = $back['openingtime'];
				$content .= '<h5>'.$opentxt.' '.$openingtime.'</h5>';
			}

			

			if (isset($back['closingtime'])){
				$closingtime = $back['closingtime'];
				$content .= '<h5>'.$closingtxt.' '.$closingtime.'</h5>';
			}

			if (isset($back['statusmessage'])){
				$statusmessage = $back['statusmessage'];
				$content .= '<h5>'.$statusmessage.'</h5>';
			}
			if (true == $back['canjoin']){
				$meeting_url = $back['url'];
				$meeting_url = "'" . $meeting_url . "'";
				$targert = "'_blank'";
				$content .= '<input type="button" value="'.("$usecode").'" class="homebutton" id="joinsession" onClick="window.open(' . $meeting_url . ',' . $targert . ');" />';
				$content .= '<p>';
				
			}
			$meeting_open = $back['openingtime'];
			if (isset($back['webcam']) && isset($back['deskshare']) ){
				$webcam = $back['webcam'];
				$deskshare = $back['deskshare'];
		          
		
			$content .= '<div class="td-video-wrapper">';
			$content .= '<video autoplay="true" preload="auto" controls="true" class="td-v1" id="va">';
			$content .= '<source src="'.$deskshare.'" type="video/mp4">';
			$content .='</video>';
			$content .='<video autobuffer="true" autoplay="true" preload="auto" class="td-v2" id="vb"> ';
			$content .='<source src="'.$webcam.'" type="video/mp4">';
			$content .='</video>';
			$content .='</div>';
			
			}
			return $content;
		}
	}

}
