<?php
function xstore_advanced_sticky_header_callback($header_area = 'main', $mobile_part = false) {
    global $et_builder_globals, $wp_customize;
    $et_builder_globals['is_customize_preview'] = get_query_var( 'et_is_customize_preview', false );
    $et_builder_globals['in_mobile_menu']       = false;

    $layout = !$mobile_part ? get_theme_mod($header_area.'_advanced_header_sticky_layout_et-desktop', '3_6_3') : get_theme_mod($header_area.'_advanced_header_sticky_layout_et-mobile', '6_6');

    ob_start();
        $specific_spaces_count = 0;
        foreach (array('left', 'center', 'right') as $side) {
            switch ($side) {
                case 'left':
                    $number = 1;
                    break;
                case 'center':
                    $number = 2;
                    break;
                case 'right':
                    $number = 3;
                    break;
            }
            $header_part_element_space = get_theme_mod($header_area.'_advanced_sticky_header_elements_section'.$number.'_content_space_et-'.($mobile_part?'mobile':'desktop'), 5);
            if ( $header_part_element_space != 5) { // if not default value then render styles for this space value
                $specific_spaces_count++;
                xstore_advanced_sticky_header_spaces($header_area, $mobile_part, '.connect-block-xash-'.$side, $header_part_element_space);
            }
        }
    $style = ob_get_clean();
    if ( $specific_spaces_count < 3) {
        ob_start();
        xstore_advanced_sticky_header_spaces($header_area, $mobile_part);
        $style = ob_get_clean().$style;
    }

    if ( $layout == 'custom' )
        $style .= xstore_advanced_sticky_header_custom_layout($header_area, $mobile_part);


    $inline_style = apply_filters('et_connect_block_inline_css', true);

    if ( $et_builder_globals['is_customize_preview'] || $inline_style ) {
        echo '<style>'.$style.'</style>';
    }
    else {
        wp_add_inline_style( 'xstore-inline-css', $style );
    }

    $mobile_filters = array(
        'etheme_mini_cart_content_type'         => 'etheme_mini_cart_content_mobile',
        'etheme_mini_account_content_type'      => 'etheme_mini_account_content_mobile',
        'etheme_mini_wishlist_content_type'     => 'etheme_mini_wishlist_content_mobile',
        'etheme_mini_compare_content_type'     => 'etheme_mini_compare_content_mobile',

//            'search_mode' => 'etheme_search_mode_mobile',
        'search_icon'                           => 'etheme_search_icon_mobile',
        'search_icon_custom'                    => 'etheme_search_icon_custom_mobile',
        'search_large_loader'                   => 'etheme_return_false',
        'search_type'                           => 'etheme_mobile_search_type',
        'search_category'                       => 'etheme_return_false',
        'account_icon'                          => 'etheme_mobile_account_icon',
        'account_icon_custom'                   => 'etheme_mobile_account_icon_custom'
    );

    if ( $et_builder_globals['is_customize_preview'] ) {
        add_filter( 'is_customize_preview', 'etheme_return_true' );
    }

    if ( $mobile_part ) {
        foreach ( $mobile_filters as $key => $value ) {
            add_filter( $key, $value, 15 );
        }
    }

    $custom_logo = xstore_advanced_sticky_header_return_logo();
    if ( $custom_logo != '') {
        add_filter('logo_img', 'xstore_advanced_sticky_header_return_logo');
        add_filter('theme_mod_headers_sticky_logo_img_et-desktop', 'xstore_advanced_sticky_header_return_logo');
    }

    $side_content_filters = array(
        'etheme_mini_cart_content_position',
        'etheme_mini_account_content_position',
        'etheme_mini_wishlist_content_position',
        'etheme_mini_compare_content_position',
        'etheme_mobile_menu_content_position'
    );

    $mobile_part_static_col_elements = array(
        'main_menu',
        'secondary_menu',
        'mobile_menu',
        'search',
        'connect_block'
    );
    $desktop_part_static_col_elements = array(
        'main_menu',
        'secondary_menu',
        'mobile_menu',
        'connect_block'
    );

    $left_elements = xstore_advanced_sticky_header_callback_part('left', $header_area, $mobile_part);
    $col_class = array();
    switch ($layout) {
        case '3_6_3':
            $col_class[] = 'et_col-xs-3';
        break;
        case '6_6':
            $col_class[] = 'et_col-xs-6';
            break;
        case '2_7_3':
        case '2_8_2':
            $col_class[] = 'et_col-xs-2';
            break;
        case '4_4_4':
            $col_class[] = 'et_col-xs-4';
            break;
        default:
            $col_class[] = 'et_col-custom-left';
            break;
    }
    $col_class[] = 'et_col-xs-offset-0';
    if ( $mobile_part && ( count(array_intersect($left_elements, $mobile_part_static_col_elements) ) ) ) {
        $col_class[] = 'pos-static';
    } elseif ( count(array_intersect($left_elements, $desktop_part_static_col_elements ) ) ) {
        $col_class[] = 'pos-static';
    }
    foreach ($side_content_filters as $side_content_filter) {
        add_filter($side_content_filter, 'xstore_advanced_sticky_header_return_left');
    }
    ?>
    <div class="et_column <?php echo esc_attr( implode( ' ', $col_class ) ); ?>">
        <div class="et_element et_connect-block flex flex-row align-items-center justify-content-start connect-block-xash-left">
            <?php foreach ($left_elements as $element ) {
                require(ET_CORE_DIR . 'app/models/customizer/templates/header/parts/' . $element . '.php');
            } ?>
        </div>
    </div>
    <?php
    foreach ($side_content_filters as $side_content_filter) {
        remove_filter($side_content_filter, 'xstore_advanced_sticky_header_return_left');
    }
    $center_elements = xstore_advanced_sticky_header_callback_part('center', $header_area, $mobile_part);
    $col_class = array();
    switch ($layout) {
        case '3_6_3':
        case '6_6':
            $col_class[] = 'et_col-xs-6';
            break;
        case '2_7_3':
            $col_class[] = 'et_col-xs-7';
            break;
        case '2_8_2':
            $col_class[] = 'et_col-xs-8';
            break;
        case '4_4_4':
            $col_class[] = 'et_col-xs-4';
            break;
        default:
            $col_class[] = 'et_col-custom-center';
            break;
    }
    $col_class[] = 'et_col-xs-offset-0';
    if ( $mobile_part && ( count(array_intersect($center_elements, $mobile_part_static_col_elements) ) ) ) {
        $col_class[] = 'pos-static';
    } elseif ( count(array_intersect($center_elements, $desktop_part_static_col_elements ) ) ) {
        $col_class[] = 'pos-static';
    }
    if ( $mobile_part && $layout == '6_6') {
        foreach ($side_content_filters as $side_content_filter) {
            add_filter($side_content_filter, 'xstore_advanced_sticky_header_return_right');
        }
    }
    ?>
    <div class="et_column <?php echo esc_attr( implode( ' ', $col_class ) ); ?>">
        <div class="et_element et_connect-block flex flex-row align-items-center justify-content-<?php if ($mobile_part && $layout == '6_6') echo 'end'; else echo 'center';?> connect-block-xash-center">
            <?php foreach ($center_elements as $element ) {
                require(ET_CORE_DIR . 'app/models/customizer/templates/header/parts/' . $element . '.php');
            } ?>
        </div>
    </div>
    <?php
    if ( $mobile_part && $layout == '6_6') {
        foreach ($side_content_filters as $side_content_filter) {
            remove_filter($side_content_filter, 'xstore_advanced_sticky_header_return_right');
        }
    }
    if ( !$mobile_part || ($mobile_part && $layout != '6_6')) :
    $right_elements = xstore_advanced_sticky_header_callback_part('right', $header_area, $mobile_part);
    $col_class = array();
    switch ($layout) {
        case '3_6_3':
        case '2_7_3':
            $col_class[] = 'et_col-xs-3';
            break;
        case '2_8_2':
            $col_class[] = 'et_col-xs-2';
            break;
        case '4_4_4':
            $col_class[] = 'et_col-xs-4';
            break;
        default:
            $col_class[] = 'et_col-custom-right';
            break;
    }
    $col_class[] = 'et_col-xs-offset-0';
    if ( $mobile_part && ( count(array_intersect($right_elements, $mobile_part_static_col_elements) ) ) ) {
        $col_class[] = 'pos-static';
    } elseif ( count(array_intersect($right_elements, $desktop_part_static_col_elements ) ) ) {
        $col_class[] = 'pos-static';
    }
    foreach ($side_content_filters as $side_content_filter) {
        add_filter($side_content_filter, 'xstore_advanced_sticky_header_return_right');
    }
    ?>
    <div class="et_column <?php echo esc_attr( implode( ' ', $col_class ) ); ?>">
        <div class="et_element et_connect-block flex flex-row align-items-center justify-content-end connect-block-xash-right">
            <?php foreach ($right_elements as $element ) {
                require(ET_CORE_DIR . 'app/models/customizer/templates/header/parts/' . $element . '.php');
            } ?>
        </div>
    </div>
    <?php
    foreach ($side_content_filters as $side_content_filter) {
        remove_filter($side_content_filter, 'xstore_advanced_sticky_header_return_right');
    }
    endif;
    if ( $mobile_part ) {
        foreach ( $mobile_filters as $key => $value ) {
            remove_filter( $key, $value, 15 );
        }
    }

    if ( $custom_logo != '') {
        remove_filter('logo_img', 'xstore_advanced_sticky_header_return_logo');
        remove_filter('theme_mod_headers_sticky_logo_img_et-desktop', 'xstore_advanced_sticky_header_return_logo');
    }

    if ( $et_builder_globals['is_customize_preview'] ) {
        remove_filter( 'is_customize_preview', 'etheme_return_true' );
    }
}

