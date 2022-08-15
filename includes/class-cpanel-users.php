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

 class CPanelUsers{


    
    /**
	 * Get Basic Mooolde Setiings
	 *
	 * @since    1.0.0
	*/
	public function get_cp_settings(){
		$username = get_option('cpusername');
		$password = get_option( 'cppassword');
		$url = get_option( 'cpurl');
		$services = get_option( 'cpservice');
		$token = get_option('cptoken');

		return array(
			'cpusername' => $username,
			'cppassword' => $password,
			'cpurl' => $url,
			'cpservices' => $services,
			'cptoken' => $token
		);
	}
    
	  /**
	 * Get Basic Current User Data
	 *
	 * @since    1.0.0
	*/
	public function get_user_cred(){
		
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		if ( 0 != $user_id ) {
		$username = $current_user->user_login;
		$user_pass = get_user_meta($user_id, 'tmp_pw_saved' );
		$user_firstname= get_user_meta($user_id, 'first_name' );
		$user_lastname= get_user_meta($user_id, 'last_name' );
		$user_email = $current_user->user_email;


			return array(
				'user_id' => $user_id,
				'username' => $username,
				'user_pass' => $user_pass[0],
				'user_firstname' => $user_firstname[0],
				'user_lastname' => $user_lastname[0],
				'user_email' => $user_email
			);
		}else{
			return array(
				'user_id' => 0 ,
			);
		}
		}



	public function add_user_to_cpnel(){
		global $wpdb;

		$user_data = $this->get_user_cred();
		$cp_cr = $this->get_cp_settings();
		$user_id = $user_data['user_id'];
		if ( 0 != $user_id ) {
			$username = $user_data['username'];
			$user_pass = $user_data['user_pass'];
			$user_firstname = $user_data['user_firstname'];
			$user_lastname = $user_data['user_lastname'];
			$user_email = $username . '@tadreb.live';
			$baseurl = $cp_cr['cpurl'];
			$cptoken = $cp_cr['cptoken'];
			$method = 'POST';
			
			$usernamemoodle = $wpdb->get_var( // @codingStandardsIgnoreLine
				$wpdb->prepare(
					"SELECT meta_value
					FROM {$wpdb->prefix}usermeta
					WHERE meta_key = 'cp_user_id'
					AND user_id = %s",
					$user_id
				)
			);
			#echo ($usernamemoodle);

			if ( false == $usernamemoodle ){
				$body = array(
					'wstoken' => $cptoken,
					'wsfunction' => 'core_user_get_users_by_field',
					'moodlewsrestformat' => 'json',
					'field' => 'username',
					'values[0]' => $username
				);
				$add_user = new CPanel_Services;

				$add_user = $add_user->register_moodle_services($baseurl, $method, $body);
				if ( false != $add_user){
					foreach ((array) $add_user as $cp_username){
						$cp_id = $cp_username->id;
						add_user_meta( $user_id, 'cp_user_id', $cp_id, true );
					}
				}else{
					$body = array(
						'wstoken' => $cptoken,
						'wsfunction' => 'core_user_create_users',
						'moodlewsrestformat' => 'json',
						'users[0][username]' => $username,
						'users[0][auth]'     => 'manual',
						'users[0][password]' => $user_pass,
						'users[0][firstname]' => $user_firstname,
						'users[0][lastname]' => $user_lastname,
						'users[0][email]' => $user_email ,
					);
					$add_user = new CPanel_Services;

				 	$add_user = $add_user->register_moodle_services($baseurl, $method, $body);
				}
			}
		}
		$this->get_current_user_cp_token();
	}
					
			



	public function get_current_user_cp_token(){
		global $wpdb;
		$user_data = $this->get_user_cred();
		
		$current_user = wp_get_current_user();
		$baseurl = get_option( 'cpurl');
		$service = get_option( 'cpservice');
		$user_id = $current_user->ID;
		
		if ( 0 != $current_user->ID ) {
			$password = $user_data['user_pass'];
			$token = new CPanel_Services;
			$username = $current_user->user_login;
			#echo $cuser_id ;
			#echo $username;
			#echo $password;
			#echo $baseurl;
			#echo $service;
			$token = $token->get_moodle_token($username, $password, $baseurl, $service );
			$token_meta = $wpdb->get_var( // @codingStandardsIgnoreLine
				$wpdb->prepare(
					"SELECT umeta_id
					FROM {$wpdb->prefix}usermeta
					WHERE meta_key = 'cp_token'
					AND meta_value = %s",
					$token
				)
			);
			#var_dump($token_meta);
			if ( false == $token_meta ){
				add_user_meta( $user_id, 'cp_token', $token, true ) ;
				update_user_meta( $user_id, 'cp_token', $token);
			}
		}
		
		
		


		


	}



	public function update_pw_to_meta(){
		global $wpdb;

		$pcurrent_user = wp_get_current_user();
		$cp_cr = $this->get_cp_settings();
		$baseurl = $cp_cr['cpurl'];
		$cptoken = $cp_cr['cptoken'];
		$method = 'POST';
		$puser_id = $pcurrent_user->ID;
		
		// Update Pasword To Meta Data in Wordpress 
		$new_pass = $_POST['pass1'];
		#echo $new_pass;
		update_user_meta( $puser_id, 'tmp_pw_saved', $new_pass ) ;
		
		// Update Password in Moodle
		$usernamemoodle = $wpdb->get_var( // @codingStandardsIgnoreLine
			$wpdb->prepare(
				"SELECT meta_value
				FROM {$wpdb->prefix}usermeta
				WHERE meta_key = 'cp_user_id'
				AND user_id = %s",
				$puser_id
			)
		);
		if ( false != $usernamemoodle ){
			$add_user = new CPanel_Services;
			$body = array(
				'wstoken' => $cptoken,
				'wsfunction' => 'core_user_update_users',
				'moodlewsrestformat' => 'json',
				'users[0][id]' => $usernamemoodle,
				'users[0][password]' => $new_pass
			);
			$add_user = $add_user->register_moodle_services($baseurl, $method, $body);
		}
		
		
	}
	public function create_new_cpanel_user (){
        $current_user = wp_get_current_user();
		$current_user_id = $current_user->ID;
		$key = 'digits_phone_no';
		$current_user_phone = get_user_meta($current_user_id, $key );

        
    }

	public function core_user_update_users(){
		

		

	}

	public function core_user_create_users(){
		global $wpdb;
		$users_data = 
			$wpdb->get_results(
				"SELECT ID, user_login, user_pass, user_email
				FROM {$wpdb->prefix}users"
			);
		    $current_user = wp_get_current_user();
			$current_user = wp_get_current_user();
			$current_user_id = $current_user->ID;
			$key = 'digits_phone_no';
			$current_user_phone = get_user_meta($current_user_id, $key, true );
		#echo $current_user_phone;
		#var_dump($users_data);
		
	}

	public function cpanel_update_course_access($user_id, $course_id, $course_access_list, $remove){
		
	}
 }