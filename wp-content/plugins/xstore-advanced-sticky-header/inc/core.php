<?php
namespace XStore_Advanced_Sticky_Header\Inc;

use XStore_Advanced_Sticky_Header\Inc\i18n;

defined( 'ABSPATH' ) || exit;

/**
 * The main plugin class
 *
 * @since      1.0.0
 * @version    1.0.0
 * @package    XStore_Advanced_Sticky_Header
 * @subpackage XStore_Advanced_Sticky_Header/includes
 */
class Core {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 */
	const PLUGIN_ID         =	'xstore-advanced-sticky-header';

	/**
	 * The name identifier of this plugin.
	 *
	 * @since    1.0.0
	 */
	const PLUGIN_NAME       =	'XStore Advanced Sticky header';

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 */

	const PLUGIN_VERSION    =	XStore_Advanced_Sticky_Header_Version;

	/**
	 * Holds instance of this class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      XStore_Advanced_Sticky_Header    $instance    Instance of this class.
	 */
	private static $instance;

	/**
	 * Main plugin path.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_path    Main path.
	 */
	private static $plugin_path;

	/**
	 * Absolute plugin url.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_url    Main path.
	 */
	private static $plugin_url;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		// Plugin Id and Path
		self::$plugin_path = plugin_dir_path( dirname( __FILE__ ) );
		self::$plugin_url  = plugin_dir_url( dirname( __FILE__ ) );
		// Load depency
		$this->autoload_dependencies();
		$this->set_locale();