function xstore_advanced_sticky_header_callback_part($side, $header_area = 'main', $mobile_part = false) {
    switch ($side) {
        case 'left':
            $part_data = ( $mobile_part ) ? get_theme_mod($header_area.'_advanced_sticky_header_elements_section1_content_et-mobile', array('logo')) : get_theme_mod($header_area.'_advanced_sticky_header_elements_section1_content_et-desktop', array('logo'));
            break;
        case 'center':
            $is_woocommerce = class_exists('WooCommerce');
            $part_data = ( $mobile_part ) ? get_theme_mod($header_area.'_advanced_sticky_header_elements_section2_content_et-mobile', ($is_woocommerce ? array('account', 'wishlist', 'cart', 'mobile_menu') : array('mobile_menu'))) : get_theme_mod($header_area.'_advanced_sticky_header_elements_section2_content_et-desktop', array('main_menu'));
            break;
        case 'right':
            $is_woocommerce = class_exists('WooCommerce');
            $part_data = ( $mobile_part ) ? get_theme_mod($header_area.'_advanced_sticky_header_elements_section3_content_et-mobile', array()) : get_theme_mod($header_area.'_advanced_sticky_header_elements_section3_content_et-desktop', ($is_woocommerce ? array('account', 'wishlist', 'cart') : array('account')));
            break;
    }
    if ( ! is_array( $part_data ) ) {
        $part_data = array();
    }

    return $part_data;

}

