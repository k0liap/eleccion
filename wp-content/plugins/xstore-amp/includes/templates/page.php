<?php
/**
 * Home page template
 *
 * @package    page.php
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

global $xstore_amp_settings;

$options = array();

$options['elements'] = array(
	'slider',
	'products_categories_01',
	'products_01',
	'banner_01',
	'posts_01',
	'textarea_block_01'
);
$options['callbacks'] = array(
	'slider' => array($this, 'home_slider'),
);
$options['callbacks_args'] = array();

if ( isset($xstore_amp_settings['home_page']['page_elements']) && !empty($xstore_amp_settings['home_page']['page_elements']) ) {
	$options['elements'] = explode(',', $xstore_amp_settings['home_page']['page_elements']);
	foreach ( $options['elements'] as $element_key => $element_name ) {
		if ( !isset($xstore_amp_settings['home_page'][$element_name.'_visibility']) || !$xstore_amp_settings['home_page'][$element_name.'_visibility'] ) {
			unset($options['elements'][$element_key]);
		}
	}
}
// products
foreach (array('products_01', 'products_02', 'products_03', 'products_04') as $products) {
    $options['callbacks'][$products] = array($this, 'get_products');
    $options['callbacks_args'][$products] = array(
        'type' => 'random',
        'args' => array(),
        'carousel_args' => array()
    );
    if ( in_array($products, $options['elements']) ) {
        if ( isset( $xstore_amp_settings['home_page'][$products.'_type'] ) ) {
            $options['callbacks_args'][$products]['type'] = $xstore_amp_settings['home_page'][$products.'_type'];
        }
        if ( isset( $xstore_amp_settings['home_page'][$products.'_limit'] ) ) {
            $options['callbacks_args'][$products]['args']['limit']          = $xstore_amp_settings['home_page'][$products.'_limit'];
            $options['callbacks_args'][$products]['args']['posts_per_page'] = $xstore_amp_settings['home_page'][$products.'_limit'];
        }
        if ( isset( $xstore_amp_settings['home_page'][$products.'_order'] ) ) {
            $options['callbacks_args'][$products]['args']['order'] = $xstore_amp_settings['home_page'][$products.'_order'];
        }
        if ( isset( $xstore_amp_settings['home_page'][$products.'_title'] ) ) {
            $options['callbacks_args'][$products]['carousel_args']['title'] = $xstore_amp_settings['home_page'][$products.'_title'];
        }
    }
}

// product categories
foreach (array('products_categories_01', 'products_categories_02') as $products_categories) {
    $options['callbacks'][$products_categories] = array($this, 'get_products_categories');
    $options['callbacks_args'][$products_categories] = array(
        'args' => array(),
        'carousel_args' => array()
    );
    if (in_array($products_categories, $options['elements'])) {
        if (isset($xstore_amp_settings['home_page'][$products_categories . '_title'])) {
            $options['callbacks_args'][$products_categories]['carousel_args']['title'] = $xstore_amp_settings['home_page'][$products_categories . '_title'];
        }
        if (isset($xstore_amp_settings['home_page'][$products_categories . '_limit'])) {
            $options['callbacks_args'][$products_categories]['args']['number'] = $xstore_amp_settings['home_page'][$products_categories . '_limit'];
        }
        if (isset($xstore_amp_settings['home_page'][$products_categories . '_order'])) {
            $options['callbacks_args'][$products_categories]['args']['order'] = $xstore_amp_settings['home_page'][$products_categories . '_order'];
        }
    }
}

// posts
foreach (array('posts_01', 'posts_02') as $posts) {
    $options['callbacks'][$posts] = array($this, 'get_posts');
    $options['callbacks_args'][$posts] = array(
        'type' => 'random',
        'args' => array()
    );
    if (in_array($posts, $options['elements'])) {
        if (isset($xstore_amp_settings['home_page'][$posts.'_title'])) {
            $options['callbacks_args'][$posts]['carousel_args']['title'] = $xstore_amp_settings['home_page'][$posts.'_title'];
        }
        if (isset($xstore_amp_settings['home_page'][$posts.'_type'])) {
            $options['callbacks_args'][$posts]['type'] = $xstore_amp_settings['home_page'][$posts.'_type'];
        }
        if (isset($xstore_amp_settings['home_page'][$posts.'_limit'])) {
            $options['callbacks_args'][$posts]['args']['limit'] = $xstore_amp_settings['home_page'][$posts.'_limit'];
            $options['callbacks_args'][$posts]['args']['posts_per_page'] = $xstore_amp_settings['home_page'][$posts.'_limit'];
        }
        if (isset($xstore_amp_settings['home_page'][$posts.'_order'])) {
            $options['callbacks_args'][$posts]['args']['order'] = $xstore_amp_settings['home_page'][$posts.'_order'];
        }
    }
}

// banner
foreach (array('banner_01', 'banner_02', 'banner_03') as $banner) {
    $options['callbacks'][$banner] = array($this, 'banner');
    $options['callbacks_args'][$banner] = array(
        'args' => array()
    );
    if (in_array($banner, $options['elements'])) {
        if (isset($xstore_amp_settings['home_page'][$banner.'_image'])) {
            $options['callbacks_args'][$banner]['args']['image'] = $xstore_amp_settings['home_page'][$banner.'_image'];
        }
        if (isset($xstore_amp_settings['home_page'][$banner.'_title'])) {
            $options['callbacks_args'][$banner]['args']['title'] = $xstore_amp_settings['home_page'][$banner.'_title'];
        }
        if (isset($xstore_amp_settings['home_page'][$banner.'_content'])) {
            $options['callbacks_args'][$banner]['args']['content'] = $xstore_amp_settings['home_page'][$banner.'_content'];
        }
        if (isset($xstore_amp_settings['home_page'][$banner.'_button_text'])) {
            $options['callbacks_args'][$banner]['args']['button_text'] = $xstore_amp_settings['home_page'][$banner.'_button_text'];
        }
        if (isset($xstore_amp_settings['home_page'][$banner.'_button_url'])) {
            $options['callbacks_args'][$banner]['args']['button_url'] = $xstore_amp_settings['home_page'][$banner.'_button_url'];
        }
        if (isset($xstore_amp_settings['home_page'][$banner.'_height'])) {
            $options['callbacks_args'][$banner]['args']['height'] = $xstore_amp_settings['home_page'][$banner.'_height'];
        }
    }
}

// textarea
foreach (array('textarea_block_01', 'textarea_block_02') as $textarea_block) {
    $options['callbacks'][$textarea_block] = array($this, 'textarea_block');
    $options['callbacks_args'][$textarea_block] = array(
        'args' => array()
    );
    if (in_array($textarea_block, $options['elements'])) {
        if (isset($xstore_amp_settings['home_page'][$textarea_block.'_title'])) {
            $options['callbacks_args'][$textarea_block]['args']['title'] = $xstore_amp_settings['home_page'][$textarea_block.'_title'];
        }
        if (isset($xstore_amp_settings['home_page'][$textarea_block.'_content'])) {
            $options['callbacks_args'][$textarea_block]['args']['content'] = $xstore_amp_settings['home_page'][$textarea_block.'_content'];
        }
    }
}

$i=0;

global $xstore_amp_el_settings;
foreach ($options['elements'] as $element) {
	$i++;
	if ( count($options['elements']) > $i) {
		$xstore_amp_el_settings['space'] = '10vw';
	}
	else {
		unset($xstore_amp_el_settings['space']);
	}
    if ( isset( $options['callbacks_args'][ $element ] ) ) {
        call_user_func_array( $options['callbacks'][ $element ], $options['callbacks_args'][ $element ] );
    } else {
        call_user_func( $options['callbacks'][ $element ] );
    }
}

unset($options);