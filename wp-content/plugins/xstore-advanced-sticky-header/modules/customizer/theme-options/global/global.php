<?php

/**
 * The template created for enqueueing all files for header panel
 *
 * @version 1.0.0
 * @since   1.0.0
 */

$is_customize_preview = is_customize_preview();

$strings = array(
	'label'           => array(
		'elements'               => esc_html__( 'Elements', 'xstore-advanced-sticky-header' ),
		'elements_spacing'       => esc_html__( 'Elements spacing (px)', 'xstore-advanced-sticky-header' ),
		'wide_header'            => esc_html__( 'Full-width header', 'xstore-advanced-sticky-header' ),
		'wcag_color'             => esc_html__( 'WCAG Color', 'xstore-advanced-sticky-header' ),
		'min_height'             => esc_html__( 'Min height (px)', 'xstore-advanced-sticky-header' ),
		'sticky_logo'            => esc_html__( 'Custom sticky logo', 'xstore-advanced-sticky-header' ),
	),
	'separator_label' => array(
		'main_configuration' => esc_html__( 'Main configuration', 'xstore-advanced-sticky-header' ),
		'style'              => esc_html__( 'Style', 'xstore-advanced-sticky-header' ),
		'advanced'           => esc_html__( 'Advanced', 'xstore-advanced-sticky-header' )
	),
	'description'     => array(
		'wcag_color'            => esc_html__( 'Select the text color for your content. Please choose auto color to ensure readability with your selected background-color, or switch to the "Custom Color" tab to select any other color you want.', 'xstore-advanced-sticky-header' ),
		'wcag_bg_color'         => esc_html__( 'WCAG control is designed to be used by webdesigners, web developers or web accessibility professionals to compute the contrast between two colors (background color, text color)', 'xstore-advanced-sticky-header' ) . ' <a href="https://app.contrast-finder.org/?lang=en" rel="nofollow" target="_blank" style="text-decoration: none; color: var(--customizer-dark-color, #222);">' . esc_html__( 'More details', 'xstore-advanced-sticky-header' ) . '</a>',
        'wide_header'           => esc_html__( 'Expand the current header area to the full width of the page.', 'xstore-advanced-sticky-header'),
        'min_height'            => esc_html__( 'This controls the minimum height of the current header area.', 'xstore-advanced-sticky-header' ),
        'elements'               => esc_html__( 'Easily rearrange, enable, and disable elements to create a truly unique, customized, and informative sticky header for your customers.', 'xstore-advanced-sticky-header' ),
        'elements_spacing'       => esc_html__('Using this option, you can set the spacing value for elements shown in current sticky header area.', 'xstore-advanced-sticky-header'),
        'sticky_logo'           => esc_html__( 'By default, the sticky header uses the site logo. Upload an image to set up a different logo for the sticky header.', 'xstore-advanced-sticky-header' ),
	)
);

$sep_style = 'display: flex; justify-content: flex-start; align-items: center; padding: calc(var(--customizer-ui-content-zoom, 1) * 12px) 15px;margin: 0 -15px;text-align: start;font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px);font-weight: 500;line-height: 1;text-transform: uppercase; letter-spacing: 1px;background-color: var(--customizer-white-color, #fff);color: var(--customizer-dark-color, #222);border-top: 1px solid var(--customizer-border-color, #e1e1e1);border-bottom: 1px solid var(--customizer-border-color, #e1e1e1);';

$separators = array(
    'content'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-inline-start: 5px;">' . $strings['separator_label']['main_configuration'] . '</span></div>',
    'style'    => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-inline-start: 5px;">' . $strings['separator_label']['style'] . '</span></div>',
    'advanced' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-star-filled"></span> <span style="padding-inline-start: 5px;">' . $strings['separator_label']['advanced'] . '</span></div>'
);