function xstore_advanced_sticky_header_spaces($header_area = 'main', $mobile_part = false, $connect_block_class = '.et_connect-block', $space = 5) {
    $prefix = '.sticky-'.($mobile_part ? 'mobile-' : '').'header-wrapper .sticky-'.($mobile_part ? 'mobile-' : '').'header-'.$header_area.'-wrapper';
    ?>
    <?php echo esc_attr($prefix); ?> <?php echo esc_attr($connect_block_class); ?> {
    --connect-block-space: <?php echo $space.'px'; ?>;
    margin: <?php echo '0 ' . '-'.$space.'px'; ?>;
    }
    <?php echo esc_attr($prefix); ?> .et_element<?php echo esc_attr($connect_block_class); ?> > div,
    <?php echo esc_attr($prefix); ?> .et_element<?php echo esc_attr($connect_block_class); ?> > form.cart,
    <?php echo esc_attr($prefix); ?> .et_element<?php echo esc_attr($connect_block_class); ?> > .price {
    margin: <?php echo '0 ' . $space.'px'; ?>;
    }
    <?php echo esc_attr($prefix); ?> .et_element<?php echo esc_attr($connect_block_class); ?> > .et_b_header-widget > div,
    <?php echo esc_attr($prefix); ?> .et_element<?php echo esc_attr($connect_block_class); ?> > .et_b_header-widget > ul {
    margin-left: <?php echo $space.'px'; ?>;
    margin-right: <?php echo $space.'px'; ?>;
    }
    <?php echo esc_attr($prefix); ?> .et_element<?php echo esc_attr($connect_block_class); ?> .widget_nav_menu .menu > li > a {
    margin: <?php echo '0 ' . $space.'px'; ?>
    }
    /*<?php echo esc_attr($prefix); ?> .et_element<?php echo esc_attr($connect_block_class); ?> .widget_nav_menu .menu .menu-item-has-children > a:after {
    right: <?php echo $space.'px'; ?>;
    }*/
    <?php
}

function xstore_advanced_sticky_header_custom_layout($header_area = 'main', $mobile_part = false) {
    $styles = '';
    foreach (array('left', 'center', 'right') as $side) {
        $prefix = '.sticky-'.($mobile_part ? 'mobile-' : '').'header-wrapper .sticky-'.($mobile_part ? 'mobile-' : '').'header-'.$header_area.'-wrapper .et_col-custom-'.$side;
        switch ($side) {
            case 'left':
                $width = get_theme_mod($header_area.'_advanced_header_sticky_layout_custom_section1_et-'.($mobile_part?'mobile':'desktop'), 25);
                break;
            case 'center':
                $width = get_theme_mod($header_area.'_advanced_header_sticky_layout_custom_section2_et-'.($mobile_part?'mobile':'desktop'), 50);
                break;
            case 'right':
                $width = get_theme_mod($header_area.'_advanced_header_sticky_layout_custom_section3_et-'.($mobile_part?'mobile':'desktop'), 25);
                break;
        }
        $styles .= esc_attr($prefix) . ' { ';
            $styles .= 'width: '.$width.'%;';
            if ( $width == 0) {
                $styles .= 'display: none';
            }
        $styles .= '}';
    }
    return $styles;
}