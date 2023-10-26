<?php
/**
 * @link              https://xstore.8theme.com
 * @since             1.0.0
 * @package           XStore Advanced Sticky Header
 *
 * Plugin Name:       XStore Advanced Sticky Header
 * Plugin URI:        http://8theme.com
 * Description:       Get more control over your website's header with the advanced sticky header plugin for XStore theme.
 * Version:           1.0.1
 * Author:            8theme
 * Author URI:        https://xstore.8theme.com
 * Text Domain:       xstore-advanced-sticky-header
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 *  Initial class
 */
class XStore_Advanced_Sticky_Header_Initial {

    /**
     * Minimum php version for plugin.
     *
     * @var string
     * @since 1.0.0
     */
    private $min_php_version = '5.3';

    /**
     * Minimum wp version.
     *
     * @var string
     * @since 1.0.0
     */
    private $min_wp_version = '4.8';

    /**
     * Compatible with Multisite.
     *
     * @var boolean
     * @since 1.0.0
     */
    private $multisite_compatible = true;


    /**
     * Holds Error messages if dependencies are not met
     *
     * @var array
     * @since 1.0.0
     */
    private $errors = array();

    /**
     * The single instance of the class.
     *
     * @var instance
     * @since 1.0.0
     */
    protected static $instance = null;

    /**
     * Cloning is forbidden.
     * @since 1.0.0
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheating huh?', 'xstore-advanced-sticky-header' ), '1.0.0' );
    }

    /**
     * Unserializing instances of this class is forbidden.
     * @since 1.0.0
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheating huh?', 'xstore-advanced-sticky-header'), '1.0.0' );
    }

    /**
     * Main XStore_Advanced_Sticky_Header Instance.
     *
     * Ensures only one instance of XStore_Advanced_Sticky_Header is loaded or can be loaded.
     * @return XStore_Advanced_Sticky_Header - Main instance.
     * @since 1.0.0
     */
    public static function instance() {
        // Get an instance of Class
        if( is_null( self::$instance ) ) self::$instance = new self();

        // Return the instance
        return self::$instance;
    }

    /**
     * Unserializing instances of this class is forbidden.
     * @since 1.0.0
     */
    public function __construct(){
        // Define constant
        $this->define_constants();
        // Run the plugin
        $this->run_XStore_Advanced_Sticky_Header();
    }

