<?php

/**
 * Factory Processing
 *
 * Use https://github.com/deliciousbrains/wp-background-processing
 *
 * @author        Artem Prihodko <webtemyk@yandex.ru>
 * @since         1.0.0
 *
 * @package       factory-processing
 * @copyright (c) 2021, Webcraftic Ltd
 *
 * @version       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'FACTORY_PROCESSING_107_LOADED' ) || ( defined( 'FACTORY_PROCESSING_STOP' ) && FACTORY_PROCESSING_STOP ) ) {
	return;
}

define( 'FACTORY_PROCESSING_107_LOADED', true );
define( 'FACTORY_PROCESSING_107_VERSION', '1.0.7' );
define( 'FACTORY_PROCESSING_107_DIR', dirname( __FILE__ ) );
define( 'FACTORY_PROCESSING_107_URL', plugins_url( null, __FILE__ ) );

//load_plugin_textdomain( 'wbcr_factory_processing_107', false, dirname( plugin_basename( __FILE__ ) ) . '/langs' );

require_once( FACTORY_PROCESSING_107_DIR . '/includes/classes/wp-async-request.php' );
require_once( FACTORY_PROCESSING_107_DIR . '/includes/classes/wp-background-process.php' );


/**
 * @param Wbcr_Factory469_Plugin $plugin
 */
add_action( 'wbcr_factory_processing_107_plugin_created', function ( $plugin ) {
	/* @var Wbcr_Factory469_Plugin $plugin */

	/* Settings of Processing
	$settings = [
		'dir' => null,
		'file' => 'app.log',
		'flush_interval' => 1000,
		'rotate_size' => 5000000,
		'rotate_limit' => 3,
	];

	$plugin->set_logger( "WBCR\Factory_Processing_107\Processing", $settings );
	*/
} );
