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

class tadreb_action_functions{

    public function __construct( $tadreblive, $version ) {

		$this->tadreblive = $tadreblive;
		$this->version = $version;

	}

    /**
	 * Add Password in plain text to user meta data
	 *
	 * @since    1.0.0
	*/

    public function save_pw_to_meta_data( $user_id ) {
        if ( ! empty( $_POST['signup_password'] ) ) {
			update_user_meta( $user_id, 'tmp_pw_saved', $_POST['signup_password']);
        }
        
        if ( ! empty( $_POST['digits_reg_password'] ) ) {
			update_user_meta( $user_id, 'tmp_pw_saved', $_POST['digits_reg_password']);
        }
    }

	public function update_pw_to_meta_data( $user_id ) {
        if ( ! empty( $_POST['pass1'] ) ) {
			update_user_meta( $user_id, 'tmp_pw_saved', $_POST['pass1']);
            $password = $_POST['pass1'];
            $this->update_pw_to_meta($user_id, $password);
        }
        
        if ( ! empty( $_POST['digits_password'] ) ) {
			update_user_meta( $user_id, 'tmp_pw_saved', $_POST['digits_password']);
            $password = $_POST['digits_password'];
            $this->update_pw_to_meta($user_id, $password);
        }

        if ( ! empty( $_POST['digits_cpassword'] ) ) {
			update_user_meta( $user_id, 'tmp_pw_saved', $_POST['digits_cpassword']);
            $password = $_POST['digits_cpassword'];
            $this->update_pw_to_meta($user_id, $password);
        }
        
        if ( ! empty( $_POST['password_1'] ) ) {
			update_user_meta( $user_id, 'tmp_pw_saved', $_POST['password_1']);
            $password = $_POST['password_1'];
            $this->update_pw_to_meta($user_id, $password);
        }
        
    }

    public function update_pw_to_meta($user_id, $password){
		global $wpdb;
        $cre = new CPanelUsers;
		$pcurrent_user = wp_get_current_user();
		$cp_cr = $cre->get_cp_settings();
		$baseurl = $cp_cr['cpurl'];
		$cptoken = $cp_cr['cptoken'];
		$method = 'POST';
	
		
		// Update Password in Moodle
		$usernamemoodle = $wpdb->get_var( // @codingStandardsIgnoreLine
			$wpdb->prepare(
				"SELECT meta_value
				FROM {$wpdb->prefix}usermeta
				WHERE meta_key = 'cp_user_id'
				AND user_id = %s",
				$user_id
			)
		);
		if ( false != $usernamemoodle ){
			$add_user = new CPanel_Services;
			$body = array(
				'wstoken' => $cptoken,
				'wsfunction' => 'core_user_update_users',
				'moodlewsrestformat' => 'json',
				'users[0][id]' => $usernamemoodle,
				'users[0][password]' => $password
			);
			$add_user = $add_user->register_moodle_services($baseurl, $method, $body);
		}
		
		
	}

	public function update_username(){
		global $wpdb;
        $cre = new CPanelUsers;
		$pcurrent_user = wp_get_current_user();
		$user_id = $pcurrent_user->ID;
		$username = $pcurrent_user->user_login;
		$username = '0'.$username;
		$cp_cr = $cre->get_cp_settings();
		$baseurl = $cp_cr['cpurl'];
		$cptoken = $cp_cr['cptoken'];
		$method = 'POST';
		//echo '-------------------------------'.$user_id;
		$usernamemoodle = $wpdb->get_var( // @codingStandardsIgnoreLine
			$wpdb->prepare(
				"SELECT meta_value
				FROM {$wpdb->prefix}usermeta
				WHERE meta_key = 'cp_user_id'
				AND user_id = %s",
				$user_id
			)
		);
		//echo '-------------------------------'.$usernamemoodle;
		if ( false != $usernamemoodle ){
			$add_user = new CPanel_Services;
			$body = array(
				'wstoken' => $cptoken,
				'wsfunction' => 'core_user_update_users',
				'moodlewsrestformat' => 'json',
				'users[0][id]' => $usernamemoodle,
				'users[0][username]' => $username
			);
			$add_user = $add_user->register_moodle_services($baseurl, $method, $body);
		}
	}
}