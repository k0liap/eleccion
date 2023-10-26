<?php
/**
 * The template created for displaying header sticky options
 *
 * @version 1.0.0
 * @since   1.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {

    $args = array(
        'advanced_header_sticky_general' => array(
            'name'       => 'advanced_header_sticky_general',
            'title'      => esc_html__( 'General', 'xstore-advanced-sticky-header' ),
            'panel'      => 'advanced-sticky-header',
            'icon'       => 'dashicons-admin-generic',
            'type'       => 'kirki-lazy',
            'dependency' => array()
        )
    );

    return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/advanced_header_sticky_general', function ( $fields ) use ( $separators, $strings ) {
    $args = array();

    // Array of fields
    $args = array(

        // content separator
        'advanced_header_sticky_content_separator'              => array(
            'name'     => 'advanced_header_sticky_content_separator',
            'type'     => 'custom',
            'settings' => 'advanced_header_sticky_content_separator',
            'section'  => 'advanced_header_sticky_general',
            'default'  => $separators['content'],
        ),
        'main_advanced_header_sticky_layout_et-desktop'                => array(
            'name'     => 'main_advanced_header_sticky_layout_et-desktop',
            'type'     => 'select',
            'settings' => 'main_advanced_header_sticky_layout_et-desktop',
            'label'    => esc_html__( 'Layout', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__( 'Choose the layout type for the content in the sticky header.', 'xstore-advanced-sticky-header' ) . '<br/>' .
                esc_html__('Choose "Custom" to set your own column sizes for the content in the sticky header.', 'xstore-advanced-sticky-header'),
            'section'  => 'advanced_header_sticky_general',
            'default'  => '3_6_3',
            'multiple' => 1,
            'choices'  => array(
                '3_6_3' => '25% + 50% + 25%',
                '2_7_3' => '16% + 59% + 25%',
                '4_4_4' => '33.33% + 33.33% + 33.33%',
                '2_8_2' => '16% + 68% + 16%',
                'custom' => esc_html__('Custom', 'xstore-advanced-sticky-header'),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'main_advanced_header_sticky_layout_et-desktop' => array(
                    'selector'        => '.sticky-header-wrapper .sticky-header-main-wrapper > .sticky-header-main > .et-row-container > .et-wrap-columns',
                    'render_callback' => function() {
                        xstore_advanced_sticky_header_callback('main');
                    }
                ),
            ),
        ),
        'main_advanced_header_sticky_layout_et-mobile'                => array(
            'name'     => 'main_advanced_header_sticky_layout_et-mobile',
            'type'     => 'select',
            'settings' => 'main_advanced_header_sticky_layout_et-mobile',
            'label'    => esc_html__( 'Layout', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__( 'Choose the layout type for the content in the sticky header on mobile devices.', 'xstore-advanced-sticky-header' ) . '<br/>' .
                esc_html__('Choose "Custom" to set your own column sizes for the content in the sticky header.', 'xstore-advanced-sticky-header'),
            'section'  => 'advanced_header_sticky_general',
            'default'  => '6_6',
            'multiple' => 1,
            'choices'  => array(
                '3_6_3' => '25% + 50% + 25%',
                '6_6' => '50% + 50%',
                '4_4_4' => '33.33% + 33.33% + 33.33%',
                '2_8_2' => '16% + 68% + 16%',
                'custom' => esc_html__('Custom', 'xstore-advanced-sticky-header'),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'main_advanced_header_sticky_layout_et-mobile' => array(
                    'selector'        => '.sticky-mobile-header-wrapper .sticky-mobile-header-main-wrapper > .sticky-mobile-header-main > .et-row-container > .et-wrap-columns',
                    'render_callback' => function() {
                        xstore_advanced_sticky_header_callback('main', true);
                    }
                ),
            ),
        ),

        // main_advanced_header_sticky_layout_custom_section1
        'main_advanced_header_sticky_layout_custom_section1_et-desktop' => array(
            'name'            => 'main_advanced_header_sticky_layout_custom_section1_et-desktop',
            'type'            => 'slider',
            'settings'        => 'main_advanced_header_sticky_layout_custom_section1_et-desktop',
            'label'           => esc_html__( 'Section 01 width (%)', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__( 'This controls the width of Section 01 in the sticky header.', 'xstore-advanced-sticky-header' ),
            'section'         => 'advanced_header_sticky_general',
            'default'         => 25,
            'choices'         => array(
                'min'  => '0',
                'max'  => '99',
                'step' => '1',
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'main_advanced_header_sticky_layout_custom_section1_et-desktop' => array(
                    'selector'        => '.sticky-header-wrapper .sticky-header-main-wrapper > .sticky-header-main > .et-row-container > .et-wrap-columns',
                    'render_callback' => function() {
                        xstore_advanced_sticky_header_callback('main');
                    }
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'main_advanced_header_sticky_layout_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // main_advanced_header_sticky_layout_custom_section1
        'main_advanced_header_sticky_layout_custom_section1_et-mobile' => array(
            'name'            => 'main_advanced_header_sticky_layout_custom_section1_et-mobile',
            'type'            => 'slider',
            'settings'        => 'main_advanced_header_sticky_layout_custom_section1_et-mobile',
            'label'           => esc_html__( 'Section 01 width (%)', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__( 'This controls the width of Section 01 in the sticky header on mobile devices.', 'xstore-advanced-sticky-header' ),
            'section'         => 'advanced_header_sticky_general',
            'default'         => 25,
            'choices'         => array(
                'min'  => '0',
                'max'  => '99',
                'step' => '1',
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'main_advanced_header_sticky_layout_custom_section1_et-mobile' => array(
                    'selector'        => '.sticky-mobile-header-wrapper .sticky-mobile-header-main-wrapper > .sticky-mobile-header-main > .et-row-container > .et-wrap-columns',
                    'render_callback' => function() {
                        xstore_advanced_sticky_header_callback('main', true);
                    }
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'main_advanced_header_sticky_layout_et-mobile',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),


        // main_advanced_header_sticky_layout_custom_section2
        'main_advanced_header_sticky_layout_custom_section2_et-desktop' => array(
            'name'            => 'main_advanced_header_sticky_layout_custom_section2_et-desktop',
            'type'            => 'slider',
            'settings'        => 'main_advanced_header_sticky_layout_custom_section2_et-desktop',
            'label'           => esc_html__( 'Section 02 width (%)', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__( 'This controls the width of Section 02 in the sticky header.', 'xstore-advanced-sticky-header' ),
            'section'         => 'advanced_header_sticky_general',
            'default'         => 50,
            'choices'         => array(
                'min'  => '0',
                'max'  => '99',
                'step' => '1',
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'main_advanced_header_sticky_layout_custom_section2_et-desktop' => array(
                    'selector'        => '.sticky-header-wrapper .sticky-header-main-wrapper > .sticky-header-main > .et-row-container > .et-wrap-columns',
                    'render_callback' => function() {
                        xstore_advanced_sticky_header_callback('main');
                    }
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'main_advanced_header_sticky_layout_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // main_advanced_header_sticky_layout_custom_section2
        'main_advanced_header_sticky_layout_custom_section2_et-mobile' => array(
            'name'            => 'main_advanced_header_sticky_layout_custom_section2_et-mobile',
            'type'            => 'slider',
            'settings'        => 'main_advanced_header_sticky_layout_custom_section2_et-mobile',
            'label'           => esc_html__( 'Section 02 width (%)', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__( 'This controls the width of Section 02 in the sticky header on mobile devices.', 'xstore-advanced-sticky-header' ),
            'section'         => 'advanced_header_sticky_general',
            'default'         => 50,
            'choices'         => array(
                'min'  => '0',
                'max'  => '99',
                'step' => '1',
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'main_advanced_header_sticky_layout_custom_section2_et-mobile' => array(
                    'selector'        => '.sticky-mobile-header-wrapper .sticky-mobile-header-main-wrapper > .sticky-mobile-header-main > .et-row-container > .et-wrap-columns',
                    'render_callback' => function() {
                        xstore_advanced_sticky_header_callback('main', true);
                    }
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'main_advanced_header_sticky_layout_et-mobile',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // main_advanced_header_sticky_layout_custom_section3
        'main_advanced_header_sticky_layout_custom_section3_et-desktop' => array(
            'name'            => 'main_advanced_header_sticky_layout_custom_section3_et-desktop',
            'type'            => 'slider',
            'settings'        => 'main_advanced_header_sticky_layout_custom_section3_et-desktop',
            'label'           => esc_html__( 'Section 03 width (%)', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__( 'This controls the width of Section 03 in the sticky header.', 'xstore-advanced-sticky-header' ),
            'section'         => 'advanced_header_sticky_general',
            'default'         => 25,
            'choices'         => array(
                'min'  => '0',
                'max'  => '99',
                'step' => '1',
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'main_advanced_header_sticky_layout_custom_section3_et-desktop' => array(
                    'selector'        => '.sticky-header-wrapper .sticky-header-main-wrapper > .sticky-header-main > .et-row-container > .et-wrap-columns',
                    'render_callback' => function() {
                        xstore_advanced_sticky_header_callback('main');
                    }
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'main_advanced_header_sticky_layout_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // main_advanced_header_sticky_layout_custom_section3
        'main_advanced_header_sticky_layout_custom_section3_et-mobile' => array(
            'name'            => 'main_advanced_header_sticky_layout_custom_section3_et-mobile',
            'type'            => 'slider',
            'settings'        => 'main_advanced_header_sticky_layout_custom_section3_et-mobile',
            'label'           => esc_html__( 'Section 03 width (%)', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__( 'This controls the width of Section 03 in the sticky header on mobile devices.', 'xstore-advanced-sticky-header' ),
            'section'         => 'advanced_header_sticky_general',
            'default'         => 25,
            'choices'         => array(
                'min'  => '0',
                'max'  => '99',
                'step' => '1',
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'main_advanced_header_sticky_layout_custom_section3_et-mobile' => array(
                    'selector'        => '.sticky-mobile-header-wrapper .sticky-mobile-header-main-wrapper > .sticky-mobile-header-main > .et-row-container > .et-wrap-columns',
                    'render_callback' => function() {
                        xstore_advanced_sticky_header_callback('main', true);
                    }
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'main_advanced_header_sticky_layout_et-mobile',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // advanced_header_sticky_type
        'advanced_header_sticky_type_et-desktop'                => array(
            'name'     => 'advanced_header_sticky_type_et-desktop',
            'type'     => 'radio-buttonset',
            'settings' => 'advanced_header_sticky_type_et-desktop',
            'label'    => esc_html__( 'Sticky type', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__('Our Header Sticky comes in few variations: Smart and Custom, so you can choose the best option to fit your specific needs. Give them a try and see the difference for yourself.', 'xstore-advanced-sticky-header'),
            'section'  => 'advanced_header_sticky_general',
            'default'  => 'custom',
            'multiple' => 1,
            'choices'  => array(
//                'sticky' => esc_html__( 'Sticky', 'xstore-advanced-sticky-header' ),
                'smart'  => esc_html__( 'Smart', 'xstore-advanced-sticky-header' ),
                'custom' => esc_html__( 'Custom', 'xstore-advanced-sticky-header' ),
            ),
        ),

        // advanced_header_sticky_general_animation
        'advanced_header_sticky_general_animation_et-desktop'          => array(
            'name'            => 'advanced_header_sticky_general_animation_et-desktop',
            'type'            => 'select',
            'settings'        => 'advanced_header_sticky_general_animation_et-desktop',
            'label'           => esc_html__( 'Animation type', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__('You can choose from a range of customizable animations to create a sticky header that matches your brand and website design. Our animations are smooth and seamless, making navigation a breeze for your visitors.', 'xstore-advanced-sticky-header'),
            'section'         => 'advanced_header_sticky_general',
            'default'         => 'toBottomFull',
            'choices'         => array(
                'toBottomFull' => esc_html__( 'Jump down', 'xstore-advanced-sticky-header' ),
                'fadeIn'       => esc_html__( 'Fade in', 'xstore-advanced-sticky-header' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'advanced_header_sticky_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'element'  => '.sticky-site-header.sticky-on .sticky-header-wrapper, .sticky-site-header.sticky-on .sticky-mobile-header-wrapper',
                    'property' => 'animation-name',
                    'prefix'   => 'et-',
                    'context'  => array( 'editor', 'front' )
                ),
            ),
        ),

        // advanced_header_sticky_general_animation_duration
        'advanced_header_sticky_general_animation_duration_et-desktop' => array(
            'name'            => 'advanced_header_sticky_general_animation_duration_et-desktop',
            'type'            => 'slider',
            'settings'        => 'advanced_header_sticky_general_animation_duration_et-desktop',
            'label'           => esc_html__( 'Animation duration (sec)', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__('This feature allows you to customize the duration of the animations on your sticky header.', 'xstore-advanced-sticky-header'),
            'section'         => 'advanced_header_sticky_general',
            'default'         => .7,
            'choices'         => array(
                'min'  => '.1',
                'max'  => '3',
                'step' => '.1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'advanced_header_sticky_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'element'  => '.sticky-site-header.sticky-on .sticky-header-wrapper, .sticky-site-header.sticky-on .sticky-mobile-header-wrapper',
                    'property' => 'animation-duration',
                    'units'    => 's',
                    'context'  => array( 'editor', 'front' )
                ),
            ),
        ),

        // advanced_header_sticky_general_start
        'advanced_header_sticky_general_start_et-desktop'              => array(
            'name'            => 'advanced_header_sticky_general_start_et-desktop',
            'type'            => 'slider',
            'settings'        => 'advanced_header_sticky_general_start_et-desktop',
            'label'           => esc_html__( 'Start sticky header on scroll (px)', 'xstore-advanced-sticky-header' ),
            'tooltip' => esc_html__('With this feature, you can choose at which point your header becomes sticky as your visitors scroll down the page. Choose the perfect starting point for your sticky header based on your website\'s layout and user engagement.', 'xstore-advanced-sticky-header'),
            'section'         => 'advanced_header_sticky_general',
            'default'         => 80,
            'choices'         => array(
                'min'  => '0',
                'max'  => '500',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'advanced_header_sticky_on_scroll_et-desktop',
                    'operator' => '!=',
                    'value'    => 1,
                ),
                array(
                    'setting'  => 'advanced_header_sticky_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // advanced_header_sticky_general_logo_img
        'advanced_header_sticky_general_logo_img_et-desktop'           => array(
            'name'            => 'advanced_header_sticky_general_logo_img_et-desktop',
            'type'            => 'image',
            'settings'        => 'advanced_header_sticky_general_logo_img_et-desktop',
            'label'           => $strings['label']['sticky_logo'],
            'tooltip'     => $strings['description']['sticky_logo'] . ' <span class="et_edit" data-parent="logo" data-section="logo_content_separator" style="text-decoration: underline;">' . esc_html__( 'Width settings', 'xstore-advanced-sticky-header' ) . '</span>',
            'section'         => 'advanced_header_sticky_general',
            'default'         => '',
            'choices'         => array(
                'save_as' => 'array',
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'advanced_header_sticky_general_logo_img_et-desktop' => array(
                    'selector'        => '.et_b_header-logo.et_element-top-level span.fixed',
                    'render_callback' => function () {

                        $logo_old = get_theme_mod('logo_img_et-desktop', array(
                            'url' => ETHEME_BASE_URI . 'theme/assets/images/logo.png',
                            'alt' => 'header logo'
                        ));
                        $logo         = get_theme_mod( "advanced_header_sticky_general_logo_img_et-desktop", '' );

                        if ( ! is_array( $logo ) || empty( $logo ) ) {
                            $logo = get_theme_mod( "logo_img_et-desktop", '' );
                        }

                        if ( ! isset( $logo['url'] ) || $logo['url'] == '' ) {
                            $logo['url'] = $logo_old['url'];
                        }

                        if ( isset( $logo['id'] ) && $logo['id'] != '' ) {
                            $logo['alt'] = get_post_meta( $logo['id'], '_wp_attachment_image_alt', true );
                        }

                        $logo['alt'] = ! empty( $logo['alt'] ) ? $logo['alt'] : '';

                        echo '<img src="' . esc_url( $logo['url'] ) . '" alt="' . $logo['alt'] . '">';
                    },
                ),
            ),
        ),

        // style separator
        'advanced_header_sticky_general_style_separator'               => array(
            'name'     => 'advanced_header_sticky_general_style_separator',
            'type'     => 'custom',
            'settings' => 'advanced_header_sticky_general_style_separator',
            'section'  => 'advanced_header_sticky_general',
            'default'  => $separators['style'],
            'priority' => 10,
        ),

        // top_header_wide
        'main_advanced_header_sticky_wide_et-desktop'       => array(
            'name'      => 'main_advanced_header_sticky_wide_et-desktop',
            'type'      => 'toggle',
            'settings'  => 'main_advanced_header_sticky_wide_et-desktop',
            'label'     => $strings['label']['wide_header'],
            'tooltip' => $strings['description']['wide_header'],
            'section'   => 'advanced_header_sticky_general',
            'default'   => '0',
            'transport' => 'postMessage',
            'js_vars'   => array(
                array(
                    'element'  => '.sticky-header-main-wrapper .sticky-header-main > .et-row-container',
                    'function' => 'toggleClass',
                    'class'    => 'et-container',
                    'value'    => false
                ),
            ),
        ),

        // main_advanced_header_sticky_height
        'main_advanced_header_sticky_height_et-desktop'          => array(
            'name'            => 'main_advanced_header_sticky_height_et-desktop',
            'type'            => 'slider',
            'settings'        => 'main_advanced_header_sticky_height_et-desktop',
            'label'     => $strings['label']['min_height'],
            'tooltip' => $strings['description']['min_height'],
            'section'         => 'advanced_header_sticky_general',
            'default'         => 70,
            'choices'         => array(
                'min'  => '0',
                'max'  => '300',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'advanced_header_sticky_type_et-desktop',
                    'operator' => '!=',
                    'value'    => 'sticky',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.sticky .sticky-header-main .et-wrap-columns, .sticky-site-header[data-type="smart"].sticky .sticky-header-main .et-wrap-columns',
                    'property' => 'min-height',
                    'units'    => 'px',
                ),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.sticky-site-header.sticky .sticky-header-main .widget_nav_menu .menu > li > a, .sticky-site-header[data-type="smart"].sticky .sticky-header-main .widget_nav_menu .menu > li > a,
									.sticky-site-header.sticky .sticky-header-main #lang_sel a.lang_sel_sel, .sticky-site-header[data-type="smart"].sticky .sticky-header-main #lang_sel a.lang_sel_sel,
									.sticky-site-header.sticky .sticky-header-main .wcml-dropdown a.wcml-cs-item-toggle, .sticky-site-header[data-type="smart"].sticky .sticky-header-main .wcml-dropdown a.wcml-cs-item-toggle',
                    'property' => 'line-height',
                    'units'    => 'px',
                ),
            ),
        ),

        // main_advanced_header_sticky_height
        'main_advanced_header_sticky_height_et-mobile'           => array(
            'name'            => 'main_advanced_header_sticky_height_et-mobile',
            'type'            => 'slider',
            'settings'        => 'main_advanced_header_sticky_height_et-mobile',
            'label'     => $strings['label']['min_height'],
            'tooltip' => $strings['description']['min_height'],
            'section'         => 'advanced_header_sticky_general',
            'default'         => 60,
            'choices'         => array(
                'min'  => '0',
                'max'  => '300',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'advanced_header_sticky_type_et-desktop',
                    'operator' => '!=',
                    'value'    => 'sticky',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.sticky .sticky-mobile-header-wrapper .sticky-mobile-header-main .et-wrap-columns,
											  .sticky-site-header[data-type="smart"].sticky .sticky-mobile-header-wrapper .sticky-mobile-header-main .et-wrap-columns',
                    'property' => 'min-height',
                    'units'    => 'px'
                ),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.sticky .sticky-mobile-header-wrapper .sticky-header-main .widget_nav_menu .menu > li > a, .sticky-site-header[data-type="smart"].sticky .sticky-mobile-header-wrapper .sticky-header-main .widget_nav_menu .menu > li > a,
						.sticky .sticky-mobile-header-wrapper .sticky-header-main #lang_sel a.lang_sel_sel, .sticky-site-header[data-type="smart"].sticky .sticky-mobile-header-wrapper .sticky-header-main #lang_sel a.lang_sel_sel,
						.sticky .sticky-mobile-header-wrapper .sticky-header-main .wcml-dropdown a.wcml-cs-item-toggle, .sticky-site-header[data-type="smart"].sticky .sticky-mobile-header-wrapper .sticky-header-main .wcml-dropdown a.wcml-cs-item-toggle',
                    'property' => 'line-height',
                    'units'    => 'px'
                ),
            ),
        ),

        // main_advanced_header_sticky_background
        'main_advanced_header_sticky_background_et-desktop'      => array(
            'name'            => 'main_advanced_header_sticky_background_et-desktop',
            'type'            => 'background',
            'settings'        => 'main_advanced_header_sticky_background_et-desktop',
            'label'           => esc_html__( 'Header WCAG Control', 'xstore-advanced-sticky-header' ),
            'tooltip'     => $strings['description']['wcag_bg_color'],
            'section'         => 'advanced_header_sticky_general',
            'default'         => array(
                'background-color'      => '#ffffff',
                'background-image'      => '',
                'background-repeat'     => 'no-repeat',
                'background-position'   => 'center center',
                'background-size'       => '',
                'background-attachment' => '',
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.sticky .sticky-header-main',
                ),
            ),
        ),

        // main_advanced_header_sticky_background
        'main_advanced_header_sticky_background_et-mobile'       => array(
            'name'            => 'main_advanced_header_sticky_background_et-mobile',
            'type'            => 'background',
            'settings'        => 'main_advanced_header_sticky_background_et-mobile',
            'label'           => esc_html__( 'Header WCAG Control', 'xstore-advanced-sticky-header' ),
            'tooltip'     => $strings['description']['wcag_bg_color'],
            'section'         => 'advanced_header_sticky_general',
            'default'         => array(
                'background-color'      => '#ffffff',
                'background-image'      => '',
                'background-repeat'     => 'no-repeat',
                'background-position'   => 'center center',
                'background-size'       => '',
                'background-attachment' => '',
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.sticky-mobile-header-wrapper .sticky-on .sticky-mobile-header-main, .sticky-on .sticky-mobile-header-wrapper .sticky-mobile-header-main',
                ),
            ),
        ),
        'main_advanced_header_sticky_color_et-desktop'           => array(
            'name'            => 'main_advanced_header_sticky_color_et-desktop',
            'settings'        => 'main_advanced_header_sticky_color_et-desktop',
            'label'           => $strings['label']['wcag_color'],
            'tooltip'     => $strings['description']['wcag_color'],
            'type'            => 'kirki-wcag-tc',
            'section'         => 'advanced_header_sticky_general',
            'default'         => '#000000',
            'choices'         => array(
                'setting' => 'setting(advanced_header_sticky_general)(main_advanced_header_sticky_background_et-desktop)[background-color]',
                // 'maxHueDiff'          => 60,   // Optional.
                // 'stepHue'             => 15,   // Optional.
                // 'maxSaturation'       => 0.5,  // Optional.
                // 'stepSaturation'      => 0.1,  // Optional.
                // 'stepLightness'       => 0.05, // Optional.
                // 'precissionThreshold' => 6,    // Optional.
                // 'contrastThreshold'   => 4.5   // Optional.
                'show'    => array(
                    // 'auto'        => false,
                    // 'custom'      => false,
                    'recommended' => false,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.sticky-on .sticky-header-main',
                    'property' => 'color'
                )
            ),
        ),

        // main_advanced_header_sticky_color
        'main_advanced_header_sticky_color_et-mobile'            => array(
            'name'            => 'main_advanced_header_sticky_color_et-mobile',
            'settings'        => 'main_advanced_header_sticky_color_et-mobile',
            'label'           => $strings['label']['wcag_color'],
            'tooltip'     => $strings['description']['wcag_color'],
            'type'            => 'kirki-wcag-tc',
            'section'         => 'advanced_header_sticky_general',
            'default'         => '#000000',
            'choices'         => array(
                'setting' => 'setting(advanced_header_sticky_general)(main_advanced_header_sticky_background_et-mobile)[background-color]',
                // 'maxHueDiff'          => 60,   // Optional.
                // 'stepHue'             => 15,   // Optional.
                // 'maxSaturation'       => 0.5,  // Optional.
                // 'stepSaturation'      => 0.1,  // Optional.
                // 'stepLightness'       => 0.05, // Optional.
                // 'precissionThreshold' => 6,    // Optional.
                // 'contrastThreshold'   => 4.5   // Optional.
                'show'    => array(
                    // 'auto'        => false,
                    // 'custom'      => false,
                    'recommended' => false,
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.sticky-mobile-header-wrapper .sticky-on .sticky-mobile-header-main, .sticky-on .sticky-mobile-header-wrapper .sticky-mobile-header-main',
                    'property' => 'color'
                )
            ),
        ),

    );

    return array_merge( $fields, $args );

} );
