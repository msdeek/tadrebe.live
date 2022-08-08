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

 class CPanel_Services{
 /**    
    *public function __construct( $tadreblive, $version ) {

	*	$this->tadreblive = $tadreblive;
	*	$this->version = $version;

	*}*/
    
    /**
	 * Get Moodle Access Token
	 * Done
	 * @since    1.0.0
	 */

    public static function get_moodle_token($username, $password, $baseurl, $service){
        
        $token_url = $baseurl .'/login/token.php';// Moodle Token URL.
        
        // arguments to get token from moodle website.
        $arguments = array(
            'method' => 'POST',
            'body' => array( 
            'username' => $username,
            'password' => $password,
            'service' => $service)          // Moodle Service that installed by codefish plugin in moodle website.
        );

        $request= wp_remote_post($token_url, $arguments); //get token from moodle API, Method: POST.
        
        if ( is_wp_error( $request ) ) {
            $token = $request->get_error_message();
        }else{
             // retrieve json body content.
            $token_response = json_decode( wp_remote_retrieve_body($request));
            #var_dump($request);         
            if ( property_exists( $token_response,"token")){
                $token = $token_response->token; // get token code from API content.
                return $token;
            }
        }
    }

    /**
    * Get Moodle Services 
    * Done
    * @since 1.0.0
    */
    
    public static function register_moodle_services($baseurl,$method, $body){

        $service_url = $baseurl .'/webservice/rest/server.php';
        $arguments = array(
            'method' => $method,
            'body' => $body
        );
        $request= wp_remote_post($service_url, $arguments);
        #var_dump($request);
        if ( is_wp_error( $request ) ) {
            echo $request->get_error_message();
        }else{
             // retrieve json body content.
            $content = json_decode( wp_remote_retrieve_body($request));         
            return $content;
        }

    }
    
    

 }