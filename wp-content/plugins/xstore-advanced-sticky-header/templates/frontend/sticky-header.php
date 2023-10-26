<?php
    global $et_builder_globals;
    $et_builder_globals['in_mobile_menu']       = false;
    $et_builder_globals['is_customize_preview'] = get_query_var( 'et_is_customize_preview', false );
    $is_mobile                                  = get_query_var( 'is_mobile', false );
    $sticky_attr = array();
    $sticky_type = ( get_theme_mod( 'advanced_header_sticky_type_et-desktop', 'custom' ) );
    $sticky_attr[] = 'data-type="' . $sticky_type . '"';
    if ( $sticky_type == 'custom' ) {
        $sticky_attr[] = 'data-start="' . get_theme_mod( 'advanced_header_sticky_general_start_et-desktop', 80 ) . '"';
    }
?>
<div class="site-header sticky-site-header sticky" <?php echo implode(' ', $sticky_attr); ?>>
    <?php if ( (get_query_var('et_mobile-optimization', false) && !get_query_var('is_mobile', false)) || !get_query_var('et_mobile-optimization', false) ) : ?>
    <div class="sticky-header-wrapper">
        <div class="sticky-header-main-wrapper sticky">
            <div class="sticky-header-main" data-title="<?php esc_html_e( 'Advanced Sticky header', 'xstore-advanced-sticky-header' ); ?>">
                <div class="et-row-container<?php echo !(get_theme_mod( 'main_advanced_advanced_header_sticky_wide_et-desktop', '0' )) ? ' et-container' : ''; ?>">
                    <div class="et-wrap-columns flex align-items-center"><?php xstore_advanced_sticky_header_callback('main'); ?></div><?php // to prevent empty spaces in DOM content ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif;
    if ( (get_query_var('et_mobile-optimization', false) && get_query_var('is_mobile', false)) || !get_query_var('et_mobile-optimization', false) ) : ?>
    <div class="sticky-mobile-header-wrapper">
        <div class="sticky-mobile-header-main-wrapper sticky">
            <div class="sticky-mobile-header-main" data-title="<?php esc_html_e( 'Advanced Sticky header', 'xstore-advanced-sticky-header' ); ?>">
                <div class="et-row-container<?php echo !(get_theme_mod( 'main_advanced_advanced_header_sticky_wide_et-mobile', '0' )) ? ' et-container' : ''; ?>">
                    <div class="et-wrap-columns flex align-items-center"><?php xstore_advanced_sticky_header_callback('main', true); ?></div><?php // to prevent empty spaces in DOM content ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
