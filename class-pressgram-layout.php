<?php
/**
 * Layout Settings for Pressgram.
 *
 * @package   Layout_Settings_for_Pressgram
 * @author    Daniel Milner <daniel@firetreedesign.com>
 * @license   GPL-2.0+
 * @link      http://firetreedesign.com
 * @copyright 2013 FireTree Design
 */

/**
 * Plugin class.
 *
 * @package Layout_Settings_for_Pressgram
 * @author  Daniel Milner <daniel@firetreedesign.com>
 */
class Layout_Settings_for_Pressgram {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.1.3';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'pressgram-layout';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// Check if the Pressgram plugin is active
		add_action( 'admin_init', array( $this, 'check_pressgram' ) );
		
		// Initializes the Pressgram Category setting and field
		add_filter( 'admin_init', array( $this, 'register_pressgram_layout_fields' ) );
		
		// Add post class to the selected Pressgram category
		add_filter( 'post_class', array( $this, 'pressgram_layout_post_class' ) );
		
		// Set the posts_per_page for the Pressgram category
		add_action( 'pre_get_posts', array( $this , 'pressgram_layout_pagesize'), 1 );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide  ) {
				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}
				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}
				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param	int	$blog_id ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) )
			return;

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return	array|false	The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {
		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";
		return $wpdb->get_col( $sql );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		// First, grab the Pressgram category
		$pressgram_category = get_option( 'pressgram_category' );
		if ( is_category($pressgram_category) ) {
			wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), self::VERSION );
		} // end if
		
	} // end enqueue_styles
	
	/**
	 * Registers the Pressgram Layout setting and field with the WordPress Settings API.
	 *
	 * @since    1.0.0
	 */
	public function register_pressgram_layout_fields() {

		if ( is_plugin_active('pressgram/pressgram.php') ) {
			// First, register the setting for the Pressgram field
			register_setting( 'media', 'pressgram_layout', 'esc_attr' );
			register_setting( 'media', 'pressgram_layout_mobile', 'esc_attr' );
			register_setting( 'media', 'pressgram_layout_posts_per_page', 'esc_attr' );

			// Now introduce the settings field
			add_settings_field(
				'pressgram_layout',
				__( 'Layout Settings for Pressgram' , 'pressgram-layout' ),
				array( $this, 'display_pressgram_layout' ) ,
				'media',
				'pressgram'
			);
		} // end if

	} // end register_pressgram_layout_fields
	
	/**
	 * Renders the select option for the layout and allows users to select what layout that want to use.
	 *
	 * @since    1.0.0
	 */
	public function display_pressgram_layout() {

		// Read the currently selected value
        $pressgram_layout_value = get_option( 'pressgram_layout' );
		$pressgram_layout_mobile_value = get_option( 'pressgram_layout_mobile' );
		$pressgram_layout_posts_per_page_value = get_option( 'pressgram_layout_posts_per_page' );

        $html =  '<select id="pressgram_layout" name="pressgram_layout">';
			$html .= '<option value="off"' . selected( 'off', $pressgram_layout_value, FALSE ) . '>' . __( 'Off', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="2wide"' . selected( '2wide', $pressgram_layout_value, FALSE ) . '>' . __( '2 across', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="3wide"' . selected( '3wide', $pressgram_layout_value, FALSE ) . '>' . __( '3 across', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="4wide"' . selected( '4wide', $pressgram_layout_value, FALSE ) . '>' . __( '4 across', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="5wide"' . selected( '5wide', $pressgram_layout_value, FALSE ) . '>' . __( '5 across', 'pressgram-layout' ) . '</option>';
        $html .= '</select>';
		$html .= '&nbsp;on large screen devices';
		
		$html .= '<br><br>';
		$html .=  '<select id="pressgram_layout_mobile" name="pressgram_layout_mobile">';
			$html .= '<option value="off"' . selected( 'off', $pressgram_layout_mobile_value, FALSE ) . '>' . __( 'Off', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="2wide"' . selected( '2wide', $pressgram_layout_mobile_value, FALSE ) . '>' . __( '2 across', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="3wide"' . selected( '3wide', $pressgram_layout_mobile_value, FALSE ) . '>' . __( '3 across', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="4wide"' . selected( '4wide', $pressgram_layout_mobile_value, FALSE ) . '>' . __( '4 across', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="5wide"' . selected( '5wide', $pressgram_layout_mobile_value, FALSE ) . '>' . __( '5 across', 'pressgram-layout' ) . '</option>';
        $html .= '</select>';
		$html .= '&nbsp;on small screen devices';
		
		$html .= '<br><br>';
		$html .=  '<select id="pressgram_layout_posts_per_page" name="pressgram_layout_posts_per_page">';
			$html .= '<option value="default"' . selected( 'default', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( 'WordPress Default', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="10"' . selected( '10', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '10', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="20"' . selected( '20', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '20', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="30"' . selected( '30', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '30', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="40"' . selected( '40', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '40', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="50"' . selected( '50', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '50', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="60"' . selected( '60', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '60', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="70"' . selected( '70', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '70', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="80"' . selected( '80', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '80', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="90"' . selected( '90', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '90', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="100"' . selected( '100', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( '100', 'pressgram-layout' ) . '</option>';
			$html .= '<option value="-1"' . selected( '-1', $pressgram_layout_posts_per_page_value, FALSE ) . '>' . __( 'All', 'pressgram-layout' ) . '</option>';
        $html .= '</select>';
		$html .= '&nbsp;images on a page';

        echo $html;

    } // end display_pressgram_layout
	
	/**
	 * Check if the official Pressgram plugin is active.
	 *
	 * @since    1.0.0
	 */
	public function check_pressgram() {
		
		if ( !is_plugin_active('pressgram/pressgram.php') ) {
			add_action( 'admin_notices', array( $this, 'show_pressgram_required' ) ); 
		} // end if
		
	} // check_pressgram
	
	/**
	 * Display the message that Pressgram is required
	 *
	 * @since    1.0.0
	 */
	public function show_pressgram_required() {
		
		// Only show to admins
		if (current_user_can('manage_options')) {
		   // Shows as an error message. You could add a link to the right page if you wanted.
		   echo '<div id="message" class="error">';
		   echo '<p><strong>' . __('Layout Settings for Pressgram', 'pressgram-layout') . '</strong> ' . __('requires the official', 'pressgram-layout') . ' <a href="http://wordpress.org/plugins/pressgram/" target="_blank">' . __('Pressgram', 'presgram-layout') . '</a> plugin to be installed and activated.</p>';
		   echo '</div>';
		} // end if
		
	} // end show_pressgram_required
	
	/**
	 * Add post class to the selected Pressgram category
	 *
	 * @since    1.0.0
	 */
	public function pressgram_layout_post_class( $classes ) {

		// First, grab the Pressgram category
		$pressgram_category = get_option( 'pressgram_category' );
		if ( is_category($pressgram_category) ) {
			
			$pressgram_layout = get_option( 'pressgram_layout' );
			if ( $pressgram_layout != "off" ) {
				$classes[] = 'pressgram-layout-' . $pressgram_layout;
			} // end if
			
			$pressgram_layout_mobile = get_option( 'pressgram_layout_mobile' );
			if ( $pressgram_layout_mobile != "off" ) {
				$classes[] = 'pressgram-layout-mobile-' . $pressgram_layout_mobile;
			} // end if
			
		} // end if
		return $classes;

	} // end pressgram_layout_post_class
	
	/**
	 * Adjust the posts_per_page for the Pressgram category
	 *
	 * @since    1.1.0
	 */
	public function pressgram_layout_pagesize( $query ) {
		// First, grab the Pressgram category
		$pressgram_category = get_option( 'pressgram_category' );
		if ( is_category($pressgram_category) && $query->is_main_query() ) { // Check that it's the Pressgram category and the main loop
			// Get the posts_per_page value from the settings
			$pressgram_layout_posts_per_page_value = get_option( 'pressgram_layout_posts_per_page' );
			// Check if it's set to WordPress Default and grab the default setting
			if ( $pressgram_layout_posts_per_page_value == "default" ) {
				$pressgram_layout_posts_per_page_value = get_option('posts_per_page');
			} // end if
			// Set the posts_per_page for the main loop
			$query->set( 'posts_per_page', $pressgram_layout_posts_per_page_value );
		} // end if
		return;
	} // end pressgram_layout_pagesize
	
}