    /**
     * Define Listdom Constants.
     * @since 1.0.0
     */
    private function define_constants() {
        /**
         * define XStore_Advanced_Sticky_Header_URL.
         *
         * @since 1.0.0
         */
        define( 'XStore_Advanced_Sticky_Header_Version', '1.0.1' );

        /**
         * define XStore_Advanced_Sticky_Header_THEME_MIN_VERSION.
         *
         * @since 1.0.0
         */
        define( 'XStore_Advanced_Sticky_Header_THEME_MIN_VERSION', '9.0.3' );

        /**
         * define XStore_Advanced_Sticky_Header_ET_CORE_MIN_VERSION.
         *
         * @since 1.0.0
         */
        define( 'XStore_Advanced_Sticky_Header_ET_CORE_MIN_VERSION', '5.0.3' );

        /**
         * define XStore_Advanced_Sticky_Header_PLUGIN_BASENAME.
         *
         * @since 1.0.0
         */
        define( 'XStore_Advanced_Sticky_Header_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

        /**
         * define XStore_Advanced_Sticky_Header_URL.
         *
         * @since 1.0.0
         */
        define( 'XStore_Advanced_Sticky_Header_URL', plugin_dir_url( __FILE__ ) );

        /**
         * define XStore_Advanced_Sticky_Header_DIR.
         *
         * @since 1.0.0
         */
        define( 'XStore_Advanced_Sticky_Header_DIR', plugin_dir_path( __FILE__ ) );

        /**
         * define XStore_Advanced_Sticky_HeaderSHORTCODES_IMAGES.
         *
         * @since 1.0.0
         */
//        define( 'XStore_Advanced_Sticky_HeaderSHORTCODES_IMAGES', XStore_Advanced_Sticky_Header_URL . 'images/' );

        /**
         * define XStore_Advanced_Sticky_HeaderCHANGELOG.
         *
         * @since 1.0.0
         */
        define( 'XStore_Advanced_Sticky_Header_CHANGELOG', 'https://8theme.com/import/update-history/xstore/' );
    }

    /**
     * Begins execution of the plugin.
     *
     * @since    1.0.0
     */
    public function run_XStore_Advanced_Sticky_Header() {

        // If Requirements are not met.
        if ( ! $this->plugin_requirements_checker() ) {

            add_action( 'admin_notices', array( $this, 'requirements_errors' ) );

            // Deactivate if requirements are not met.
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            deactivate_plugins( plugin_basename( __FILE__ ) );

            return false;
        }

        require_once XStore_Advanced_Sticky_Header_DIR . 'inc/core.php';
        require_once XStore_Advanced_Sticky_Header_DIR . 'inc/update.php';

        // Fire the plugin
        new XStore_Advanced_Sticky_Header\Inc\Core();

        register_activation_hook( __FILE__, array( new XStore_Advanced_Sticky_Header\Inc\Activator(), 'activate' ) );
        register_deactivation_hook( __FILE__, array( new XStore_Advanced_Sticky_Header\Inc\Deactivator(), 'deactivate' ) );

        // Fire the customizer class
        new XStore_Advanced_Sticky_Header\Modules\Customizer();

    }

    /**
     * Creates/Maintains the object of Requirements Checker Class
     *
     * @return boolean
     * @since 1.0.0
     */
    function plugin_requirements_checker() {
        static $requirements_checker = null;

        if ( null === $requirements_checker ) {
            $requirements_checker = $this->requirements_met();
        }

        return $requirements_checker;
    }

    /**
     * Checks if all plugins requirements are met or not
     *
     * @return boolean
     * @since 1.0.0
     */
    public function requirements_met() {

        if ( ! $this->is_php_version_ready() ) {
            return false;
        }

        if ( ! $this->is_wp_version_ready() ) {
            return false;
        }

        if ( ! $this->is_wp_multisite_ready() ) {
            return false;
        }

        if ( ! $this->plugin_compatibility() ) {
            return false;
        }

        // if ( ! $this->required_theme_version() ) {
        // return false;
        // }

        return true;
    }

    /**
     * Checks if Installed WP Version is higher than required WP Version
     *
     * @return boolean
     * @since 1.0.0
     */
    private function is_php_version_ready() {

        if ( ! version_compare( $this->min_php_version ,  PHP_VERSION, '>=' ) ) {
            return true;
        }

        $this->add_error_notice(
            'PHP ' . $this->min_php_version . '+ is required',
            'You\'re running version ' . PHP_VERSION
        );

        return false;
    }

    /**
     * Checks if Installed WP Version is higher than required WP Version
     *
     * @return boolean
     * @since 1.0.0
     */
    private function is_wp_version_ready() {
        global $wp_version;

        if ( ! version_compare( $this->min_wp_version, $wp_version, '>=' ) ) {
            return true;
        }

        $this->add_error_notice(
            'WordPress ' . $this->min_wp_version . '+ is required',
            'You\'re running version ' . $wp_version
        );

        return false;
    }

    /**
     * Checks if Multisite Dependencies are met
     *
     * @return boolean
     * @since 1.0.0
     */
    private function is_wp_multisite_ready() {
        $wp_multisite = is_multisite() && ( false === $this->multisite_compatible ) ? false : true;

        if ( false == $wp_multisite ) {
            $this->add_error_notice(
                'Your site is set up as a Network (Multisite)',
                'This plugin is not compatible with multisite environment'
            );
        }

        return $wp_multisite;
    }

    /**
     * Checks for compatibility
     *
     * @return boolean
     * @since 1.0.0
     */
    private function plugin_compatibility() {
        $plugins = array();

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if ( !is_plugin_active( 'et-core-plugin/et-core-plugin.php' ) ) {
            $plugins[] = 'XStore Core';
        }

        if ( count( $plugins ) ) {
            $this->add_error_notice(
                esc_html__( 'Attention!', 'xstore-advanced-sticky-header' ).
                esc_html__( 'XStore Core plugin should be enabled for correct work of XStore Advanced Sticky header plugin', 'xstore-advanced-sticky-header' ),
                ''
            );

            return false;
        }

        return true;
    }

    /**
     * ! Notice "Theme version"
     * @since 1.0.0
     */
    function required_theme_version(){

        if ( get_template_directory() !== get_stylesheet_directory() ) {
            $theme = wp_get_theme( 'xstore' );
        } else {
            $theme = wp_get_theme();
        }

        if ( $theme->name == ('XStore') &&  version_compare( $theme->version, XStore_Advanced_Sticky_Header_THEME_MIN_VERSION, '<' ) ) {
            $this->add_error_notice(
                __( 'XStore Advanced Sticky header plugin requires the following theme: XStore '. XStore_Advanced_Sticky_Header_THEME_MIN_VERSION .' or higher.', 'xstore-advanced-sticky-header' ),
                ''
            );

            return false;
        }

        return true;
    }

    /**
     * Adds Error message in $errors variable
     *
     * @param string $error_message Error Message.
     * @param string $supportive_information.
     * @return void
     * @since 1.0.0
     */
    private function add_error_notice( $error_message, $supportive_information ) {
        $this->errors[] = (object) [
            'error_message' => $error_message,
            'supportive_information' => $supportive_information,
        ];
    }

    /**
     * Prints an error that the system requirements weren't met.
     *
     * @since    1.0.0
     */
    public function requirements_errors() {
        $errors = $this->errors;
        require_once( XStore_Advanced_Sticky_Header_DIR . 'templates/admin/errors/requirements-error.php' );
    }
}


/**
 * Main instance of XStore_Advanced_Sticky_Header.
 *
 * Returns the main instance of XStore_Advanced_Sticky_Header to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return XStore_Advanced_Sticky_Header
 */
function initial_XStore_Advanced_Sticky_Header() {
    return XStore_Advanced_Sticky_Header_Initial::instance();
}

// Init the XStore_Advanced_Sticky_Header :)
initial_XStore_Advanced_Sticky_Header();
