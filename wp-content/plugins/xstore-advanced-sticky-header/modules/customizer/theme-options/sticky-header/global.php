<?php
/**
 * The template created for enqueueing all files for built-in wishlist options
 *
 * @version 1.0.0
 * @since   1.0.0
 */

$elements = array(
    'panel',
	'general',
	'elements',
);

foreach ( $elements as $key ) {
	require_once( XStore_Advanced_Sticky_Header_DIR . 'modules/customizer/theme-options/sticky-header/' . $key . '.php' );
}