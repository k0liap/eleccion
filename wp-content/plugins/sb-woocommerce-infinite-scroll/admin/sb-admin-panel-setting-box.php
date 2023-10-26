<?php
	$setting = $this->get_infinite_scroll_setting();
	if(!isset($setting['lazyload'])) $setting['lazyload'] = 0;
?>
<div class="meta-box-sortables" id="dashboard-widgets">
    <form class="infinite_scroll_setting_form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
    	<div class="postbox-container">
        	 <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><span><?php esc_html_e('Global Settings', 'sb-woocommerce-infinite-scroll'); ?></span></h2>
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: At a Glance</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                </div>
                <div class="inside">
	                <div class="main">
                    	<div class="form-row">
                            <label><?php esc_html_e('Status', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input <?php checked($this->sb_display_field($setting['status']), 1); ?> name="settings[status]" type="checkbox" value="1" /> <?php esc_html_e('Enable', 'sb-woocommerce-infinite-scroll'); ?> / <?php esc_html_e('Disable', 'sb-woocommerce-infinite-scroll'); ?>
                                <p class="description"><?php esc_html_e('Uncheck this box to disabled plugin pagination.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                    	<div class="form-row">
                            <label><?php esc_html_e('Pagination Type', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <select name="settings[pagination_type]">
                                    <?php
                                        $pagination_types = $this->get_pagination_type();
                                        if(isset($pagination_types)) {
                                            foreach($pagination_types as $pagination_type_key => $pagination_type) {
                                                echo '<option '.selected($setting['pagination_type'], $pagination_type_key).' value="'.$pagination_type_key.'">'.$pagination_type.'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                                <p class="description"><?php esc_html_e('Select type of pagination.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                        	<input type="checkbox" id="mobile_pagination_settings" <?php checked($setting['mobile_pagination_settings'], '1'); ?> name="settings[mobile_pagination_settings]" value="1" /> <strong>Enable different pagination type for smaller devices.</strong>
                            <div class="small-device-settings-box" style=" <?php if($setting['mobile_pagination_settings'] == 1) { echo 'display:block;'; } ?>">
                                <div class="clear"></div><br />
                                <label><?php esc_html_e('Pagination Type', 'sb-woocommerce-infinite-scroll'); ?></label>
                                <div class="field-wrapper">
                                    <select name="settings[mobile_pagination_type]">
                                        <?php
                                            $mobile_pagination_type = $this->get_pagination_type();
                                            if(isset($mobile_pagination_type)) {
                                                foreach($mobile_pagination_type as $pagination_type_key => $pagination_type) {
                                                    echo '<option '.selected($setting['mobile_pagination_type'], $pagination_type_key).' value="'.$pagination_type_key.'">'.$pagination_type.'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                    <p class="description"><?php esc_html_e('Select pagination type for small devices.', 'sb-woocommerce-infinite-scroll'); ?></p>
                                </div>
                                <div class="clear"></div>
                                <label><?php esc_html_e('Break Point', 'sb-woocommerce-infinite-scroll'); ?></label>
                                <div class="field-wrapper">
                                    <input type="number" min="0" value="<?php echo $setting['break_point']; ?>" name="settings[break_point]" /> <?php esc_html_e('Pixels', 'sb-woocommerce-infinite-scroll'); ?>
                                    <p class="description"><?php esc_html_e('Pagination type will change for smaller device than break point pixels.', 'sb-woocommerce-infinite-scroll'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Animation', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <select name="settings[animation]" id="animation">
                                    <option <?php selected($setting['animation'], 'none'); ?> value="none">None</option>
                                    <?php
                                        $animations = $this->parent->get_animation_style();
                                        if(isset($animations)) {
                                            foreach($animations as $animation_key => $animation) {
                                                echo '<optgroup label="'.$animation_key.'">';
                                                foreach($animation as $anim_key => $anim)
                                                echo '<option '.selected($setting['animation'], $anim_key).' value="'.$anim_key.'">'.$anim.'</option>';
                                                echo '</optgroup>';
                                            }
                                        }
                                    ?>
                                </select>
                                <img id="animate-img" src="<?php echo $this->parent->plugin_dir_url; ?>assets/img/logo-icon.jpg" alt="SB Themes" />
                                <p class="description"><?php esc_html_e('Animation style after loading products.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Products Per Page', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[products_per_page]" type="number" value="<?php echo $this->sb_display_field($setting['products_per_page']); ?>" min="1" />
                                <p class="description"><strong>(<?php esc_html_e('Optional', 'sb-woocommerce-infinite-scroll'); ?>)</strong> <?php esc_html_e('Set number to initially load products. Leave empty to default.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Placeholder Image', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[woo_placeholder_image]" class="loading_image" type="text" value="<?php echo $this->sb_display_field($setting['woo_placeholder_image']); ?>" /><input type="button" class="button upload_image" value="Upload" />
                                <img width="32" height="32" class="loading_image_preview alignright" src="<?php echo $this->sb_display_field($setting['woo_placeholder_image']); ?>" alt=" " />
                                <span class="alignright">&nbsp; &nbsp;</span>
                                <p class="description"><?php esc_html_e('Default product placeholder thumbnail. Loads when product image is not available.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
             </div>
             
             <div class="postbox">
                <div class="postbox-header">
                     <h2 class="hndle"><span><?php esc_html_e('Messages and Loader Settings', 'sb-woocommerce-infinite-scroll'); ?></span></h2>
                     <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: At a Glance</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                </div>
                <div class="inside">
	                <div class="main">
                    	<div class="form-row">
                            <label><?php esc_html_e('Loading Message', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[loading_message]" type="text" value="<?php echo $this->sb_display_field($setting['loading_message']); ?>" />
                                <p class="description"><?php esc_html_e('Text to display when products are retrieving.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Loading Wrapper Class', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[loading_wrapper_class]" type="text" value="<?php echo $this->sb_display_field($setting['loading_wrapper_class']); ?>" />
                                <p class="description"><?php esc_html_e('Add custom class to customize loading message style.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Finished Message', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[finished_message]" type="text" value="<?php echo $this->sb_display_field($setting['finished_message']); ?>" />
                                <p class="description"><?php esc_html_e('Text to display when no additional products are available.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Loading Image', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[loading_image]" class="loading_image" type="text" value="<?php echo $this->sb_display_field($setting['loading_image']); ?>" /><input type="button" class="button upload_image" value="Upload" />
                                <img width="32" height="32" class="loading_image_preview alignright" src="<?php echo $this->sb_display_field($setting['loading_image']); ?>" alt=" " />
                                <span class="alignright">&nbsp; &nbsp;</span>
                                <p class="description"><?php esc_html_e('Loader image to display when products are retrieving.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Load More Button Text', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[load_more_button_text]" type="text" value="<?php echo $this->sb_display_field($setting['load_more_button_text']); ?>" />
                                <p class="description"><?php esc_html_e('Add Load More Button Text.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Load More Button Class', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[load_more_button_class]" type="text" value="<?php echo $this->sb_display_field($setting['load_more_button_class']); ?>" />
                                <p class="description"><?php esc_html_e('Add custom class to customize button style (Use space for multiple)', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
             </div>
             
        </div>
        <div class="postbox-container" id="sb-postbox-container-right">
        	 <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><span><?php esc_html_e('Selector Settings', 'sb-woocommerce-infinite-scroll'); ?></span></h2>
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: At a Glance</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                 </div>
                <div class="inside">
	                <div class="main">
                    	<div class="form-row">
                            <label><?php esc_html_e('Content Selector', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[content_selector]" type="text" value="<?php echo $this->sb_display_field($setting['content_selector']); ?>" />
                                <p class="description">Div containing your products</p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Item Selector', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[item_selector]" type="text" value="<?php echo $this->sb_display_field($setting['item_selector']); ?>" />
                                <p class="description"><?php esc_html_e('Div containing an individual product.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Navigation Selector', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[navigation_selector]" type="text" value="<?php echo $this->sb_display_field($setting['navigation_selector']); ?>" />
                                <p class="description"><?php esc_html_e('Div containing your products navigation (pagination).', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Next Selector', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[next_selector]" type="text" value="<?php echo $this->sb_display_field($setting['next_selector']); ?>" />
                                <p class="description"><?php esc_html_e('Link to next page of products (Next page link selector).', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
             </div>
             
             <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><span><?php esc_html_e('Lazy Load Settings', 'sb-woocommerce-infinite-scroll'); ?></span></h2>
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: At a Glance</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                </div>
                <div class="inside">
	                <div class="main">
                    	<div class="form-row">
                            <label><?php esc_html_e('Enable Lazy Load', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input <?php checked($this->sb_display_field($setting['lazyload']), 1); ?> name="settings[lazyload]" type="checkbox" value="1" /> Enable / Disable
                                <p class="description"><?php esc_html_e('Check this box to enable lazy load for WooCommerce.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <label><?php esc_html_e('Disable On Mobile Devices', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input <?php checked($this->sb_display_field($setting['lazyload_mobile']), 1); ?> name="settings[lazyload_mobile]" type="checkbox" value="1" /> Yes / No
                                <p class="description"><?php esc_html_e('Check to disable lazy load on mobile devices.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <label><?php esc_html_e('Loader Image', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[lazyload_loading_image]" class="loading_image" type="text" value="<?php echo $this->sb_display_field($setting['lazyload_loading_image']); ?>" /><input type="button" class="button upload_image" value="Upload" />
                                <img width="32" height="32" class="loading_image_preview alignright" src="<?php echo $this->sb_display_field($setting['lazyload_loading_image']); ?>" alt=" " />
                                <span class="alignright">&nbsp; &nbsp;</span>
                                <p class="description"><?php esc_html_e('Loader image for lazy load.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
             </div>
             
             <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><span><?php esc_html_e('Miscellaneous Settings', 'sb-woocommerce-infinite-scroll'); ?></span></h2>
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: At a Glance</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                </div>
                <div class="inside">
	                <div class="main">
                    	<div class="form-row">
                            <label><?php esc_html_e('Buffer Pixels', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[buffer_pixels]" type="number" value="<?php echo $this->sb_display_field($setting['buffer_pixels']); ?>" /> Pixels
                                <p class="description"><?php esc_html_e('Increase this number if you want infinite scroll to fire quicker.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Scroll Top', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input <?php checked($this->sb_display_field($setting['scrolltop']), 1); ?> name="settings[scrolltop]" type="checkbox" value="1" /> Yes / No
                                <p class="description"><?php esc_html_e('Check to scroll top after data loading (only for: Ajax Pagination).', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><?php esc_html_e('Scroll To', 'sb-woocommerce-infinite-scroll'); ?></label>
                            <div class="field-wrapper">
                                <input name="settings[scrollto]" type="text" value="<?php echo $this->sb_display_field($setting['scrollto']); ?>" />
                                <p class="description"><?php esc_html_e('Scroll top destination. Only works if scroll top is enable.', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
             </div>
             
             <div class="postbox advanced-settings closed">
                <div class="postbox-header">
                    <h2 class="hndle"><span><?php esc_html_e('Advanced Settings', 'sb-woocommerce-infinite-scroll'); ?></span></h2>
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: At a Glance</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                </div>
                <div class="inside">
	                <div class="main">
                    	<div class="form-row">
                            <label><strong><?php esc_html_e('On Pagination Start', 'sb-woocommerce-infinite-scroll'); ?></strong></label>
                            <div class="clear"></div>
                            <div class="field-wrapper">
                                <textarea name="settings[onstart]"><?php echo $this->sb_display_field($setting['onstart']); ?></textarea>
                                <p class="description"><?php esc_html_e('Executes on pagination start. (Use Javasctipt/jQuery code to trigger custom event).', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <label><strong><?php esc_html_e('On Pagination End', 'sb-woocommerce-infinite-scroll'); ?></strong></label>
                            <div class="clear"></div>
                            <div class="field-wrapper">
                                <textarea name="settings[onfinish]"><?php echo $this->sb_display_field($setting['onfinish']); ?></textarea>
                                <p class="description"><?php esc_html_e('Executes immediately after pagination completed. (Use Javasctipt/jQuery code to trigger custom event).', 'sb-woocommerce-infinite-scroll'); ?></p>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
             </div>
        </div>
        
        <div class="form-row">
            <div class="field-wrapper">
                <input type="hidden" name="action" value="save_infinite_scroll_settings" />
                <input type="submit" value="<?php esc_html_e('Save Settings', 'sb-woocommerce-infinite-scroll'); ?>" class="button-primary btn-save-settings sb-btn alignleft" /><span class="alignleft">&nbsp;</span>
                <img class="ajax-loader" src="<?php echo $this->parent->plugin_dir_url; ?>assets/img/ajax-loader.gif" alt="Saving..." />
            </div>
        </div>
        <div class="clear"></div>
    </form>
</div>