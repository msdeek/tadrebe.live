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
class cpanel_Admin
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
	 * @param      string    $tadreblive       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	
	
	/**
	 * Register the tadreb.live menue  for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function tadreb_live_cpanel_manue()
	{
		add_submenu_page('tadreblive/overview.php', 'CPanel Settings', 'CPanel Settings', 'manage_options', 'tadreblive/cpanel.php', array($this, 'tadreb_live_cpanel_admin_page'), 10);
	}

	public function tadreb_live_cpanel_admin_page()
	{
		// Return View 
		require_once 'partials/tadreblive-cpanel-admin-display.php';
	}

	/**
	 * Register the cpanel settings.
	 *
	 * @since    1.0.0
	 */

	public function register_cpanel_settings()
	{
		register_setting('cpcred', 'cpurl');
		register_setting('cpcred', 'cpusername');
		register_setting('cpcred', 'cppassword');
		register_setting('cpcred', 'importcourse');
		register_setting('cpcred', 'cpservice');
		register_setting('cpcred', 'cptoken');
	}

	/**
	 * Register the BigBlueButton settings.
	 *
	 * @since    1.0.0
	 */
	public function register_bbb_settings()
	{
		register_setting('bbbcred', 'bbburl');
		register_setting('bbbcred', 'bbbsharedsecret');
	}

	
	
	public function register_token()
	{

		$username = get_option('cpusername');
		$password = get_option('cppassword');
		$url = get_option('cpurl');
		$services = get_option('cpservice');

		$token = new CPanel_Services;
		$token = $token->get_moodle_token($username, $password, $url, $services);
		update_option('cptoken', $token);
		return $token;
	}

	public function test_connection()
	{
		
		$username = get_option('cpusername');
		$password = get_option('cppassword');
		$url = get_option('cpurl');
		$services = get_option('cpservice');
		$token = new CPanel_Services;
		$response['data'] = $token->get_moodle_token($username, $password, $url, $services);
		if ($response['data'] != null) {
			$response['success'] = "Coneection is Success" . $response['data'];
		} else {
			$response['success'] = "Somseting Wrong";
		};
		$response = json_encode($response);
		echo $response;
		die();
	}


	public function get_moodle_courses()
	{

		$baseurl = get_option('cpurl');
		$token =  get_option('cptoken');
		#var_dump($token);
		$content = new CPanel_Content;
		$response['courses'] = $content->get_cpanel_courses($baseurl, $token);
		$response['lessons'] = $content->get_cpanel_items($baseurl, $token);
		#$users = new CPanelUsers;
		#$users_data = $users->core_user_create_users();
		$response = json_encode($response);
		echo $response;
		die();
	}

	/**public function init_cpanel_connecions(){


		$username = get_option('cpusername');
		$password = get_option( 'cppassword');
		$url = get_option( 'cpurl');
		$services = get_option( 'cpservice');

		$token = new CPanel_Services;
        
		$response[ 'data' ] = $token->get_moodle_token($username, $password, $url, $services);
		update_option('cptoken', $response[ 'data' ]);
		if ( $response['data'] != null){
			$response[ 'success' ] = "Coneection is Success". $response['data'];
		}else{
			$response ['success'] = "Somseting Wrong";
		};
		$response = json_encode( $response);
		echo $response;
		die();
	}*/

	public function init_cpanel_connecions()
	{

		$response['username'] = get_option('cpusername');
		$response['password'] = get_option('cppassword');
		$response['url'] = get_option('cpurl');
		$response['services'] = get_option('cpservice');

		$response = json_encode($response);
		echo $response;
		die();
	}

	public function update_cptoken()
	{
		if (isset($_REQUEST)) {
			$testing = $_REQUEST['ress'];
			update_option('cptoken', $testing['token']);

			die();
		}
	}

	function cpanel_course_id_columns($columns)
	{
		$columns['cpanel_course_id'] = 'CPanel ID';
		$columns['fullname'] = 'CPanel Name';
		$columns['cplang'] = 'Lang';
		return $columns;
	}

	function cpanel_course_id_column($column, $post_id)
	{
		switch ($column) {
			case 'cpanel_course_id':
				$cp_id = get_post_meta($post_id, 'cpanel_course_id', true);
				echo '<a href="https://cp.tadreb.live/course/view.php?id='.$cp_id.'">'.$cp_id.'</a>';
				break;
			case 'fullname':
				echo get_post_meta($post_id, 'fullname', true);
				break;
			case 'cplang':
				echo get_post_meta($post_id, 'cplang', true);
				break;
		}
	}

	function cpanel_module_id_columns($columns)
	{		
		$columns['cpanel_module_url'] = 'CPanel Link';
		return $columns;
	}

	function cpanel_module_id_column($column, $post_id)
	{
		if ($column == 'cpanel_module_url'){
			$cp_url = get_post_meta($post_id, 'module_url', true);
			$cp_id = get_post_meta($post_id, 'cpanel_module_id', true);
			echo '<a href="'.$cp_url.'">'.$cp_id.'</a>';
			
		}
	}
}
