<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.reignitionllc.com/
 * @since      1.0.0
 *
 * @package    Open_Secrets_Data_For_Wp
 * @subpackage Open_Secrets_Data_For_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Open_Secrets_Data_For_Wp
 * @subpackage Open_Secrets_Data_For_Wp/admin
 * @author     Michael McGlynn <mike@reignition.net>
 */
class Open_Secrets_Data_For_Wp_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/open-secrets-data-for-wp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/open-secrets-data-for-wp-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Add custom menu.
     *
     * @since    1.0.1
     */
    public function osd_admin_menu() {
        add_options_page ( 'Open Secrets Data Plugin Settings', 'Open Secrets Data', 'manage_options', 'open-secrets-data-for-wp/mainsettings.php', array($this, 'osd_admin_page'), 'dashicons-money-alt', 80);
    }

    /**
     * Add admin page.
     *
     * @since    1.0.1
     */
    public function osd_admin_page(){
        // Return view
        require_once 'partials/open-secrets-data-for-wp-admin-display.php';
    }

    /**
     * Register plugin fields for plugin settings.
     *
     * @since    1.0.1
     */
    public function osd_register_settings() {
        // Registers all settings for general settings page
        register_setting( 'osd_api_settings', 'osd_api_key' );
        register_setting( 'osd_api_settings', 'osd_base_url' );
        register_setting( 'osd_api_settings', 'osd_cycle' );
        // Not currently handling this format
        //register_setting( 'osd_api_settings', 'output_type' );
    }

}
