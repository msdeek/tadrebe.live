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
		
		//wp_enqueue_script('custom-video', 'https://cdn.tadreb.live/cdn/tadreblive-public.js', array('jquery'), $this->version, false);

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
			$content .= '<h4>تم إضافة علامة مائية على كافة مقاطع الصورة تحتوى على بيانات المستخدم إذا كنت لاترغب فى هذا يمكنك إلغاء حسابك</h4>';
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
				$rurl = $back['rurl'];
		          
			//$content .='<iframe src="'.$rurl.'" width="100%" height="900px" marginheight="0" frameborder="0" border="0" scrolling="auto" onload="autoResize(this);"> </iframe>';
			
			
			
			$content .= '<div class="td-video-wrapper">';
			$content .= '<video autoplay="true" preload="auto"  class="td-v1" id="vabb">';
			$content .= '<source src="'.$deskshare.'" type="video/mp4">';
			$content .='</video>';
			$content .='<video autobuffer="true" autoplay="true" preload="auto" class="td-v2" id="vbbb"> ';
			$content .='<source src="'.$webcam.'" type="video/mp4">';
			$content .='</video>';
			$content .='</div>';
			$content .='
			<div class="bar_player">
				<div class="progress_video">
					<input type="range" min="0" max="100" value="0" class="range_progress">
					<div class="time_hover">
						<span>00:10</span>
					</div>
				</div>
			</div>

			<div class="btn_player">
                <div class="side_right">
                    <div class="play_pause" data-play="false" >
                        <svg class="play active"   xmlns="http://www.w3.org/2000/svg"  height="100%" version="1.1" viewBox="0 0 36 36" width="100%"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="M 12,26 18.5,22 18.5,14 12,10 z M 18.5,22 25,18 25,18 18.5,14 z" id="ytp-id-60"></path></svg>
                        <svg class="pause "  xmlns="http://www.w3.org/2000/svg" height="100%" version="1.1" viewBox="0 0 36 36" width="100%"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="M 12,26 16,26 16,10 12,10 z M 21,26 25,26 25,10 21,10 z" id="ytp-id-48"></path></svg>
                    </div>
                    <div class="next_video">
                        <svg xmlns="http://www.w3.org/2000/svg" height="100%" version="1.1" viewBox="0 0 36 36" width="100%"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="M 12,24 20.5,18 12,12 V 24 z M 22,12 v 12 h 2 V 12 h -2 z" id="ytp-id-12"></path></svg>
                    </div>
                    <div class="volume_voice">
                        <div class="icon_volume" onclick="isMute(this)" data-mute="false">
                            <svg class="volume active" xmlns="http://www.w3.org/2000/svg" height="100%" version="1.1" viewBox="0 0 36 36" width="100%"><use class="ytp-svg-shadow" ></use><use class="ytp-svg-shadow" ></use><defs><clipPath id="ytp-svg-volume-animation-mask"><path d="m 14.35,-0.14 -5.86,5.86 20.73,20.78 5.86,-5.91 z"></path><path d="M 7.07,6.87 -1.11,15.33 19.61,36.11 27.80,27.60 z"></path><path class="ytp-svg-volume-animation-mover" d="M 9.09,5.20 6.47,7.88 26.82,28.77 29.66,25.99 z" transform="translate(0, 0)"></path></clipPath><clipPath id="ytp-svg-volume-animation-slash-mask"><path class="ytp-svg-volume-animation-mover" d="m -11.45,-15.55 -4.44,4.51 20.45,20.94 4.55,-4.66 z" transform="translate(0, 0)"></path></clipPath></defs><path class="ytp-svg-fill ytp-svg-volume-animation-speaker" clip-path="url(#ytp-svg-volume-animation-mask)" d="M8,21 L12,21 L17,26 L17,10 L12,15 L8,15 L8,21 Z M19,14 L19,22 C20.48,21.32 21.5,19.77 21.5,18 C21.5,16.26 20.48,14.74 19,14 ZM19,11.29 C21.89,12.15 24,14.83 24,18 C24,21.17 21.89,23.85 19,24.71 L19,26.77 C23.01,25.86 26,22.28 26,18 C26,13.72 23.01,10.14 19,9.23 L19,11.29 Z" fill="#fff" id="ytp-id-14"></path><path class="ytp-svg-fill ytp-svg-volume-animation-hider" clip-path="url(#ytp-svg-volume-animation-slash-mask)" d="M 9.25,9 7.98,10.27 24.71,27 l 1.27,-1.27 Z" fill="#fff" id="ytp-id-15" style="display: none;"></path></svg>
                            <svg class="half_volume" xmlns="http://www.w3.org/2000/svg" height="100%" version="1.1" viewBox="0 0 36 36" width="100%"><use class="ytp-svg-shadow" ></use><use class="ytp-svg-shadow" ></use><defs><clipPath id="ytp-svg-volume-animation-mask"><path d="m 14.35,-0.14 -5.86,5.86 20.73,20.78 5.86,-5.91 z"></path><path d="M 7.07,6.87 -1.11,15.33 19.61,36.11 27.80,27.60 z"></path><path class="ytp-svg-volume-animation-mover" d="M 9.09,5.20 6.47,7.88 26.82,28.77 29.66,25.99 z" transform="translate(0, 0)"></path></clipPath><clipPath id="ytp-svg-volume-animation-slash-mask"><path class="ytp-svg-volume-animation-mover" d="m -11.45,-15.55 -4.44,4.51 20.45,20.94 4.55,-4.66 z" transform="translate(0, 0)"></path></clipPath></defs><path class="ytp-svg-fill ytp-svg-volume-animation-speaker" clip-path="url(#ytp-svg-volume-animation-mask)" d="M8,21 L12,21 L17,26 L17,10 L12,15 L8,15 L8,21 Z M19,14 L19,22 C20.48,21.32 21.5,19.77 21.5,18 C21.5,16.26 20.48,14.74 19,14 Z" fill="#fff" id="ytp-id-14"></path><path class="ytp-svg-fill ytp-svg-volume-animation-hider" clip-path="url(#ytp-svg-volume-animation-slash-mask)" d="M 9.25,9 7.98,10.27 24.71,27 l 1.27,-1.27 Z" fill="#fff" id="ytp-id-15" style="display: none;"></path></svg>
                            <svg class="mute" xmlns="http://www.w3.org/2000/svg" height="100%" version="1.1" viewBox="0 0 36 36" width="100%"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="m 21.48,17.98 c 0,-1.77 -1.02,-3.29 -2.5,-4.03 v 2.21 l 2.45,2.45 c .03,-0.2 .05,-0.41 .05,-0.63 z m 2.5,0 c 0,.94 -0.2,1.82 -0.54,2.64 l 1.51,1.51 c .66,-1.24 1.03,-2.65 1.03,-4.15 0,-4.28 -2.99,-7.86 -7,-8.76 v 2.05 c 2.89,.86 5,3.54 5,6.71 z M 9.25,8.98 l -1.27,1.26 4.72,4.73 H 7.98 v 6 H 11.98 l 5,5 v -6.73 l 4.25,4.25 c -0.67,.52 -1.42,.93 -2.25,1.18 v 2.06 c 1.38,-0.31 2.63,-0.95 3.69,-1.81 l 2.04,2.05 1.27,-1.27 -9,-9 -7.72,-7.72 z m 7.72,.99 -2.09,2.08 2.09,2.09 V 9.98 z" id="ytp-id-94"></path></svg>
                        </div>
                        <input type="range" class="range_volume" min="0" max="1" step="0.01">
                    </div>
                    <div class="time_count">
                        <span class="current">00:00</span>
                        <span> 	&nbsp;/&nbsp;</span>
                        <span class="duration">00:00</span>
                    </div>
                </div>
                <div class="side_left">
                    <div class="setting">
                        <svg xmlns="http://www.w3.org/2000/svg" height="100%" version="1.1" viewBox="0 0 36 36" width="100%"><use class="ytp-svg-shadow" ></use><path d="m 23.94,18.78 c .03,-0.25 .05,-0.51 .05,-0.78 0,-0.27 -0.02,-0.52 -0.05,-0.78 l 1.68,-1.32 c .15,-0.12 .19,-0.33 .09,-0.51 l -1.6,-2.76 c -0.09,-0.17 -0.31,-0.24 -0.48,-0.17 l -1.99,.8 c -0.41,-0.32 -0.86,-0.58 -1.35,-0.78 l -0.30,-2.12 c -0.02,-0.19 -0.19,-0.33 -0.39,-0.33 l -3.2,0 c -0.2,0 -0.36,.14 -0.39,.33 l -0.30,2.12 c -0.48,.2 -0.93,.47 -1.35,.78 l -1.99,-0.8 c -0.18,-0.07 -0.39,0 -0.48,.17 l -1.6,2.76 c -0.10,.17 -0.05,.39 .09,.51 l 1.68,1.32 c -0.03,.25 -0.05,.52 -0.05,.78 0,.26 .02,.52 .05,.78 l -1.68,1.32 c -0.15,.12 -0.19,.33 -0.09,.51 l 1.6,2.76 c .09,.17 .31,.24 .48,.17 l 1.99,-0.8 c .41,.32 .86,.58 1.35,.78 l .30,2.12 c .02,.19 .19,.33 .39,.33 l 3.2,0 c .2,0 .36,-0.14 .39,-0.33 l .30,-2.12 c .48,-0.2 .93,-0.47 1.35,-0.78 l 1.99,.8 c .18,.07 .39,0 .48,-0.17 l 1.6,-2.76 c .09,-0.17 .05,-0.39 -0.09,-0.51 l -1.68,-1.32 0,0 z m -5.94,2.01 c -1.54,0 -2.8,-1.25 -2.8,-2.8 0,-1.54 1.25,-2.8 2.8,-2.8 1.54,0 2.8,1.25 2.8,2.8 0,1.54 -1.25,2.8 -2.8,2.8 l 0,0 z" fill="#fff" id="ytp-id-18"></path></svg>
                    </div>
                    <div class="fullscreen" data-zoom="false">
                        <svg class="zoom_in active" onclick="isZoom()"  xmlns="http://www.w3.org/2000/svg" height="100%" version="1.1" viewBox="0 0 36 36" width="100%"><g class="ytp-fullscreen-button-corner-0"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="m 10,16 2,0 0,-4 4,0 0,-2 L 10,10 l 0,6 0,0 z" id="ytp-id-6"></path></g><g class="ytp-fullscreen-button-corner-1"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="m 20,10 0,2 4,0 0,4 2,0 L 26,10 l -6,0 0,0 z" id="ytp-id-7"></path></g><g class="ytp-fullscreen-button-corner-2"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="m 24,24 -4,0 0,2 L 26,26 l 0,-6 -2,0 0,4 0,0 z" id="ytp-id-8"></path></g><g class="ytp-fullscreen-button-corner-3"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="M 12,20 10,20 10,26 l 6,0 0,-2 -4,0 0,-4 0,0 z" id="ytp-id-9"></path></g></svg>
                        <svg class="zoom_out"  onclick="isZoom()"  xmlns="http://www.w3.org/2000/svg" height="100%" version="1.1" viewBox="0 0 36 36" width="100%"><g class="ytp-fullscreen-button-corner-2"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="m 14,14 -4,0 0,2 6,0 0,-6 -2,0 0,4 0,0 z" id="ytp-id-66"></path></g><g class="ytp-fullscreen-button-corner-3"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="m 22,14 0,-4 -2,0 0,6 6,0 0,-2 -4,0 0,0 z" id="ytp-id-67"></path></g><g class="ytp-fullscreen-button-corner-0"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="m 20,26 2,0 0,-4 4,0 0,-2 -6,0 0,6 0,0 z" id="ytp-id-68"></path></g><g class="ytp-fullscreen-button-corner-1"><use class="ytp-svg-shadow" ></use><path class="ytp-svg-fill" d="m 10,22 4,0 0,4 2,0 0,-6 -6,0 0,2 0,0 z" id="ytp-id-69"></path></g></svg>
                    </div>
                </div>
            </div>
			';
			
			}
			return $content;
		}
	}

}
