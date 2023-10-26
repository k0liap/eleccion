<?php
namespace XStore_Advanced_Sticky_Header\Modules;

class Customizer {
    public static $key = 'xstore-advanced-sticky-header';
    public function __construct()
    {
        require_once( XStore_Advanced_Sticky_Header_DIR . 'modules/customizer/functions.php' );
        add_action( 'init', array( $this, 'customizer_init' ), 20 );
        add_action('wp', array($this, 'disable_old_sticky_headers'), 10);
        $this->assets();
        add_action( 'customizer_after_including_fields', array( $this, 'customizer_field' ), 20 );
        add_action('etheme_header_start', array($this, 'sticky_header_template'), -999);
    }
    public function customizer_init() {
        /**
         * Load customize-builder options callbacks.
         *
         * @since 1.0.0
         */
        require_once( XStore_Advanced_Sticky_Header_DIR . 'modules/customizer/theme-options/global/callbacks.php' );

    }
    public function customizer_field() {

        /**
         * Load customize-builder options.
         *
         * @since 1.0.0
         */
        require_once( XStore_Advanced_Sticky_Header_DIR . 'modules/customizer/theme-options/global/global.php' );

        /**
         * Load customize-builder options.
         *
         * @since 1.0.0
         */
        require_once( XStore_Advanced_Sticky_Header_DIR . 'modules/customizer/theme-options/sticky-header/global.php' );
    }

    /**
     * Enqueue style/scripts action
     */
    public function assets() {
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'), 30 );
        add_action( 'customize_controls_print_styles', array( $this, 'admin_styles_customizer' ), 100 );
    }

    public function enqueue_scripts(){
        // Enqueue the script.
        wp_register_script(
            self::$key,
            XStore_Advanced_Sticky_Header_URL . 'assets/js/scripts.min.js',
            array(
                'jquery',
                'etheme',
            ),
            XStore_Advanced_Sticky_Header_Version,
            false
        );

        wp_enqueue_script( self::$key );

        wp_register_style(
            self::$key,
            XStore_Advanced_Sticky_Header_URL . 'assets/css/style.css',
            false,
            XStore_Advanced_Sticky_Header_Version,
            'all' );

        $element_options = array();

        if ( !get_query_var('et_mobile-optimization', false) ) {
            $element_options['media_query'] = get_theme_mod('mobile_header_start_from', 992);
            ob_start(); ?>
                @media only screen and (max-width: <?php echo esc_html($element_options['media_query']); ?>px) {
                    .sticky-header-wrapper {
                        display: none;
                    }
                }

                @media only screen and (min-width: <?php echo esc_html($element_options['media_query'] + 1); ?>px) {
                    .sticky-mobile-header-wrapper {
                        display: none;
                    }
                }
            <?php wp_add_inline_style(self::$key, ob_get_clean());
        }

        wp_enqueue_style( self::$key );

    }

    public function admin_styles_customizer() {
        wp_register_style( 'admin-xstore-advanced-sticky-header', false );
        wp_enqueue_style( 'admin-xstore-advanced-sticky-header' );
        $output = '';
        $output .= "#customize-theme-controls #sub-accordion-panel-header-builder {
            display: flex;
            flex-direction: column;
        }
        
        #customize-theme-controls #sub-accordion-panel-header-builder .panel-meta, 
        #accordion-section-logo,
        #accordion-section-header_presets,
        #accordion-section-top_header,
        #accordion-section-main_header,
        #accordion-section-bottom_header,
        #accordion-section-headers_sticky {
            order: -1;
        }
        #accordion-panel-advanced-sticky-header .screen-reader-text {
            clip: unset;
            height: auto;
            margin: 0;
            clip-path: none;
            -webkit-clip-path: none;
            width: auto;
            position: static;
            font-size: 0 !important;
        }
        
        #accordion-panel-advanced-sticky-header .screen-reader-text:before {
            content: 'Extra';
        }";
        wp_add_inline_style( 'admin-xstore-advanced-sticky-header', $output );
    }

    /**
     * Disable old headers sticky to prevent overlapping multiple sticky headers
     *
     * @return void
     * @version 1.0.0
     * @since   1.0.0
     */
    public function disable_old_sticky_headers() {
        add_filter('et_sticky_headers_should_render', '__return_false', 999);
    }
    /**
     * Return header sticky html.
     *
     * @return  {html} html of sticky-header
     * @version 1.0.0
     * @since   1.0.0
     */
    public function sticky_header_template() {
        require_once( XStore_Advanced_Sticky_Header_DIR . 'templates/frontend/sticky-header.php' );
    }
}