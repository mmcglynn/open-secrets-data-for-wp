<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.reignitionllc.com/
 * @since      1.0.0
 *
 * @package    Open_Secrets_Data_For_Wp
 * @subpackage Open_Secrets_Data_For_Wp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Open_Secrets_Data_For_Wp
 * @subpackage Open_Secrets_Data_For_Wp/public
 * @author     Michael McGlynn <mike@reignition.net>
 */
class Open_Secrets_Data_For_Wp_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Open_Secrets_Data_For_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Open_Secrets_Data_For_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/open-secrets-data-for-wp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Open_Secrets_Data_For_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Open_Secrets_Data_For_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/open-secrets-data-for-wp-public.js', array( 'jquery' ), $this->version, false );

	}



    // Add a comment here
    public function open_secrets_data() {

        // https://github.com/bpilkerton/php-crpapi
        require_once('lib/crpapi.php');

        // For when we are ready to get it from the post - get_the_ID()
        $cid = 'N00035527';
        $message = '';

        if ( get_transient( $cid ) ) {
            $message .= build_open_secrets_display( get_transient( $cid ) );
        } else {

            // Open Secrets data class request
            $crp = new crp_api("candContrib",
                Array(
                    "cid"=>$cid,
                    "cycle"=>"2022",
                    "output" => "json"
                ));
            $data = $crp->get_data();

            if ( set_transient($cid, $data, 600) ) {
                $message .= build_open_secrets_display ( get_transient( $cid ) );
            }

        }

        return $message;

    }



    function wp_remote_retrieve_response_code( $response ) {
        if ( is_wp_error( $response ) || ! isset( $response['response'] ) || ! is_array( $response['response'] ) ) {
            return '';
        }
        return $response['response']['code'];
    }

    function wp_remote_retrieve_body( $response ) {
        if ( is_wp_error( $response ) || ! isset( $response['body'] ) ) {
            return '';
        }
        return $response['body'];
    }

}

function build_open_secrets_display( $json ) {
    //return '<h4>build_open_secrets_display function</h4>';

    //$json = file_get_contents("json/tweets.json");
    $obj = json_decode($json);

    // Convert JSON string into a PHP object.
    $contributors = $obj->response->contributors->{'@attributes'};

    return var_export($contributors);

}
