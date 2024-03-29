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
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'dist/open-secrets-data-for-wp-public.css', array(), $this->version, 'all' );
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

	/**
	 * Comment goes here
	 */
	public function open_secrets_data() {

		$debug  = false;
		$markup = '';
		$meta   = '';

		if ( ! post_custom( 'cid' ) ) {
			$meta .= "No Open Secrets data exists for this post.\n";
		} else {
			$cid = post_custom( 'cid' );
			/* https://github.com/bpilkerton/php-crpapi */
			require_once 'lib/crpapi.php';

			/* For when we are ready to get it from the post - get_the_ID() */
			$cid_ts  =  $cid . '_ts';

			/*
			    If the TIMESTAMP and DATA transients exist, pull the data, otherwise set a new transient.
			*/
			if ( get_transient( $cid_ts ) && get_transient( $cid ) ) {

				/* Track the datestamp from the transient. */
                $meta .= "Pulled from transient on \n";
				$meta .= get_transient($cid_ts) . ".\n";

				/* Get the serialized data from a transient. */
				$data = get_transient( $cid );

				/* Track the status of the transient data. */
                if (empty($data)) {
                    $meta .= "There is NO data in the transient\n";
                } else {
                    $meta .= "There IS data in the transient.\n";
                }

				/* Call the function that returns the contribution data in an HTML table. */
				$markup = display_cand_contrib( $data );

			} else {

				/* Set and report the TIMESTAMP transient. */

				if ( set_transient( 'osd_' . $cid_ts, gmdate( ' m/d/Y h:i:s A' ), 14400 ) ) {
                    $meta .= "Create a new transient.\n";
                    $meta .= gmdate( ' m/d/Y h:i:s A' ) . "\n";
				}

                $cycle = '';

                if ( get_option( 'osd_cycle' ) ) {
                    $cycle = get_option( 'osd_cycle' );
                }

                $api_key = '';

                if ( get_option( 'osd_api_key' ) ) {
                    $api_key = get_option( 'osd_api_key' );
                }

                $base_url = '';

                if ( get_option( 'osd_base_url' ) ) {
                    $base_url = get_option( 'osd_base_url' );
                }

                // error_log( 'cycle: ' . get_option( 'osd_cycle' ));
				// error_log( 'api key: ' . get_option( 'osd_api_key' ));
				// error_log( 'base url: ' . get_option( 'osd_base_url' ));
                // Not yet
				//error_log( 'output_type: ' . get_option( 'output_type' ));


				/* https://www.opensecrets.org/api/?method=candContrib&output=doc */
				$crp = new crp_api(					
					array(
						'method'	 => 'candContrib',
						'cid'        => $cid,
						'cycle'      => $cycle,
						'api_key'    => $api_key,
						'base_url'   => $base_url,
                        'output'     => 'json',
					)
				);

				$cand_contrib = $crp->get_data();

				//var_dump($cand_contrib);

				set_transient( ( 'osd_' . $cid ), $cand_contrib, 14400 );

				// Debug
                if (set_transient( ( 'osd_' . $cid ) , $cand_contrib, 14400 )) {
                    $meta .= "Open Secrets data object saved as a transient.\n";
                }

				$markup .= display_cand_contrib( $cand_contrib );
			}
		}

        if(!$debug) {
            $meta = '<!--' . $meta . '-->';
        }

		return $meta . $markup;
	}

	/**
	 * @param $response
	 * @return mixed|string
	 */
	private function wp_remote_retrieve_response_code( $response ) {
		if ( is_wp_error( $response ) || ! isset( $response['response'] ) || ! is_array( $response['response'] ) ) {
			return '';
		}
		return $response['response']['code'];
	}

	/**
	 * @param $response
	 * @return mixed|string
	 */
	private function wp_remote_retrieve_body( $response ) {
		if ( is_wp_error( $response ) || ! isset( $response['body'] ) ) {
			return '';
		}
		return $response['body'];
	}

}

/**
 * @param $obj
 * @return string
 */
function display_cand_contrib( $obj ): string {

	$str = '';

	if ( empty( $obj ) ) {
		$str .= '<i>OpenSecrets data not currently available.</i>';
	} else {
        $contributors = $obj['response']['contributors']['@attributes'];

        $contributor = $obj['response']['contributors']['contributor'];

        if ( $contributors['cand_name'] ) {
            $str .= "<h2>$contributors[cand_name]</h2>";
        }

        if ( $contributors['cycle'] ) {
            $str = "<h3>Top contributors for the $contributors[cycle] election cycle.</h3>";
        }

        if ( $contributors['notice'] ) {
            $str .= "<p>$contributors[notice]</p>";
        }

        $str .= '<table class="op-data-table">';
        $str .= '<thead>';
        $str .= '<tr>';
        $str .= '<th>Organization Name</th>';
        $str .= '<th>Total</th>';
        $str .= '<th>PACs</th>';
        $str .= '<th>Individuals</th>';
        $str .= '</thead>';
        $str .= '<tbody>';

        foreach ( $contributor as $attributes ) {
            foreach ( $attributes as $attribute ) {
                $str .= '<tr>';
                foreach ( $attribute as $val ) {
                    if ( ctype_digit( $val ) ) {
                        $str .= '<td>$' . number_format( $val, 2, '.', ',' ) . '</td>';
                    } else {
                        $str .= "<td>$val</td>";
                    }
                }
                $str .= '</tr>';
            }
        }

        $str .= '</tbody>';
        $str .= '</table>';

        if ( $contributors['source'] ) {
            $str .= "<small>Data provided by <a href=\"$contributors[source]\" target='_blank'>Open Secrets</a>.</small>";
        }
	}

	return $str;
}
