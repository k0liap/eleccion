<?php
namespace XStore_Advanced_Sticky_Header\Inc;

/**
 * Define the internationalization functionality
 *
 * @since      1.0.0
 * @package    XStore_Advanced_Sticky_Header
 * @subpackage XStore_Advanced_Sticky_Header/includes
 */
class i18n {

	/**
	 * The domain specified for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $domain
	 */
	private $domain;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		
		$locale = apply_filters( 'plugin_locale', get_locale(), 'xstore-advanced-sticky-header' );

		load_textdomain( 'xstore-advanced-sticky-header', WP_LANG_DIR . '/xstore-advanced-sticky-header/xstore-advanced-sticky-header-' . $locale . '.mo' );

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	/**
	 * Set the domain.
	 *
	 * @since    1.0.0
	 * @param    string $domain
	 */
	public function set_domain( $domain ) {
		$this->domain = $domain;
	}

}
