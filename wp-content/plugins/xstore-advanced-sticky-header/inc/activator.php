<?php
namespace XStore_Advanced_Sticky_Header\Inc;

/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 * @package    XStore_Advanced_Sticky_Header
 * @subpackage XStore_Advanced_Sticky_Header/includes
 */
class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
        if ( class_exists('ETC\App\Controllers\Upgrade') && ! get_option( 'xstore_advanced_sticky_header_first_activated', false ) ) {
            update_option( 'xstore_kirki_styles_render', 'generate' );
            $this->reinit_customizer_styles();
        }
	}

    /**
     * Register widget args
     *
     * @return mixed|null|void
     */
    public function reinit_customizer_styles() {
        require_once ET_CORE_DIR . 'packages/kirki/kirki.php';
        require_once( ET_CORE_DIR . 'app/models/customizer/webfont-extend.php' );
        /**
         * Load customize-builder.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/class-ajax-search.php' );

        /**
         * Load builder functions.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/functions.php' );

        /**
         * Load customizer addons.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/addons.php' );

        /**
         * Customizer import/export plugin
         *
         * @since 1.0.0
         */
        if ( ! defined( 'CEI_PLUGIN_DIR' ) ) {
            require_once( ET_CORE_DIR . 'packages/customizer-export-import/customizer-export-import.php' );
        }

        add_action( 'init', array( $this, 'init_xstore_core_customizer' ), 11 );

    }

    public function init_xstore_core_customizer() {

        /**
         * Load customize-builder icons.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/icons.php' );

        /**
         * Load customize-builder options callbacks.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/global/callbacks.php' );

        if ( ! defined('ETHEME_CODE_CUSTOMIZER_IMAGES') ) return;

        /**
         * Load customize-builder options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/global/global.php' );

        /**
         * Load customize-builder options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/header-builder/global.php' );

        /**
         * Load customize-mobile-panel options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/mobile-panel/mobile-panel.php' );

        /**
         * Load customize-builder options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/product-single-builder/global.php' );

        /**
         * Load customize-cart-checkout options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/cart-checkout/global.php' );

        /**
         * Load customize-site-sections options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/site-sections/sections.php' );

        /**
         * Load customize-age-verify-popup options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/age-verify-popup/popup.php' );

        /**
         * Load customize-general-gdpr-cookies options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/gdpr/gdpr.php' );

        /**
         * Load customize-xstore-wishlist options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/wishlist/global.php' );

        /**
         * Load customize-xstore-compare options.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/compare/global.php' );

        /**
         * Load customize-builder.
         *
         * @since 1.0.0
         */
        require_once( ET_CORE_DIR . 'app/models/customizer/builder/class-customize-builder.php' );

        // Default Customizer
        $Customizer = ETC\App\Controllers\Customizer::get_instance( 'ETC\App\Models\Customizer' );
        $Customizer->customizer_style('kirki-styles');
        update_option( 'xstore_kirki_styles_render', 'regenerated' );

        // Multiple header
        $Etheme_Customize_header_Builder = new \Etheme_Customize_header_Builder();
        $Etheme_Customize_header_Builder->generate_header_builder_style('all');
        update_option( 'xstore_kirki_hb_render', 'regenerated' );

        // Multiple products
        $Etheme_Customize_header_Builder->generate_single_product_style('all');
        update_option( 'xstore_kirki_sp_render', 'regenerated' );

        // Upgraded options
        update_option( 'xstore_advanced_sticky_header_first_activated', true );

    }

}
