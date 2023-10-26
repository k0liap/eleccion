<?php
/**
 * The template created for displaying advanced header sticky elements options
 *
 * @version 1.0.0
 * @since   1.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'advanced-sticky-header-elements' => array(
			'name'       => 'advanced-sticky-header-elements',
			'title'      => esc_html__( 'Elements', 'xstore-advanced-sticky-header' ),
			'panel'      => 'advanced-sticky-header',
			'icon'       => 'dashicons-screenoptions',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);

    return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/advanced-sticky-header-elements', function ( $fields ) use ($sep_style, $strings) {
    $elements = require( ET_CORE_DIR . 'app/models/customizer/builder/elements.php' );

    unset($elements['connect_block']);

    $elements = array_filter($elements, function ($val) {
        return (in_array('header', $val['location']));
    });
    $elements_ready = array();
    foreach ($elements as $key => $value) {
        $elements_ready[$key] = $elements[$key]['title'];
    }

    $args = array();
    for ($number=1; $number<=3; $number++) {
        $default_desktop = array('logo');
        $default_mobile = array('logo');
        switch ($number) {
            case '2':
                $default_desktop = array('main_menu');
                $default_mobile = class_exists('WooCommerce') ? array('account', 'wishlist', 'cart', 'mobile_menu') : array('mobile_menu');
                break;
            case '3':
                $default_desktop = class_exists('WooCommerce') ? array('account', 'wishlist', 'cart') : array('account');
                $default_mobile = array();
                break;
        }
        $callbacks = array(
            'desktop' => array(
                'selector'        => '.sticky-header-wrapper .sticky-header-main-wrapper > .sticky-header-main > .et-row-container > .et-wrap-columns',
                'render_callback' => function() {
                    xstore_advanced_sticky_header_callback('main');
                }
            ),
            'mobile' => array(
                'selector'        => '.sticky-mobile-header-wrapper .sticky-mobile-header-main-wrapper > .sticky-mobile-header-main > .et-row-container > .et-wrap-columns',
                'render_callback' => function() {
                    xstore_advanced_sticky_header_callback('main', true);
                }
            )
        );
        $separator_args = array(
            'name'            => 'main_advanced_sticky_header_elements_section'.$number.'_separator',
            'type'            => 'custom',
            'settings'        => 'main_advanced_sticky_header_elements_section'.$number.'_separator',
            'section'         => 'advanced-sticky-header-elements',
            'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-list-view"></span> <span style="padding-inline-start: 3px;">' . sprintf(esc_html__( 'Section %s', 'xstore-advanced-sticky-header' ), $number) . '</span></div>',
            'transport'       => 'postMessage',
        );

        $content_desktop_args = array(
            'name'            => 'main_advanced_sticky_header_elements_section'.$number.'_content_et-desktop',
            'type'            => 'sortable',
            'label'           => $strings['label']['elements'],
            'tooltip'     => $strings['description']['elements'],
            'section'         => 'advanced-sticky-header-elements',
            'settings'        => 'main_advanced_sticky_header_elements_section'.$number.'_content_et-desktop',
            'default'         => $default_desktop,
            'choices'         => $elements_ready,
            'transport'       => 'postMessage',
        );

        $content_desktop_spaces = array(
            'name'            => 'main_advanced_sticky_header_elements_section'.$number.'_content_space_et-desktop',
            'type'            => 'slider',
            'settings'        => 'main_advanced_sticky_header_elements_section'.$number.'_content_space_et-desktop',
            'label'           => $strings['label']['elements_spacing'],
            'tooltip'     => $strings['description']['elements_spacing'],
            'section'         => 'advanced-sticky-header-elements',
            'default'         => 5,
            'choices'         => array(
                'min'  => '0',
                'max'  => '50',
                'step' => '1',
            ),
        );

        $content_mobile_args = array(
            'name'            => 'main_advanced_sticky_header_elements_section'.$number.'_content_et-mobile',
            'type'            => 'sortable',
            'label'           => $strings['label']['elements'],
            'tooltip'     => $strings['description']['elements'],
            'section'         => 'advanced-sticky-header-elements',
            'settings'        => 'main_advanced_sticky_header_elements_section'.$number.'_content_et-mobile',
            'default'         => $default_mobile,
            'choices'         => $elements_ready,
            'transport'       => 'postMessage',
        );

        $content_mobile_spaces = array(
            'name'            => 'main_advanced_sticky_header_elements_section'.$number.'_content_space_et-mobile',
            'type'            => 'slider',
            'settings'        => 'main_advanced_sticky_header_elements_section'.$number.'_content_space_et-mobile',
            'label'           => $strings['label']['elements_spacing'],
            'tooltip'     => $strings['description']['elements_spacing'],
            'section'         => 'advanced-sticky-header-elements',
            'default'         => 5,
            'choices'         => array(
                'min'  => '0',
                'max'  => '50',
                'step' => '1',
            ),
        );

        switch ($number) {
            case '1':
                $content_desktop_args['partial_refresh'] = $content_desktop_spaces['partial_refresh'] = array(
                    'main_advanced_sticky_header_elements_section1_content_et-desktop' => $callbacks['desktop']
                );
                $content_mobile_args['partial_refresh'] = $content_mobile_spaces['partial_refresh'] = array(
                    'main_advanced_sticky_header_elements_section1_content_et-mobile' => $callbacks['mobile']
                );
                $args = array_merge($args, array(
                    // content separator
                    'main_advanced_sticky_header_elements_section1_separator'            => $separator_args,

                    // main_advanced_sticky_header_elements_section1_content
                    'main_advanced_sticky_header_elements_section1_content_et-desktop'              => $content_desktop_args,

                    // main_advanced_sticky_header_elements_section1_content
                    'main_advanced_sticky_header_elements_section1_content_et-mobile'              => $content_mobile_args,

                    // main_advanced_sticky_header_elements_section1_content_space
                    'main_advanced_sticky_header_elements_section1_content_space_et-desktop' => $content_desktop_spaces,

                    // main_advanced_sticky_header_elements_section1_content_space
                    'main_advanced_sticky_header_elements_section1_content_space_et-mobile' => $content_mobile_spaces,
                ));
                break;
            case '2':
                $content_desktop_args['partial_refresh'] = $content_desktop_spaces['partial_refresh'] = array(
                    'main_advanced_sticky_header_elements_section2_content_et-desktop' => $callbacks['desktop']
                );
                $content_mobile_args['partial_refresh'] = $content_mobile_spaces['partial_refresh'] = array(
                    'main_advanced_sticky_header_elements_section2_content_et-mobile' => $callbacks['mobile']
                );
                $args = array_merge($args, array(
                    // content separator
                    'main_advanced_sticky_header_elements_section2_separator'            => $separator_args,

                    // main_advanced_sticky_header_elements_section2_content
                    'main_advanced_sticky_header_elements_section2_content_et-desktop'              => $content_desktop_args,

                    // main_advanced_sticky_header_elements_section2_content
                    'main_advanced_sticky_header_elements_section2_content_et-mobile'              => $content_mobile_args,

                    // main_advanced_sticky_header_elements_section2_content_space
                    'main_advanced_sticky_header_elements_section2_content_space_et-desktop' => $content_desktop_spaces,

                    // main_advanced_sticky_header_elements_section_1_content_space
                    'main_advanced_sticky_header_elements_section2_content_space_et-mobile' => $content_mobile_spaces,
                ));
                break;
            case '3':
                $content_desktop_args['partial_refresh'] = $content_desktop_spaces['partial_refresh'] = array(
                    'main_advanced_sticky_header_elements_section3_content_et-desktop' => $callbacks['desktop']
                );
                $content_mobile_args['partial_refresh'] = $content_mobile_spaces['partial_refresh'] = array(
                    'main_advanced_sticky_header_elements_section3_content_et-mobile' => $callbacks['mobile']
                );
                $content_mobile_args['tooltip'] .= '<br/>' . sprintf(esc_html__('Note: These elements don\'t work in case you selected "%s" mobile layout', 'xstore-advanced-sticky-header'), '50% + 50%');
                $args = array_merge($args, array(
                    // content separator
                    'main_advanced_sticky_header_elements_section3_separator'            => $separator_args,

                    // main_advanced_sticky_header_elements_section3_content
                    'main_advanced_sticky_header_elements_section3_content_et-desktop'              => $content_desktop_args,

                    // main_advanced_sticky_header_elements_section3_content
                    'main_advanced_sticky_header_elements_section3_content_et-mobile'              => $content_mobile_args,

                    // main_advanced_sticky_header_elements_section3_content_space
                    'main_advanced_sticky_header_elements_section3_content_space_et-desktop' => $content_desktop_spaces,

                    // main_advanced_sticky_header_elements_section3_content_space
                    'main_advanced_sticky_header_elements_section3_content_space_et-mobile' => $content_mobile_spaces,
                ));
                break;
        }
    }
	
	return array_merge( $fields, $args );
	
} );