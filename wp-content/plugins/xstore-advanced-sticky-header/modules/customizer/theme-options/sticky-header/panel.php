<?php
/**
 * The template created for displaying single product page panel
 *
 * @version 1.0.0
 * @since   1.0.0
 */

add_filter( 'et/customizer/add/panels', function ( $panels ) {

    $args = array(
        'advanced-sticky-header' => array(
            'id'    => 'advanced-sticky-header',
            'title'      => esc_html__( 'Advanced Sticky header', 'xstore-advanced-sticky-header' ),
            'panel'      => 'header-builder',
            'icon'  => 'dashicons-paperclip',
        )
    );

    return array_merge( $panels, $args );
} );