        add_action( 'admin_notices', array( $this, 'required_theme_notice' ), 50 );
        add_action( 'admin_notices', array($this, 'required_et_core_notice'), 50 );
        add_action( 'admin_bar_menu', array( $this, 'top_bar_menu' ), 120 );
        add_filter( 'plugin_action_links_'.XStore_Advanced_Sticky_Header_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ), 10, 5 );
		add_action( 'wp_body_open', array( $this, 'required_theme_notice_frontend' ), 50 );
		add_action( 'wp_body_open', array( $this, 'required_et_core_notice_frontend' ), 50 );
	}

	/**
	 * Get plugin's absolute path.
	 *
	 * @since    1.0.0
	 */
	public static function get_plugin_path() {
		return isset( self::$plugin_path ) ? self::$plugin_path : plugin_dir_path( dirname( __FILE__ ) );
	}

	/**
	 * Method responsible to call all the dependencies
	 *
	 * @since 1.0.0
	 */
	protected function autoload_dependencies() {
		spl_autoload_register( array( $this, 'load' ) );
	}

	/**
	 * Loads all Plugin dependencies.
	 *
	 * @param string $class Class need to be loaded.
	 * @since    1.0.0
	 */
	public function load( $class ) {
		$parts = explode( '\\', $class );

		// Run this autoloader for classes related to this plugin only.
		if ( 'XStore_Advanced_Sticky_Header' !== $parts[0] ) {
			return;
		}

		// Remove 'XStore_Advanced_Sticky_Header' from parts.
		array_shift( $parts );

		$parts = array_map(
			function ( $part ) {
				return str_replace( '_', '-', strtolower( $part ) );
			}, $parts
		);

		$class_file_name = '/' . array_pop( $parts ) . '.php';
		$file_path = self::get_plugin_path() . implode( '/', $parts ) . $class_file_name;

		if ( \file_exists( $file_path ) ) {
			require_once( $file_path );
		}

		$trait_file_name = '/' . array_pop( $parts ) . '.php';

		$file_path = self::get_plugin_path() . implode( '/', $parts ) . $trait_file_name;

		if ( \file_exists( $file_path ) ) {
			require_once( $file_path );
		}

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {
		$xstore_advanced_sticky_header_i18n = new i18n;
		$xstore_advanced_sticky_header_i18n->set_domain( self::PLUGIN_ID );

		add_action( 'plugins_loaded', array( $xstore_advanced_sticky_header_i18n, 'load_plugin_textdomain' ) );
	}

    public function top_bar_menu( $wp_admin_bar ) {
        if (!defined('ETHEME_CODE_IMAGES') || !current_user_can('manage_options')) {
            return;
        }

        $wp_admin_bar->add_node( array(
            'parent' => 'et-top-bar-general-menu',
            'id'     => 'et-advanced-sticky-header',
            'title'  => esc_html__( 'Advanced sticky header', 'xstore-advanced-sticky-header' ) . '<span class="et-tbm-label et-tbm-label-new">'.esc_html__('new', 'xstore-advanced-sticky-header').'</span>',
            'href'   => admin_url( '/customize.php?autofocus[panel]=advanced-sticky-header' ),
        ) );

    }
    /**
     * Show action links on the plugin screen.
     *
     * @param mixed $links Plugin Action links.
     *
     * @return array
     */
    public static function plugin_action_links( $links ) {
        $action_links = array(
            'settings' => '<a href="' . admin_url( '/customize.php?autofocus[panel]=advanced-sticky-header' ) . '" aria-label="' . esc_attr__( 'View advanced sticky header settings', 'xstore-advanced-sticky-header' ) . '">' . esc_html__( 'Settings', 'xstore-advanced-sticky-header' ) . '</a>',
        );

        return array_merge( $action_links, $links );
    }

	/**
	 * ! Notice "Theme version"
	 * @since 1.0.0
	 */
	function required_theme_notice(){

		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

		if (
			count($xstore_branding_settings)
			&& isset($xstore_branding_settings['control_panel'])
			&& isset($xstore_branding_settings['control_panel']['hide_updates'])
			&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
		){
			return;
		}

		if ( get_template_directory() !== get_stylesheet_directory() ) {
	    	$theme = wp_get_theme( 'xstore' );
	    } else {
	    	$theme = wp_get_theme();
	    }

	    if (  $theme->name == ('XStore') &&  version_compare( $theme->version, XStore_Advanced_Sticky_Header_THEME_MIN_VERSION, '<' ) ) {
	    	$video = '<a class="et-button" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank" style="color: white!important; text-decoration: none"><span class="dashicons dashicons-video-alt3" style="color: var(--et_admin_main-color, #A4004F);"></span> Video tutorial</a>';

	      	echo '
				<div class="et-message et-warning">
					XStore Advanced Sticky header plugin requires the following theme: <strong>XStore</strong> to be updated up to <strong>' . XStore_Advanced_Sticky_Header_THEME_MIN_VERSION . ' version.</strong> You can install the updated version of XStore theme: <ul>
						<li>1) via <a href="'.admin_url('update-core.php').'">Dashboard</a> > Updates > click Check again button > update theme</li>
						<li>2) via FTP using archive from <a href="https://www.8theme.com/downloads" target="_blank">Downloads</a></li>
						<li>3) via FTP using archive from the full theme package downloaded from <a href="https://themeforest.net/" target="_blank">ThemeForest</a></li>
						<li>4) via <a href="https://envato.com/market-plugin/" target="_blank">Envato Market</a> WordPress Plugin</li>
						<li>5) via <a href="https://wordpress.org/plugins/easy-theme-and-plugin-upgrades/" target="_blank">Easy Theme and Plugin Upgrades</a> WordPress Plugin</li>
						<li>6) Don\'t Forget To Clear <strong style="color:#c62828;"> Cache! </strong></li>
		                </ul>
		                <br>
						' . $video . '
						<br><br>
				</div>
			';
	    }
	}

	/**
	 * Load theme compatibility.
	 * 
	 * @since 1.0.0
	 */
	function required_theme_notice_frontend(){
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
		if (
			count($xstore_branding_settings)
			&& isset($xstore_branding_settings['control_panel'])
			&& isset($xstore_branding_settings['control_panel']['hide_updates'])
			&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
		){
			return;
		}

		if ( is_user_logged_in() && current_user_can('administrator') ) {
			if ( get_template_directory() !== get_stylesheet_directory() ) {
		    	$theme = wp_get_theme( 'xstore' );
		    } else {
		    	$theme = wp_get_theme();
		    }

		    if (  $theme->name == ('XStore') &&  version_compare( $theme->version, XStore_Advanced_Sticky_Header_THEME_MIN_VERSION, '<' ) ) {
		    	$video = '<a class="et-button et-button-active" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank"> Video tutorial</a>';
				echo '
					</br>
					<div class="woocommerce-massege woocommerce-info error">
						XStore Advanced Sticky header plugin requires the following theme: <strong>XStore v.' . XStore_Advanced_Sticky_Header_THEME_MIN_VERSION . '.</strong>
						'.$video.'. This warning is visible for <strong>administrator only</strong>.
					</div>
					</br>
				';
			}
		}
	}

    /**
     * ! Notice "XStore Core version"
     * @since 1.0.0
     */
    function required_et_core_notice(){

        $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

        if (
            count($xstore_branding_settings)
            && isset($xstore_branding_settings['control_panel'])
            && isset($xstore_branding_settings['control_panel']['hide_updates'])
            && $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
        ){
            return;
        }


        $file = ABSPATH . 'wp-content/plugins/et-core-plugin/et-core-plugin.php';

        if ( ! file_exists($file) ) return;

        $plugin = get_plugin_data( $file, false, false );

        if ( version_compare( XStore_Advanced_Sticky_Header_ET_CORE_MIN_VERSION, $plugin['Version'], '>' ) ) {
            $video = '<a class="et-button" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank" style="color: white!important; text-decoration: none"><span class="dashicons dashicons-video-alt3" style="color: var(--et_admin_red-color, #c62828);"></span> Video tutorial</a>';

            echo '
        <div class="et-message et-warning">
            XStore Advanced Sticky header plugin requires the following plugin: <strong>XStore Core</strong> to be updated up to <strong>' . XStore_Advanced_Sticky_Header_ET_CORE_MIN_VERSION . ' version. </strong>You can install the updated version of XStore core plugin: <ul>
                <li>1) via <a href="'.admin_url('update-core.php').'">Dashboard</a> > Updates > click Check again button > update plugin</li>
                <li>2) via FTP using archive from <a href="https://www.8theme.com/downloads" target="_blank">Downloads</a></li>
                <li>3) via FTP using archive from the full theme package downloaded from <a href="https://themeforest.net/" target="_blank">ThemeForest</a></li>
                <li>4) via <a href="https://wordpress.org/plugins/easy-theme-and-plugin-upgrades/" target="_blank">Easy Theme and Plugin Upgrades</a> WordPress Plugin</li>
                <li>5) Don\'t Forget To Clear <strong style="color:#c62828;"> Cache! </strong></li>
                </ul>
                <br>
                ' . $video . '
                <br><br>
        </div>
    ';
        }
    }

    /**
     * Load et-core-plugin compatibility.
     *
     * @since 1.0.0
     */
    function required_et_core_notice_frontend(){
        if ( get_query_var( 'et_is-loggedin', false) && current_user_can('administrator') ) {

            $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

            if (
                count($xstore_branding_settings)
                && isset($xstore_branding_settings['control_panel'])
                && isset($xstore_branding_settings['control_panel']['hide_updates'])
                && $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
            ){
                return;
            }

            if( !function_exists('get_plugin_data') ){
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }

            $file = ABSPATH . 'wp-content/plugins/et-core-plugin/et-core-plugin.php';

            if ( ! file_exists($file) ) return;

            $plugin = get_plugin_data( $file, false, false );

            if ( version_compare( XStore_Advanced_Sticky_Header_ET_CORE_MIN_VERSION, $plugin['Version'], '>' ) ) {
                $video = '<a class="et-button et-button-active" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank"> Video tutorial</a>';
                echo '
				</br>
				<div class="woocommerce-massege woocommerce-info error">
					XStore Advanced Sticky header plugin requires the following plugin: <strong>XStore Core plugin v.' . XStore_Advanced_Sticky_Header_ET_CORE_MIN_VERSION . '.</strong>
					'.$video.'. This warning is visible for <strong>administrator only</strong>.
				</div>
			';
            }
        }
    }

}
