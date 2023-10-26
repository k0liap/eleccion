<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Classes\Elementor;

/**
 * Product List widget.
 *
 * @since      4.1.2
 * @version    1.0.1
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Product_List extends \Elementor\Widget_Base {
	
	protected static $query_args;
	protected static $id;
	protected static $page_link;
	protected static $widget_type;
	
	public static $instance = null;
	
	/**
	 * Get widget name.
	 *
	 * @since 4.1.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_product_list';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.1.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Products List', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.1.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-products-list';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.1.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'products', 'categories', 'list', 'woocommerce', 'query' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.1.2
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['eight_theme_general'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.1.2
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		$styles = [ 'etheme-elementor-product-list' ];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
			$styles[] = 'etheme-elementor-countdown';
		return $styles;
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.1.2
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		$scripts = [];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$scripts[] = 'etheme_post_product';
			$scripts[] = 'etheme_countdown';
		}
		return $scripts;
	}
	
	/**
	 * Help link.
	 *
	 * @since 4.1.5
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}
	
	/**
	 * Register Product List controls.
	 *
	 * @since 4.1.2
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'query_type',
			[
				'label' 		=>	__( 'Data Source', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
                'options' 		=>	self::get_data_source_list(),
                'frontend_available' => true,
				'default'	=> 'all'
			]
		);

        if ( defined('ELEMENTOR_PRO_VERSION') ) {
            foreach (array(
                         'related_products' => esc_html__('Related Products', 'xstore-core'),
                         'upsells' => esc_html__('Upsells Products', 'xstore-core'),
                         'cross_sells' => esc_html__('Cross-sells Products', 'xstore-core'),
                         'current_query' => esc_html__( 'Current Query', 'xstore-core' ),
                     ) as $specific_source_key => $specific_source_title) {
                $this->add_control(
                    $specific_source_key . '_note',
                    [
                        'type' => \Elementor\Controls_Manager::RAW_HTML,
                        'raw' => ( $specific_source_key == 'current_query' ?
                            sprintf(esc_html__('Note: The %s is available when creating a Product Archive template', 'xstore-core'), $specific_source_title) :
                            sprintf(esc_html__('Note: The %s Query is available when creating a Single Product template', 'xstore-core'), $specific_source_title) ),
                        'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                        'condition' => [
                            'query_type' => $specific_source_key,
                        ],
                    ]
                );
            }
        }
		
		$this->add_responsive_control(
			'cols',
			[
				'label' => __( 'Columns', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'default' => '2',
				'selectors' => [
					'{{WRAPPER}}' => '--cols: {{VALUE}};',
				],
			]
		);

        $this->add_control(
            'posts_per_page_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(esc_html__('Note: You can set products per page globally by setting value in Theme Options -> WooCommerce -> Shop -> %s', 'xstore-core'),
                    '<a href="'.admin_url( '/customize.php?autofocus[section]=shop-page' ).'" target="_blank">'.esc_html__('Products per page', 'xstore-core').'</a>'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'condition' => [
                    'query_type' => 'current_query',
                ],
            ]
        );
		
		$this->add_control(
			'limit',
			[
				'label'      => esc_html__( 'Products Limit', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 200,
				'step' => 1,
				'default' => 6,
				'condition'  => [
                    'query_type!' => [ 'product_ids', 'current_query' ],
				],
			]
		);
		
		$this->add_control(
			'offset',
			[
				'label' => __( 'Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'description' => __('Number of post to displace or pass over.', 'xstore-core') .
				                 ' <a href="https://developer.wordpress.org/reference/classes/wp_query/#pagination-parameters#:~:text=offset%20(int)%20%E2%80%93%20number%20of%20post%20to%20displace%20or%20pass%20over" rel="nofollow" target="_blank">' .
				                 __('More info', 'xstore-core') .
				                 '</a>',
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'condition'   => [
                    'query_type!' => [ 'product_ids', 'current_query' ],
					'orderby!' => 'rand',
				],
			]
		);
		
		$this->add_control(
			'bordered_layout',
			[
				'label'        => esc_html__( 'Bordered Layout', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'bordered_layout_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--bordered-layout-border-color: {{VALUE}};',
				],
				'condition' => [
					'bordered_layout!' => ''
				]
			]
		);
		
		$this->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'button'		=>	esc_html__('Load More', 'xstore-core'),
					'scroll'	=>	esc_html__('Infinite Scroll', 'xstore-core'),
					'pagination'		=>	esc_html__('Pagination', 'xstore-core'),
					'none'		=>	esc_html__('None', 'xstore-core'),
				],
				'frontend_available' => true,
				'default' => 'none',
			]
		);
		
		$this->add_control(
			'navigation_pagination_ajax',
			[
				'label'        => esc_html__( 'Ajax Pagination', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'navigation' => 'pagination',
				],
				'frontend_available' => true,
			]
		);

        $this->add_control(
            'navigation_button_type',
            [
                'label' 		=>	__( 'Button Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'classic'		=>	esc_html__('Classic', 'xstore-core'),
                    'advanced'	=>	esc_html__('With Count Bar', 'xstore-core'),
                ],
                'condition' => [
                    'navigation' => 'button',
                ],
                'frontend_available' => true,
                'default' => 'classic',
            ]
        );
		
		$this->add_control(
			'navigation_button_text',
			[
				'label' 		=>	__( 'Button Text', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __('Load More', 'xstore-core'),
				'condition' => [
					'navigation' => 'button',
				],
			]
		);

        $this->add_control(
            'animation_type',
            [
                'label'        => esc_html__( 'Animation Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    ''		=>	esc_html__('Without', 'xstore-core'),
                    'fadeInUp_animation'	=>	esc_html__('Fade In Up', 'xstore-core'),
                    'skeleton_animation'	=>	esc_html__('Skeleton', 'xstore-core'),
                ],
                'conditions' 	=> [
                    'relation' => 'or',
                    'terms' 	=> [
                        [
                            'name' 		=> 'navigation',
                            'operator'  => 'in',
                            'value' 	=> ['button', 'scroll']
                        ],
                        [
                            'relation' => 'and',
                            'terms' 	=> [
                                [
                                    'name' 		=> 'navigation',
                                    'operator'  => '=',
                                    'value' 	=> 'pagination'
                                ],
                                [
                                    'name' 		=> 'navigation_pagination_ajax',
                                    'operator'  => '!=',
                                    'value' 	=> ''
                                ],
                            ]
                        ]
                    ]
                ],
                'default' => '',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'navigation_none_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(esc_html__('Note: We don\'t recommend you to deactivate all navigations for products with %s data source type as it will limit your products on archive pages.', 'xstore-core'), esc_html__('Current query', 'xstore-core')),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'condition' => [
                    'navigation' => 'none',
                    'query_type' => 'current_query',
                ],
            ]
        );

        $this->add_control(
            'navigation_any_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(esc_html__('Note: Navigation works on real frontend only.', 'xstore-core'), esc_html__('Current query', 'xstore-core')),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'navigation!' => 'none',
                    'query_type' => 'current_query',
                ],
            ]
        );
		
		$this->add_control(
			'posts_per_page',
			[
				'label'      => esc_html__( 'Load Items Per Iteration', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 200,
				'step' => 1,
				'default' => 4,
				'frontend_available' => true,
				'condition'  => [
					'navigation!' => 'none',
                    'query_type!' => 'current_query'
				],
			]
		);

		$this->add_control(
			'navigation_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
                'condition' => [
                    'query_type!' => [ 'current_query' ],
                ],
			]
		);
		
		$this->add_control(
			'include_products',
			[
				'label'       => esc_html__( 'Include Only', 'xstore-core' ),
				'description' => esc_html__( 'Add products by title.', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> true,
                'dynamic' => [
                    'active' => true,
                ],
				'placeholder' 	=> esc_html__('Enter List of Products', 'xstore-core'),
				'data_options' 	=> [
					'post_type' => array( 'product_variation', 'product' ),
				],
				'condition'   => [
					'query_type' => 'product_ids',
				],
			]
		);
		
		$this->add_control(
			'orderby',
			[
				'label'     => esc_html__( 'Order By', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'  => esc_html__( 'Date', 'xstore-core' ),
					'rand'  => esc_html__( 'Random Order', 'xstore-core' ),
					'price' => esc_html__( 'Price', 'xstore-core' ),
					'menu_order' => esc_html__( 'Menu Order', 'xstore-core' ),
					'sales' => esc_html__( 'Sales', 'xstore-core' ),
				),
				'condition' => [
                    'query_type!' => [ 'product_ids', 'current_query' ],
				],
			]
		);
		
		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Sort Order', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'ASC',
				'options'   => array(
					'DESC' => esc_html__( 'Descending', 'xstore-core' ),
					'ASC'  => esc_html__( 'Ascending', 'xstore-core' ),
				),
				'condition' => [
                    'query_type!' => [ 'product_ids', 'current_query' ],
				],
			]
		);

        $this->add_control(
            'select_date',
            [
                'label' => __( 'Date', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'anytime' => __( 'All', 'xstore-core' ),
                    'today' => __( 'Past Day', 'xstore-core' ),
                    'week' => __( 'Past Week', 'xstore-core' ),
                    'month'  => __( 'Past Month', 'xstore-core' ),
                    'quarter' => __( 'Past Quarter', 'xstore-core' ),
                    'year' => __( 'Past Year', 'xstore-core' ),
                    'exact' => __( 'Custom', 'xstore-core' ),
                ],
                'default' => 'anytime',
                'condition' => [
                    'query_type!' => [ 'product_ids', 'current_query' ],
                ],
            ]
        );

        $this->add_control(
            'date_before',
            [
                'label' => __( 'Before', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'label_block' => false,
                'multiple' => false,
                'placeholder' => __( 'Choose', 'xstore-core' ),
                'condition' => [
                    'select_date' => 'exact',
                    'query_type!' => [ 'product_ids', 'current_query' ],
                ],
                'description' => __( 'Setting a ‘Before’ date will show all the posts published until the chosen date (inclusive).', 'xstore-core' ),
            ]);

        $this->add_control(
            'date_after',
            [
                'label' => __( 'After', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'label_block' => false,
                'placeholder' => __( 'Choose', 'xstore-core' ),
                'condition' => [
                    'select_date' => 'exact',
                    'query_type!' => [ 'product_ids', 'current_query' ],
                ],
                'description' => __( 'Setting an ‘After’ date will show all the posts published since the chosen date (inclusive).', 'xstore-core' ),
            ]);

        $product_taxonomies = self::product_taxonomies_to_filter();
		
		$this->add_control(
			'taxonomy_type',
			[
				'label' 		=>	__( 'Taxonomy Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	$product_taxonomies,
				'default'		=> array_key_exists('product_cat', $product_taxonomies) ? 'product_cat' : array_key_first($product_taxonomies),
				'condition'   => [
                    'query_type!' => [ 'product_ids', 'current_query' ],
				],
                'separator' => 'before',
			]
		);
		
		foreach ($product_taxonomies as $product_taxonomy_key => $product_taxonomy_label) {
			$this->add_control(
				$product_taxonomy_key == 'product_cat' ? 'ids' : $product_taxonomy_key.'s', // make is multiple
				[
					'label' 		=>	$product_taxonomy_label,
					'type' 			=>	\Elementor\Controls_Manager::SELECT2,
//					'description'   =>  esc_html__( 'Enter categories.', 'xstore-core' ),
					'label_block'	=> 'true',
					'multiple' 	=>	true,
					'options' 		=> Elementor::get_terms($product_taxonomy_key, false, false),
					'condition'   => [
                        'query_type!' => [ 'product_ids', 'current_query' ],
						'taxonomy_type' => $product_taxonomy_key
					],
				]
			);
		}
		
		$this->add_control(
			'hide_free',
			[
				'label'        => esc_html__( 'Hide Free Products', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => [
                    'query_type!' => [ 'current_query' ],
                ],
			]
		);
		
		$this->add_control(
			'hide_out_of_stock',
			[
				'label'        => esc_html__( 'Hide Out Of Stock', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'query_type!' => [ 'current_query' ],
                ],
			]
		);

        $this->add_control(
            'hide_sale',
            [
                'label'        => esc_html__( 'Hide Sale Products', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'condition'   => [
                    'query_type!' => ['product_ids', 'current_query', 'onsale'],
                ],
            ]
        );
		
		$this->add_control(
			'show_hidden',
			[
				'label'        => esc_html__( 'Show Hidden Products', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'query_type!' => [ 'current_query' ],
                ],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_product_settings',
			[
				'label' => esc_html__( 'Product', 'xstore-core' ),
			]
		);
		
		$product_elements = self::get_product_elements();
		
		foreach ($product_elements as $key => $value) {
			
			if ( $key == 'countdown' ) continue; // moved in separate tab
   
			$this->add_control(
				'product_'.$key,
				[
					'label'        => $value,
					'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'default' => in_array($key, array('image', 'title', 'rating', 'price', 'categories')) ? 'yes' : ''
				]
			);
			
			// injection of some options for specific keys
			switch ($key) {
                case 'image':
                    // make as filter for image
                    $this->add_group_control(
                        \Elementor\Group_Control_Image_Size::get_type(),
                        [
                            'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                            'default' => 'woocommerce_thumbnail',
                            'separator' => 'none',
                            'condition' => [
                                'product_image!' => ''
                            ]
                        ]
                    );
	
	                $this->add_control(
		                'product_sale_label',
		                [
			                'label'        => esc_html__('Show Sale Label', 'xstore-core'),
			                'type'         => \Elementor\Controls_Manager::SWITCHER,
			                'condition' => [
				                'product_image!' => ''
			                ]
		                ]
	                );

                    if ( apply_filters('etheme_product_grid_list_product_new_label', false) ) {
                        $this->add_control(
                            'product_new_label',
                            [
                                'label' => esc_html__('Show New Label', 'xstore-core'),
                                'description' => sprintf(
                                    esc_html__('New label will be shown according to your %s.', 'xstore-core'),
                                    '<a href="' . admin_url('/customize.php?autofocus[section]=shop-icons') . '" target="_blank">' . esc_html__('Global settings', 'xstore-core') . '</a>'),
                                'type' => \Elementor\Controls_Manager::SWITCHER,
                                'condition' => [
                                    'product_image!' => ''
                                ]
                            ]
                        );
                    }
    
                    $this->add_control(
                        'img_size_custom',
                        [
                            'label'       => esc_html__( 'Image Dimension', 'xstore-core' ),
                            'type'        => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
                            'description' => esc_html__( 'You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.', 'xstore-core' ),
                            'condition'   => [
                                'product_image!' => '',
                                'images_size' => 'custom',
                            ],
                        ]
                    );
    
                    $this->add_control(
                        'img_size_divider',
                        [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                            'condition' => [
                                'product_image!' => ''
                            ]
                        ]
                    );
                    break;
                case 'title':
                case 'excerpt':
                    if ( $key == 'title' ) {
                        $this->add_control(
                            'product_'.$key.'_tag',
                            [
                                'label'       => esc_html__( 'HTML tag', 'xstore-core' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => [
                                    'h1' => 'H1',
                                    'h2' => 'H2',
                                    'h3' => 'H3',
                                    'h4' => 'H4',
                                    'h5' => 'H5',
                                    'h6' => 'H6',
                                    'div' => 'div',
                                    'span' => 'span',
                                    'p' => 'p',
                                ],
                                'default' => 'h2',
                                'condition' => [
                                    'product_'.$key.'!' => ''
                                ]
                            ]
                        );
                    }
	                $this->add_control(
		                'product_'.$key.'_limit_type',
		                [
			                'label'       => esc_html__( 'Limit By', 'xstore-core' ),
			                'type' => \Elementor\Controls_Manager::SELECT,
			                'options' => [
				                'chars' => esc_html__('Chars', 'xstore-core'),
				                'words' => esc_html__('Words', 'xstore-core'),
                                'lines' => esc_html__('Lines', 'xstore-core'),
                                'none' => esc_html__('None', 'xstore-core'),
			                ],
			                'default' => 'none',
			                'condition' => [
				                'product_'.$key.'!' => ''
			                ]
		                ]
	                );
	
	                $this->add_control(
		                'product_'.$key.'_limit',
		                [
			                'label'      => esc_html__( 'Limit', 'xstore-core' ),
			                'type'       => \Elementor\Controls_Manager::NUMBER,
			                'min' => 0,
			                'max' => 200,
			                'step' => 1,
			                'condition' => [
				                'product_'.$key.'!' => '',
                                'product_'.$key.'_limit_type' => ['chars', 'words']
			                ]
		                ]
	                );
	                
	                $selector = '{{WRAPPER}} .etheme-product-list-title a';
	                if ( $key == 'excerpt' )
		                $selector = '{{WRAPPER}} .woocommerce-product-details__short-description';
	
	                $this->add_control(
		                'product_'.$key.'_lines_limit',
		                [
			                'label'      => esc_html__( 'Lines Limit', 'xstore-core' ),
			                'description' => esc_html__( 'Line-height will not work with this option. Don\'t set it up in typography settings.', 'xstore-core' ),
			                'type'       => \Elementor\Controls_Manager::NUMBER,
			                'min' => 1,
			                'max' => 20,
			                'step' => 1,
			                'default' => 2,
			                'condition' => [
				                'product_'.$key.'!' => '',
				                'product_'.$key.'_limit_type' => 'lines'
			                ],
                            'selectors' => [
				                '{{WRAPPER}}' => '--product-'.$key.'-lines: {{VALUE}};',
				                $selector => 'display: block; height: calc(var(--product-'.$key.'-lines) * 3ex); line-height: 3ex; overflow: hidden;',
			                ],
		                ]
	                );
	
	                $this->add_control(
		                'product_'.$key.'_divider',
		                [
			                'type' => \Elementor\Controls_Manager::DIVIDER,
			                'condition' => [
				                'product_'.$key.'!' => '',
			                ]
		                ]
	                );
                    break;
				case 'button':
					
					$this->add_control(
						'product_'.$key.'_quantity',
						[
							'label'        => esc_html__('Show Quantity', 'xstore-core'),
							'type'         => \Elementor\Controls_Manager::SWITCHER,
							'condition' => [
								'product_'.$key.'!' => '',
							]
						]
					);
					$this->add_control(
						'product_'.$key.'_icon',
						[
							'label' 		=>	__( 'Icon', 'xstore-core' ),
							'type' 			=>	\Elementor\Controls_Manager::SELECT,
							'options' 		=>	[
								'bag' => esc_html__( 'Shopping Bag', 'xstore-core' ),
								'cart' => esc_html__( 'Shopping Cart', 'xstore-core' ),
								'cart2' => esc_html__( 'Shopping Cart 2', 'xstore-core' ),
								'custom' => esc_html__( 'Custom', 'xstore-core' ),
								'none' => esc_html__( 'None', 'xstore-core' ),
							],
							'default'	=> 'none',
							'condition' => [
								'product_'.$key.'!' => '',
							]
						]
					);
					
					$this->add_control(
						'product_'.$key.'_icon_align',
						[
							'label' => __( 'Icon Position', 'xstore-core' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => 'left',
							'options' => [
								'left' => __( 'Before', 'xstore-core' ),
								'right' => __( 'After', 'xstore-core' ),
							],
							'condition' => [
								'product_'.$key.'!' => '',
								'product_'.$key.'_quantity' => ''
							],
						]
					);
					
					$this->add_control(
						'product_'.$key.'_custom_selected_icon',
						[
							'label' => __( 'Button Icon', 'xstore-core' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'fa4compatibility' => 'product_'.$key.'_custom_icon',
							'skin' => 'inline',
							'label_block' => false,
							'condition' => [
								'product_'.$key.'!' => '',
								'product_'.$key.'_icon' => 'custom',
							],
							'default' => [
								'value' => 'fas fa-shopping-cart',
								'library' => 'fa-solid',
							],
						]
					);
					
					$this->add_control(
						'product_'.$key.'_custom_icon_indent',
						[
							'label' => __( 'Icon Spacing', 'xstore-core' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'range' => [
								'px' => [
									'max' => 50,
								],
							],
							'default' => [
								'size' => 7
							],
							'selectors' => [
								'{{WRAPPER}} .etheme-product-list-button .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .etheme-product-list-button .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'product_'.$key.'!' => '',
								'product_'.$key.'_icon!' => 'none',
							],
						]
					);
					break;
            }
        }
		
		$this->add_control(
			'show_divider',
			[
				'label'        => esc_html__( 'Show Divider', 'xstore-core' ),
				'separator' => 'before',
				'type'         => \Elementor\Controls_Manager::SWITCHER,
                'frontend_available' => true,
                'condition' => [
                    'bordered_layout' => ''
                ]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'cols_gap',
			[
				'label' => __( 'Columns Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'rows_gap',
			[
				'label' => __( 'Rows Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_product_style',
			[
				'label' => __( 'Product', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'alignment',
			[
				'label' 		=>	__( 'Alignment', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-item' => 'text-align: {{VALUE}};',
				]
			]
		);
		
		$this->add_control(
			'image_column_width',
			[
				'label' => __( 'Columns Proportion', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
                    'unit' => '%'
                ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 70,
						'step' => 1,
					],
					'px' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
                    'product_image!' => ''
                ],
				'selectors' => [
					'{{WRAPPER}}' => '--image-width-proportion: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-product-list-item'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-product-list-item',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .etheme-product-list-item',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		do_action('etheme_product_grid_list_product_elements_style', $this);
		
		$this->start_controls_section(
			'section_categories_style',
			[
				'label' => __( 'Categories', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_categories!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'categories_typography',
				'selector' => '{{WRAPPER}} .etheme-product-list-categories',
			]
		);
		
		$this->start_controls_tabs('tabs_categories_colors');
		
		$this->start_controls_tab( 'tabs_categories_color_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'categories_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-categories' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_categories_color_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'categories_color_hover',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-categories a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'categories_space',
			[
				'label' => __( 'Bottom Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-categories' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_title!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .etheme-product-list-title',
			]
		);
		
		$this->start_controls_tabs('tabs_title_colors');
		
		$this->start_controls_tab( 'tabs_title_color_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-title a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_title_color_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'title_space',
			[
				'label' => __( 'Bottom Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// image
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_image!' => ''
				],
			]
		);
		
		$this->add_control(
			'image_stretch',
			[
				'label' => __('Stretch Images', 'xstore-core'),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-image img' => 'width: 100%;',
				],
			]
		);
		
		$this->add_control(
			'image_scale',
			[
				'label' => __( 'Image Scale', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--image-scale: {{SIZE}};',
				],
			]
		);
		
		$this->add_control(
			'image_object_position_x',
			[
				'label' => __( 'Image Position X', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--image-position-x: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'image_object_position_y',
			[
				'label' => __( 'Image Position Y', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--image-position-y: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .etheme-product-list-image img',
			]
		);
		
		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'image_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--image-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// rating
		$this->start_controls_section(
			'section_rating_style',
			[
				'label' => __( 'Rating', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_rating!' => ''
				],
			]
		);
		
		$this->add_control(
			'rating_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .star-rating-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// sku
		$this->start_controls_section(
			'section_sku_style',
			[
				'label' => __( 'SKU', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_sku!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'sku_typography',
				'selector' => '{{WRAPPER}} .sku_wrapper',
			]
		);
		
		$this->start_controls_tabs('tabs_sku_colors');
		
		$this->start_controls_tab( 'tabs_sku_color_normal',
			[
				'label' => esc_html__('Regular', 'xstore-core')
			]
		);
		
		$this->add_control(
			'sku_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sku_wrapper' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_categories_color_number',
			[
				'label' => esc_html__('Number', 'xstore-core')
			]
		);
		
		$this->add_control(
			'sku_color_number',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sku' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'sku_space',
			[
				'label' => __( 'Bottom Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sku_wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// excerpt
		$this->start_controls_section(
			'section_excerpt_style',
			[
				'label' => __( 'Excerpt', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_excerpt!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .woocommerce-product-details__short-description',
			]
		);
		
		$this->add_control(
			'excerpt_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'excerpt_space',
			[
				'label' => __( 'Bottom Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-product-details__short-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// price
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __( 'Price', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_price!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .price',
			]
		);
		
		$this->start_controls_tabs('tabs_price_colors');
		
		$this->start_controls_tab( 'tabs_price_color_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'price_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .price' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_sale_price_color',
			[
				'label' => esc_html__('Sale', 'xstore-core')
			]
		);
		
		$this->add_control(
			'sale_price_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ins .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'price_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// button
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_button!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .etheme-product-list-button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );
		
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-product-list-button',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-button:hover, {{WRAPPER}} .etheme-product-list-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-product-list-button:hover svg, {{WRAPPER}} .etheme-product-list-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-product-list-button:hover, {{WRAPPER}} .etheme-product-list-button:focus',
			]
		);
		
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-button:hover, {{WRAPPER}} .etheme-product-list-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .etheme-product-list-button',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-product-list-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section();
		
		if (array_key_exists('countdown', $product_elements) ) {
			// countdown
			Elementor::get_countdown_settings($this, [
				'product_countdown!' => ''
			]);
			
			$this->start_injection( [
				'type' => 'control',
				'at'   => 'before',
				'of'   => 'countdown_stretch_items',
			] );

            $this->add_control(
                'product_countdown_description',
                [
                    'raw' => esc_html__('To display a countdown timer for products, please edit the product settings to include the sale price, sale price dates, sale price start time, and sale price end time.', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
			
			$this->add_control(
				'product_countdown',
				[
					'label' => $product_elements['countdown'],
					'type'  => \Elementor\Controls_Manager::SWITCHER,
				]
			);
			
			$this->end_injection();

            $this->update_control( 'countdown_stretch_items', [
                'separator' => 'before',
            ] );
			
			$this->remove_control('countdown_label_position');
			
			$this->update_control( 'countdown_border_border', [
				'default' => 'solid',
			] );
			
			$this->update_control( 'countdown_border_width', [
				'default' => [
					'top'    => 1,
					'left'   => 1,
					'right'  => 1,
					'bottom' => 1
				]
			] );
			
			$this->update_control( 'countdown_border_color', [
				'default' => '#e1e1e1',
			] );
			
			$this->update_control( 'countdown_border_radius', [
				'default' => [
					'top'    => 5,
					'right'  => 5,
					'bottom' => 5,
					'left'   => 5,
				]
			] );
			
			$this->update_control( 'countdown_background_background', [
				'default' => 'classic'
			] );
			
			$this->update_control( 'countdown_background_color', [
				'default' => '#ffffff'
			] );
			
			$this->update_control( 'digits_color', [
				'default' => '#222222'
			] );
			
			$this->update_control( 'label_color', [
				'default' => '#222222'
			] );
			
			$this->update_control( 'delimiter_color', [
				'default' => '#222222'
			] );
			
		}
		
		$this->start_controls_section(
			'section_style_divider',
			[
				'label' => __( 'Divider', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_divider!' => '',
                    'bordered_layout' => ''
                ]
			]
		);
		
		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--line-weight: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'xstore-core' ),
					'double' => __( 'Double', 'xstore-core' ),
					'dotted' => __( 'Dotted', 'xstore-core' ),
					'dashed' => __( 'Dashed', 'xstore-core' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}}' => '--line-style: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--line-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_section();
		
		// navigation button
		$this->start_controls_section(
			'section_navigation_button_style',
			[
				'label' => esc_html__( 'Navigation Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => 'button'
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_button_typography',
				'selector' => '{{WRAPPER}} .etheme-elementor-lazy-button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_navigation_button_style' );
		
		$this->start_controls_tab(
			'tab_navigation_button_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'navigation_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'navigation_button_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-elementor-lazy-button',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_navigation_button_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'navigation_button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button:hover, {{WRAPPER}} .etheme-elementor-lazy-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-elementor-lazy-button:hover svg, {{WRAPPER}} .etheme-elementor-lazy-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'navigation_button_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-elementor-lazy-button:hover, {{WRAPPER}} .etheme-elementor-lazy-button:focus',
			]
		);
		
		$this->add_control(
			'navigation_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'navigation_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button:hover, {{WRAPPER}} .etheme-elementor-lazy-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'navigation_button_border',
				'selector' => '{{WRAPPER}} .etheme-elementor-lazy-button',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'navigation_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'navigation_button_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'navigation_button_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button-wrapper' => 'margin: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
				],
			]
		);
		
		$this->end_controls_section();

        $this->start_controls_section(
            'section_navigation_progress_bar_style',
            [
                'label' => esc_html__( 'Navigation Count Bar', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'navigation' => 'button',
                    'navigation_button_type' => 'advanced'
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'navigation_progress_bar_title_typography',
                'selector' => '{{WRAPPER}} .etheme-elementor-lazy-progress-bar-title',
            ]
        );

        $this->add_control(
            'navigation_progress_bar_title_color',
            [
                'label' => __( 'Title Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-lazy-progress-bar-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_progress_bar_title_space',
            [
                'label' => __( 'Bottom Space', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-lazy-progress-bar-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_progress_bar_heading',
            [
                'label' => esc_html__( 'Progress Bar', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'navigation_progress_bar_max_width',
            [
                'label' => __( 'Max Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--progress-max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_progress_bar_height',
            [
                'label' => __( 'Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--progress-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_progress_bar_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--progress-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_progress_bar_background_color',
            [
                'label' => __( 'Default Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-lazy-progress-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'navigation_progress_bar_background_active',
                'label' => esc_html__( 'Active Color', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                        'label' => esc_html__( 'Active Color', 'xstore-core' )
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: {{VALUE}}; --progress-active-color: {{VALUE}}',
                        ],
                    ],
                    'gradient_angle' => [
                        'default' => [
                            'unit' => 'deg',
                            'size' => 90,
                        ],
                    ]
                ],
                'selector' => '{{WRAPPER}} .etheme-elementor-lazy-progress-bar-inner',
            ]
        );

        $this->end_controls_section();
		
		// navigation scroll
		$this->start_controls_section(
			'section_navigation_scroll_style',
			[
				'label' => esc_html__( 'Scroll Loader', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => 'scroll'
				],
			]
		);
		
		$this->add_control(
			'navigation_scroll_color',
			[
				'label' => esc_html__( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button-wrapper' => '--etheme-elementor-loader-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'navigation_scroll_size',
			[
				'label' => __( 'Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button-wrapper' => '--etheme-elementor-loader-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'navigation_scroll_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button-wrapper' => 'margin: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
				],
			]
		);
		
		$this->end_controls_section();
		
		// navigation pagination
		$this->start_controls_section(
			'section_navigation_pagination_style',
			[
				'label' => esc_html__( 'Pagination', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => 'pagination'
				],
			]
		);
		
		$this->add_responsive_control(
			'navigation_pagination_items_gap',
			[
				'label' => __( 'Items Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination' => '--etheme-elementor-pagination-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'navigation_pagination_size',
			[
				'label' => __( 'Items Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination' => '--etheme-elementor-pagination-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_pagination_typography',
				'selector' => '{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers',
			]
		);
		
		$this->start_controls_tabs( 'tabs_navigation_pagination_style' );
		
		$this->start_controls_tab(
			'tab_navigation_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'navigation_pagination_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'navigation_pagination_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_navigation_pagination_hover',
			[
				'label' => esc_html__( 'Active/Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'navigation_pagination_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:hover, {{WRAPPER}} .etheme-elementor-pagination ul .current, {{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:hover svg, {{WRAPPER}} .etheme-elementor-pagination ul .current svg, {{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'navigation_pagination_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:hover, {{WRAPPER}} .etheme-elementor-pagination ul .current, {{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:focus',
			]
		);
		
		$this->add_control(
			'navigation_pagination_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'navigation_pagination_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:hover, {{WRAPPER}} .etheme-elementor-pagination ul .current, {{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'navigation_pagination_border',
				'selector' => '{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'navigation_pagination_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'navigation_pagination_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.1.2
	 * @access protected
	 */
	protected function render() {
	    
	    if ( !class_exists('WooCommerce') ) {
	        echo esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core');
	        return;
        }
		
		$settings = $this->get_settings_for_display();
		$offset = $settings['offset'] && $settings['offset'] > 0 ? $settings['offset'] : 0;
		
		self::$id = $this->get_id();
		self::$page_link = get_permalink();
		self::$widget_type = 'product-list';
		
		if ( in_array($settings['navigation'], array('button', 'scroll')) ||
            ($settings['navigation'] == 'pagination' && !!$settings['navigation_pagination_ajax']) )
			wp_enqueue_script( 'etheme_post_product' );
		
		// loop start classes, html tag filter
		add_filter('woocommerce_product_loop_start', array($this, 'product_loop_start_filter'), 10, 1);

        $is_current_query = $settings['query_type'] == 'current_query';

        if ( $is_current_query && defined('ELEMENTOR_PRO_VERSION') ) {
            $current_query_settings = $settings;
            $current_query_settings['allow_order'] = '';
            $current_query_settings['show_result_count'] = '';
            $current_query_settings['paginate'] = 'yes';
            // For Products_Renderer.
            if (!isset($GLOBALS['post'])) {
                $GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            }

            global $local_settings;
            $local_settings = $settings;
            $shortcode = new \ElementorPro\Modules\Woocommerce\Classes\Current_Query_Renderer($current_query_settings, 'current_query');
            add_filter('wc_get_template_part', array($this, 'filter_template_part'));
            wc_set_loop_prop( 'columns', $settings['cols'] );
            wc_set_loop_prop( 'etheme_elementor_product_widget', true );
            wc_set_loop_prop( 'is_shortcode', true );
            add_filter('theme_mod_ajax_product_filter', '__return_false');
            add_filter('et_ajax_widgets', '__return_false');
            add_filter('etheme_grid_list_switcher_enabled', '__return_false');
            add_filter('etheme_products_per_page_select_enabled', '__return_false');
            add_filter('etheme_shop_filters_enabled', '__return_false');
            add_filter('etheme_shop_top_toolbar_enabled', '__return_false');

            // Pagination shop
            add_filter( 'woocommerce_pagination_args', 'et_woocommerce_pagination' );

            $shortcode_content = $shortcode->get_content();
            if ($shortcode_content) {
                $pagination_classes = ' etheme-elementor-pagination';
                $navigation_content = '';
                if ( $settings['navigation'] != 'pagination' ) {
                    $pagination_classes .= ' hidden';
                    $query = $GLOBALS['wp_query'];
                    if ( $query->max_num_pages > get_query_var( 'paged', 1 )) {
                        $this->add_render_attribute( 'load-more-button-wrapper', [
                            'class' => [
                                'etheme-elementor-lazy-button-wrapper',
                                'elementor-align-center',
                            ]
                        ]);
                        if ( $settings['navigation'] == 'button' ) {
                            $this->add_render_attribute( 'load-more-button', [
                                'class' => [
                                    'elementor-button',
                                ]
                            ]);
                        }
                        $this->add_render_attribute( 'load-more-button', [
                            'class' => [
                                'etheme-elementor-lazy-button',
                                'navigation-type-'.$settings['navigation']
                            ] ]);
                        ob_start(); ?>
                        <div <?php $this->print_render_attribute_string( 'load-more-button-wrapper' ); ?>>
                            <?php if ( $settings['navigation'] == 'button' && $settings['navigation_button_type'] == 'advanced' ) :
                                $locally_total = $query->found_posts;
                                $locally_shown = (int) $query->get( 'posts_per_page' ) * (int) max( 1, $query->get( 'paged', 1 ) );
                                if ( $locally_shown > $locally_total)
                                    $locally_shown = $locally_total;

                                $this->add_render_attribute( 'load-more-button', [
                                    'data-found-posts' => $locally_total
                                ]);
                                $this->get_progress_bar($locally_total, $locally_shown);
                            endif; ?>
                            <a <?php $this->print_render_attribute_string( 'load-more-button' ); ?>>
                                <?php if ( $settings['navigation'] == 'button' ) {
                                    echo '<span>' . $settings['navigation_button_text'] . '</span>';
                                } ?>
                            </a>
                        </div>
                        <?php
                        $navigation_content = ob_get_clean();
                    }
                }
                if ($settings['navigation'] != 'pagination' || !!$settings['navigation_pagination_ajax']) {
                    $pagination_classes .= ' etheme-elementor-pagination-ajax';
                }
//                    $this->add_render_attribute( 'pagination-wrapper', [
//                        'class' => 'etheme-elementor-pagination-ajax',
//                        'data-widget-id' => self::$id,
//                        'data-total-pages' => $total,
//                        'data-permalink' => self::$page_link,
//                        'data-limit' => $settings['limit']
//                    ]);
                if ( isset($_GET['et_columns-count']) ) {
                    echo sprintf('<style id="%1s">%2s</style>',
                        $this->get_name() . '-' . self::$id,
                        'body .elementor-element.elementor-element-' . self::$id . '{--cols: '.$_GET['et_columns-count'].' !important}'); // XSS ok.
                }
                echo str_replace(array('woocommerce-pagination', '</nav>'), array($pagination_classes, '</nav>'.$navigation_content), $shortcode_content);
            }
            else {
                echo '<div class="woocommerce-info">'.esc_html__('Unfortunately, there are no products that match your criteria', 'xstore-core').'</div>';
                $search_widget = new \WC_Widget_Product_Search();
                $search_widget_args = array(
                    'widget_id' => 'woocommerce_product_search_'.$this->get_id(),
                    'before_widget' => '',
                    'after_widget' => '',
                    'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
                    'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
                );
                $search_widget->widget($search_widget_args, array('title' => ''));
            }
            remove_filter( 'woocommerce_pagination_args', 'et_woocommerce_pagination' );
            remove_filter('theme_mod_ajax_product_filter', '__return_false');
            remove_filter('et_ajax_widgets', '__return_false');
            remove_filter('etheme_shop_top_toolbar_enabled', '__return_false');
            remove_filter('etheme_shop_filters_enabled', '__return_false');
            remove_filter('etheme_products_per_page_select_enabled', '__return_false');
            remove_filter('etheme_grid_list_switcher_enabled', '__return_false');
//                remove_filter('etheme_elementor_theme_builder', '__return_true');
            remove_filter('wc_get_template_part', array($this, 'filter_template_part'));
        }
        else {

            $products = self::get_query( $settings );
            wc_set_loop_prop( 'columns', $settings['cols'] );
            wc_set_loop_prop( 'etheme_elementor_product_widget', true );
            wc_set_loop_prop( 'is_shortcode', true );
            global $local_settings;
            $local_settings = $settings;
            if ( $products && $products->have_posts() ) {

                $_i=0;

                woocommerce_product_loop_start();

                if ( $settings['navigation'] == 'pagination' && ! ! ! $settings['navigation_pagination_ajax'] ) {
                    $page      = absint( empty( $_GET[ 'etheme-' . self::$widget_type . '-' . self::$id . '-page' ] ) ? 1 : $_GET[ 'etheme-' . self::$widget_type . '-' . self::$id . '-page' ] );
                    $new_limit = $settings['limit'] != -1 ? $settings['limit'] : ($products->found_posts - $offset);
                    if ( $page > 1 ) {
                        $loaded_posts = ( $page - 1 ) * $settings['posts_per_page'];
                        if ( $settings['limit'] > $loaded_posts ) {
                            $new_limit = $settings['limit'] - $loaded_posts;
                        }
                    }
                    while ( $products->have_posts() ) {
                        $products->the_post();
                        if ( $_i >= $new_limit ) {
                            break;
                        }
                        $_i ++;
                        $this->get_content_product( $local_settings );
                    }
                }

                else {
                    while ( $products->have_posts() ) {
                        $products->the_post();
                        $this->get_content_product( $local_settings );
                    }
                }

                woocommerce_product_loop_end();

                if ( $settings['navigation'] != 'none' ) {

                    if ( $products->max_num_pages > 1 && ($settings['limit'] == -1 || $settings['limit'] > $settings['posts_per_page']) ) {

                        $button_attributes = $settings['navigation'] == 'pagination' && !!$settings['navigation_pagination_ajax'] ? 'pagination-wrapper' : 'load-more-button';

                        $nonce = wp_create_nonce( 'etheme_'.self::$widget_type.'_nonce' );

                        $product_content = array();
                        foreach (self::get_product_elements() as $key => $string) {
                            if ( !$settings['product_'.$key]) continue;
                            $product_content['product_'.$key] = true;
                            switch ($key) {
                                case 'image':
                                    $product_content[$key.'_size'] = $settings[$key.'_size'];
                                    $product_content[$key.'_custom_dimension'] = $settings[$key.'_custom_dimension'];
                                    $product_content['product_sale_label'] = $settings['product_sale_label'];
                                    if ( apply_filters('etheme_product_grid_list_product_new_label', false) ) {
                                        $product_content['product_new_label'] = $settings['product_new_label'];
                                    }
                                    break;
                                case 'button':
                                    $product_content['product_'.$key.'_icon'] = $settings['product_'.$key.'_icon'];
                                    $product_content['product_'.$key.'_icon_align'] = $settings['product_'.$key.'_icon_align'];
                                    $product_content['product_'.$key.'_custom_icon'] = $settings['product_'.$key.'_custom_icon']??false;
                                    $product_content['product_'.$key.'_custom_selected_icon'] = $settings['product_'.$key.'_custom_selected_icon']??false;
                                    $product_content['product_'.$key.'_quantity'] = $settings['product_'.$key.'_quantity']??false;
                                    break;
                                case 'countdown':
                                    // in case first shown product will not have counters but others could have
                                    // after loading
                                    wp_enqueue_script('etheme_countdown');
                                    wp_enqueue_style('etheme-elementor-countdown');
                                    $product_content[$key.'_custom_labels'] = $settings[$key.'_custom_labels'];
                                    $product_content[$key.'_label_days'] = $settings[$key.'_label_days'];
                                    $product_content[$key.'_label_hours'] = $settings[$key.'_label_hours'];
                                    $product_content[$key.'_label_minutes'] = $settings[$key.'_label_minutes'];
                                    $product_content[$key.'_label_seconds'] = $settings[$key.'_label_seconds'];
                                    $product_content[$key.'_show_days'] = $settings[$key.'_show_days'];
                                    $product_content[$key.'_show_hours'] = $settings[$key.'_show_hours'];
                                    $product_content[$key.'_show_minutes'] = $settings[$key.'_show_minutes'];
                                    $product_content[$key.'_show_seconds'] = $settings[$key.'_show_seconds'];
                                    $product_content[$key.'_add_delimiter'] = $settings[$key.'_add_delimiter'];
                                    $product_content[$key.'_delimiter'] = $settings[$key.'_delimiter'];
                                    break;
                                case 'title':
                                case 'excerpt':
                                    if ( $key == 'title' )
                                        $product_content['product_'.$key.'_tag'] = $settings['product_'.$key.'_tag'];

                                    $product_content['product_'.$key.'_limit_type'] = $settings['product_'.$key.'_limit_type'];
                                    $product_content['product_'.$key.'_limit'] = $settings['product_'.$key.'_limit'];
                                    break;
                            }
                        }
                        $this->add_render_attribute( 'load-more-button-wrapper', [
                            'class' => [
                                'etheme-elementor-lazy-button-wrapper',
                                'elementor-align-center',
                            ]
                        ]);
                        if ( $local_settings['navigation'] == 'button' ) {
                            $this->add_render_attribute( 'load-more-button', [
                                'class' => [
                                    'elementor-button',
                                ]
                            ]);
                        }

                        $query_settings = array(
                            'select_date' => $settings['select_date'],
                        );
                        if ( $settings['select_date'] == 'exact') {
                            $query_settings['date_before'] = $settings['date_before'];
                            $query_settings['date_after'] = $settings['date_after'];
                        }

                        $taxonomy_type = $settings['taxonomy_type'];
                        if ( $taxonomy_type == 'product_cat' ) {
                            $query_settings['ids'] = $settings['ids'];
                        }
                        else {
                            $query_settings[$taxonomy_type.'s'] = $settings[$taxonomy_type.'s'];
                        }

                        if ( $settings['limit'] != '-1' && ($products->found_posts - $offset) > $settings['limit'])
                            $found_posts = $settings['limit'];
                        else
                            $found_posts = max(0, $products->found_posts - $offset);

                        $this->add_render_attribute( $button_attributes, [
                                'data-widget-type' => self::$widget_type,
                                'data-paged' => '1',
                                'data-max-paged' => $products->max_num_pages,
                                'data-found-posts' => $found_posts,
                                'data-offset' => $offset,
                                'data-nonce' => $nonce,
                                'data-query-settings' => esc_attr(wp_json_encode(array_merge(
                                        array(
                                            'posts_per_page' => $settings['posts_per_page'],
                                            'offset' => $offset,
                                            'limit' => $settings['limit'],
                                            'navigation' => $settings['navigation'],
                                            'order' => $settings['order'],
                                            'show_hidden' => $settings['show_hidden'],
                                            'hide_free' => $settings['hide_free'],
                                            'hide_sale' => $settings['hide_sale'],
                                            'hide_out_of_stock' => $settings['hide_out_of_stock'],
                                            'query_type' => $settings['query_type'],
                                            'orderby' => $settings['orderby'],
                                            'taxonomy_type' => $taxonomy_type
                                        ),
                                        $query_settings
                                ))),
                                'data-product-settings' => esc_attr(wp_json_encode(
                                    $product_content
                                ))
                            ]
                        );

                        if ( isset( $settings['limit'] ) && $settings['limit'] != -1 ) {
                            $this->add_render_attribute( 'load-more-button', [
                                'data-limit' => $settings['limit']
                            ]);
                        }

                        switch ($settings['navigation']) {
                            case 'button':
                            case 'scroll':
                                $this->add_render_attribute( 'load-more-button', [
                                    'class' => [
                                        'etheme-elementor-lazy-button',
                                        'navigation-type-'.$settings['navigation']
                                    ] ]);
                                ?>
                                <div <?php $this->print_render_attribute_string( 'load-more-button-wrapper' ); ?>>
                                    <?php if ( $settings['navigation'] == 'button' && $settings['navigation_button_type'] == 'advanced' ) :
                                        $this->get_progress_bar($found_posts, $settings['posts_per_page']);
                                    endif; ?>
                                    <a <?php $this->print_render_attribute_string( 'load-more-button' ); ?>>
                                        <?php if ( $settings['navigation'] == 'button' ) {
                                            echo '<span>' . $settings['navigation_button_text'] . '</span>';
                                        } ?>
                                    </a>
                                </div>
                                <?php
                                break;
                            case 'pagination':
                                $is_rtl = is_rtl();
                                $left_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">' .
                                              '<path d="M17.976 22.8l-10.44-10.8 10.464-10.848c0.24-0.288 0.24-0.72-0.024-0.96-0.24-0.24-0.72-0.264-0.984 0l-10.92 11.328c-0.264 0.264-0.264 0.672 0 0.984l10.92 11.28c0.144 0.144 0.312 0.216 0.504 0.216 0.168 0 0.336-0.072 0.456-0.192 0.144-0.12 0.216-0.288 0.24-0.48 0-0.216-0.072-0.384-0.216-0.528z"></path>' .
                                              '</svg>';
                                $right_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">' .
                                               '<path d="M17.88 11.496l-10.728-11.304c-0.264-0.264-0.672-0.264-0.96-0.024-0.144 0.12-0.216 0.312-0.216 0.504 0 0.168 0.072 0.336 0.192 0.48l10.272 10.8-10.272 10.8c-0.12 0.12-0.192 0.312-0.192 0.504s0.072 0.36 0.192 0.504c0.12 0.144 0.312 0.216 0.48 0.216 0.144 0 0.312-0.048 0.456-0.192l0.024-0.024 10.752-11.328c0.264-0.264 0.24-0.672 0-0.936z"></path>' .
                                               '</svg>';
                                $total = $found_posts >= $settings['limit'] ? ceil($settings['limit']/$settings['posts_per_page']) : $products->max_num_pages;
                                if ( $found_posts >= $settings['limit'] && $settings['limit'] == -1 || ($offset && $found_posts + $offset >= $settings['limit']) )
                                    $total = ceil( $found_posts / $settings['posts_per_page'] );

                                $this->add_render_attribute( 'pagination-wrapper', [
                                    'class' => 'etheme-elementor-pagination',
                                ]);

                                if ( !!$settings['navigation_pagination_ajax'] ) {
                                    $this->add_render_attribute( 'pagination-wrapper', [
                                        'class' => 'etheme-elementor-pagination-ajax',
                                        'data-widget-id' => self::$id,
                                        'data-total-pages' => $total,
                                        'data-permalink' => self::$page_link,
                                        'data-limit' => $settings['limit']
                                    ]);
                                }
                                ?>
                                <div <?php $this->print_render_attribute_string( 'pagination-wrapper' ); ?>>
                                    <?php
                                    echo paginate_links( array(
                                        'base'      => esc_url_raw( add_query_arg( 'etheme-'.self::$widget_type.'-'.self::$id.'-page', '%#%', self::$page_link ) ),
                                        'format'    => '?etheme-'.self::$widget_type.'-'.self::$id.'-page=%#%',
                                        'add_args'  => false,
                                        'current'   => max( 1, absint( empty( $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] ) ? 1 : $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] ) ),
                                        'total'     => $total,
                                        'prev_text' => $is_rtl ? $right_arrow : $left_arrow,
                                        'next_text' => $is_rtl ? $left_arrow : $right_arrow,
                                        'type'      => 'list',
                                        'end_size'  => 2,
                                        'mid_size'  => 2,
                                    ) );
                                    ?>
                                </div>
                                <?php
                                break;
                        }
                    }
                }

                wc_reset_loop();
                wp_reset_postdata();

            }

            else {
                if ( $settings['query_type'] != 'recently_viewed' || \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                    echo '<div class="elementor-panel-alert elementor-panel-alert-warning">' .
                        esc_html__('No products were found matching your selection.', 'xstore-core') .
                        '</div>';
                }
            }

        }
		remove_filter('woocommerce_product_loop_start', array($this, 'product_loop_start_filter'), 10, 1);
		
	}
	
	/**
	 * Get query for render products.
	 *
	 * @param $settings
	 * @return \WP_Query
	 *
	 * @since 4.1.2
	 *
	 */
	public static function get_query($settings, $extra_params = array()) {
		
		$page = absint( empty( $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] ) ? 1 : $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] );
		
		$query_args = array(
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'page' => $page,
			'no_found_rows'  => $settings['navigation'] != 'none' ? false : 1,
			'order'          => $settings['order'],
			'meta_query'     => array(),
			'tax_query'      => array(
				'relation' => 'AND',
			),
		); // WPCS: slow query ok.

        $posts_per_page = $settings['limit'];
        if ( $settings['navigation'] != 'none' ) {
            if ( $settings['limit'] > $settings['posts_per_page'] || $settings['limit'] == -1 ) {
                $posts_per_page = $settings['posts_per_page'];
            }
        }
		$query_args['posts_per_page'] = $posts_per_page;
		
		if ( 1 < $page ) {
			$query_args['paged'] = $page;
		}

		if ( $settings['query_type'] != 'product_ids') {
			if ( $settings['offset'] && $settings['offset'] > 0 ) {
				// it is for non-ajax pagination cases
				if ( isset($query_args['paged']) ) {
					$query_args['offset'] = $settings['offset'] + ( ( $query_args['paged'] - 1 ) * $query_args['posts_per_page'] );
				} else {
					$query_args['offset'] = $settings['offset'];
				}
			}

            $query_args = self::set_date_args($query_args, $settings);

		}
		
		$query_args = wp_parse_args( $extra_params, $query_args );
		
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();
		
		if ( empty( $settings['show_hidden'] ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
				'operator' => 'NOT IN',
			);
//			$query_args['post_parent'] = 0;
		}
		
		if ( ! empty( $settings['hide_free'] ) ) {
			$query_args['meta_query'][] = array(
				'key'     => '_price',
				'value'   => 0,
				'compare' => '>',
				'type'    => 'DECIMAL',
			);
		}

//	    if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
		if ( $settings['hide_out_of_stock'] ) {
			$query_args['tax_query'][] = array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_term_ids['outofstock'],
					'operator' => 'NOT IN',
				),
			); // WPCS: slow query ok.
		}

        if ( $settings['hide_sale'] ) {
            $product_ids_on_sale    = wc_get_product_ids_on_sale();
            $product_ids_on_sale[]  = 0;
            $query_args['post__not_in'] = $product_ids_on_sale;
        }
		
		switch ( $settings['query_type'] ) {
			case 'featured':
				$query_args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_term_ids['featured'],
				);
				break;
			case 'onsale':
				$product_ids_on_sale    = wc_get_product_ids_on_sale();
				$product_ids_on_sale[]  = 0;
				$query_args['post__in'] = $product_ids_on_sale;
				break;
			case 'product_ids':
				// backup value for limit if not products are set
				$query_args['posts_per_page'] = 8;
                $settings['include_products'] = !is_array($settings['include_products']) ? explode(',', $settings['include_products']) : $settings['include_products'];
				if ( count($settings['include_products']) ) {
					$query_args['post_type'] = array_merge((array)$query_args['post_type'], array('product_variation'));
					$query_args['post__in']       = $settings['include_products'];
					$query_args['orderby']        = 'post__in';
					$query_args['posts_per_page'] = -1;
				}
				break;
            case 'recently_viewed':
                $product_ids_on_viewed = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
                $product_ids_on_viewed = array_filter( array_map( 'absint', $product_ids_on_viewed ) );

                $product_ids_on_viewed[]  = 0;
                $query_args['post__in'] = $product_ids_on_viewed;
                break;
            case 'related_products':
            case 'upsells':
            case 'cross_sells':
                global $product;
                if ( $product ) {
                    $products = array();
                    switch ($settings['query_type']) {
                        case 'related_products':
                            $products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $query_args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
                            break;
                        case 'upsells':
                            $products = array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' );
                            break;
                        case 'cross_sells':
                            $products = array_filter( array_map( 'wc_get_product', $product->get_cross_sell_ids() ), 'wc_products_array_filter_visible' );
                            break;
                    }
                    if ( !empty($products) ) {
                        $query_args['post__in'] = array_map(function ($local_product) {
                            return $local_product->get_id();
                        }, $products);
                    }
                }
                break;
		}
		
		switch ( $settings['orderby'] ) {
			case 'price':
				$query_args['meta_key'] = '_price'; // WPCS: slow query ok.
				$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rand':
			case 'menu_order':
				$query_args['orderby'] = $settings['orderby'];
				break;
			case 'sales':
				$query_args['meta_key'] = 'total_sales'; // WPCS: slow query ok.
				$query_args['orderby']  = 'meta_value_num';
				break;
			default:
                if ( !in_array($settings['query_type'], array('product_ids', 'current_query')) )
					$query_args['orderby'] = 'date';
		}

        if ( $settings['query_type'] != 'product_ids') {
			switch ( $settings['taxonomy_type'] ) {
				case 'product_cat':
					if ( $settings['ids'] ) {
						$query_args['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'id',
							'terms'    => $settings['ids'],
						);
					}
					break;
				default:
					if ( $settings[ $settings['taxonomy_type'] . 's' ] ) {
						$query_args['tax_query'][] = array(
							'taxonomy' => $settings['taxonomy_type'],
							'field'    => 'id',
							'terms'    => $settings[ $settings['taxonomy_type'] . 's' ],
						);
					}
					break;
			}
		}
		
		return new \WP_Query( apply_filters( 'woocommerce_products_widget_query_args', $query_args ) );
	}

    protected static function set_date_args($query_args, $settings) {

        $select_date = $settings['select_date'];
        if ( ! empty( $select_date ) ) {
            $date_query = [];
            switch ( $select_date ) {
                case 'today':
                    $date_query['after'] = '-1 day';
                    break;
                case 'week':
                    $date_query['after'] = '-1 week';
                    break;
                case 'month':
                    $date_query['after'] = '-1 month';
                    break;
                case 'quarter':
                    $date_query['after'] = '-3 month';
                    break;
                case 'year':
                    $date_query['after'] = '-1 year';
                    break;
                case 'exact':
                    $after_date = $settings['date_after'];
                    if ( ! empty( $after_date ) ) {
                        $date_query['after'] = $after_date;
                    }
                    $before_date = $settings['date_before'];
                    if ( ! empty( $before_date ) ) {
                        $date_query['before'] = $before_date;
                    }
                    $date_query['inclusive'] = true;
                    break;
            }

            $query_args['date_query'] = $date_query;
        }

        return $query_args;
    }

	/**
	 * Filter loop start html for compatibility with 3d-party plugins.
	 *
	 * @param $html
	 * @return string|string[]
	 *
	 * @since 4.1.2
	 *
	 */
    public function product_loop_start_filter($html) {
	    $settings = $this->get_settings_for_display();
        $class = 'etheme-product-list ' . ($settings['bordered_layout'] ? 'etheme-product-grid-bordered ' : '');
	    $html = str_replace('class="', 'class="'.$class.' ', $html);
	    $html = str_replace('<ul', '<div', $html);
	    return $html;
    }
	
	/**
	 * Filter loop end html for compatibility with 3d-party plugins.
	 *
	 * @param $html
	 * @return string|string[]
	 *
	 * @since 4.1.2
	 *
	 */
	public function product_loop_end_filter($html) {
		return str_replace('</ul', '</div', $html);
	}
	
	/**
	 * Get content of product.
	 *
	 * @since 4.1.2
	 *
	 * @return void
	 */
	public function get_content_product($settings) {
//		$settings = $this->get_settings_for_display();
		global $local_settings;
		$local_settings = $settings;
		global $product;
		
		// Ensure visibility.
		if ( empty( $product ) || ! $product->is_visible() )
			return;
		
		// filter image size
		if ( $local_settings['image_size'] != 'custom')
			add_filter('single_product_archive_thumbnail_size', array($this, 'image_prerendered_size_filter'), 10);
		else
			add_filter('woocommerce_product_get_image', array($this, 'filter_image_custom_size'), 10, 5);
		
		// add custom class for title
		add_filter('woocommerce_product_loop_title_classes', array($this, 'add_class_for_title'), 10);
		
		add_filter('etheme_static_block_prevent_setup_post', '__return_true');
		
		$class = 'etheme-product-list-item';
        $class .= $local_settings['show_divider'] ? ' has-divider' : '';
		
		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        
		?>
        
        <div <?php wc_product_class( $class, $product ); ?>>
        
        <?php
        $local_content = array();
		foreach (self::get_product_elements() as $key => $string_text) {
			
			if ( !isset($local_settings['product_'.$key]) || !$local_settings['product_'.$key]) continue;
		    switch ($key) {
                case 'image':
                    ob_start();
                    if ( $local_settings['product_sale_label'])
                        woocommerce_show_product_loop_sale_flash();
                    if ( isset($local_settings['product_new_label']) && $local_settings['product_new_label'] ) {
                        $product_new_label_range = get_theme_mod('product_new_label_range', 0);
                        if ( $product_new_label_range > 0 ) {
                            $postdate        = apply_filters('product_new_label_on_date_created', false) ?
                                get_the_date( 'Y-m-d', $product->get_id() ) :
                                get_the_modified_date( 'Y-m-d', $product->get_id() );
                            $post_date_stamp = strtotime( $postdate );

                            $with_new_label = ( time() - ( 60 * 60 * 24 * $product_new_label_range ) ) < $post_date_stamp;
                            if ( $with_new_label ) { ?>
                                <div class="sale-wrapper">
                                    <span class="onsale left new-label"><?php echo esc_html__('New', 'xstore-core'); ?></span>
                                </div>
                                <?php
                            }
                        }
                    }
                    woocommerce_template_loop_product_thumbnail();
	                $local_content[$key] = ob_get_clean();
                    break;
                case 'categories':
                    ob_start();
                    $this->get_product_categories();
	                $local_content[$key] = ob_get_clean();
                    break;
                case 'title':
                    ob_start();
                    if ( $local_settings['product_title_limit_type'] != 'none' )
                        add_filter('the_title', array($this, 'limit_title_string'), 10);
	                add_filter('the_title', array($this, 'add_link_for_title'), 10, 2);
	                
                    woocommerce_template_loop_product_title();
	
	                /* @use for etheme_get_fake_product_sales_count() */
	                // not working if ajaxify
	                do_action('after_etheme_product_grid_list_product_element_'.$key);
                    
	                remove_filter('the_title', array($this, 'add_link_for_title'), 10, 2);
	                if ( $local_settings['product_title_limit_type'] != 'none' )
	                    remove_filter('the_title', array($this, 'limit_title_string'), 10);

                    $local_content[$key] = ob_get_clean();
                    if ( $local_settings['product_title_tag'] && $local_settings['product_title_tag'] != 'h2' ) {
                        $local_content[$key] = str_replace(
                            array('<h2', '</h2>'),
                            array('<' . $local_settings['product_title_tag'], '</' . $local_settings['product_title_tag'] . '>'),
                            $local_content[$key]
                        );
                    }
                    break;
                case 'price':
                    ob_start();
	                woocommerce_template_loop_price();
	                $local_content[$key] = ob_get_clean();
                    break;
                case 'rating':
                    ob_start();
                    echo '<div class="star-rating-wrapper">';
	                woocommerce_template_loop_rating();
	                echo '</div>';
	                $local_content[$key] = ob_get_clean();
	                break;
			    case 'button':
				    ob_start();
				    $product_type_quantity_types = apply_filters('etheme_product_type_show_quantity', array('simple', 'variable', 'variation'));
				    if ( $local_settings['product_button_quantity'] && in_array($product->get_type(), $product_type_quantity_types) ) {
					    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
					    add_filter('woocommerce_product_add_to_cart_text', '__return_false');
					    remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
					    remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
					    add_action( 'woocommerce_before_quantity_input_field', array($this, 'quantity_minus_icon') );
					    add_action( 'woocommerce_after_quantity_input_field', array($this, 'quantity_plus_icon') );
					    add_filter('esc_html', array($this, 'escape_text'), 10, 2);
					    add_filter('woocommerce_loop_add_to_cart_args', array($this, 'add_class_for_button'), 10, 1);
					    add_filter('woocommerce_product_add_to_cart_text', array($this, 'add_to_cart_icon'), 10);
					    echo '<div class="quantity-wrapper">';
					    woocommerce_quantity_input( array(
                            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product )
                        ), $product, true );
					    woocommerce_template_loop_add_to_cart();
					    echo '</div>';
					    remove_filter('woocommerce_product_add_to_cart_text', array($this, 'add_to_cart_icon'), 10);
					    remove_filter('woocommerce_loop_add_to_cart_args', array($this, 'add_class_for_button'), 10, 1);
					    remove_filter('esc_html', array($this, 'escape_text'), 10, 2);
					    remove_action( 'woocommerce_before_quantity_input_field', array($this, 'quantity_minus_icon') );
					    remove_action( 'woocommerce_after_quantity_input_field', array($this, 'quantity_plus_icon') );
					    add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
					    remove_filter('woocommerce_product_add_to_cart_text', '__return_false');
				    }
				    else {
					    add_filter('esc_html', array($this, 'escape_text'), 10, 2);
					    add_filter('woocommerce_loop_add_to_cart_args', array($this, 'add_class_for_button'), 10, 1);
					    add_filter('woocommerce_product_add_to_cart_text', array($this, 'add_to_cart_icon'), 10);
					    woocommerce_template_loop_add_to_cart();
					    remove_filter('woocommerce_product_add_to_cart_text', array($this, 'add_to_cart_icon'), 10);
					    remove_filter('woocommerce_loop_add_to_cart_args', array($this, 'add_class_for_button'), 10, 1);
					    remove_filter('esc_html', array($this, 'escape_text'), 10, 2);
				    }
				    $local_content[$key] = ob_get_clean();
				    break;
                case 'excerpt':
                    ob_start();
	                if ( $local_settings['product_excerpt_limit_type'] != 'none' )
		                add_filter('woocommerce_short_description', array($this, 'limit_excerpt_string'), 10);
	                woocommerce_template_single_excerpt();
	                if ( $local_settings['product_excerpt_limit_type'] != 'none' )
		                remove_filter('woocommerce_short_description', array($this, 'limit_excerpt_string'), 10);
	                $local_content[$key] = ob_get_clean();
                    break;
			    case 'sku':
			        ob_start();
				    $this->get_product_sku();
				    $local_content[$key] = ob_get_clean();
				    break;
			    case 'countdown':
				    ob_start();
				    $this->get_countdown($local_settings, $product);
				    $local_content[$key] = ob_get_clean();
				    break;
			    default:
				    ob_start();
				    add_filter('woocommerce_loop_add_to_cart_args', array($this, 'add_class_for_button'), 10, 1);
				    do_action('etheme_product_grid_list_product_element_render', $key, $product, $edit_mode, $this);
				    remove_filter('woocommerce_loop_add_to_cart_args', array($this, 'add_class_for_button'), 10, 1);
				    $get_action_content = ob_get_clean();
				    if ( $get_action_content != '')
					    $local_content[$key] = $get_action_content;
		    }
		}
		if ( $local_settings['alignment'] != 'right' && isset($local_content['image'])) {
			echo '<div class="etheme-product-list-image"><a href="'.$product->get_permalink().'">' .
                 $local_content['image'] .
             '</a></div>';
		}
		
		$list_content = $local_content;
		unset($list_content['image']);
		
        if ( count($list_content) ) {
	        echo '<div class="etheme-product-list-content">' .
	             implode('', $list_content) .
	             '</div>';
        }

        if ( $local_settings['alignment'] == 'right' && isset($local_content['image'])) {
	        echo '<div class="etheme-product-list-image"><a href="'.$product->get_permalink().'">' .
	             $local_content['image'] .
	             '</a></div>';
        }
		
		?>
        </div>
        <?php
		
		remove_filter('etheme_static_block_prevent_setup_post', '__return_true');
		
		if ( $local_settings['image_size'] != 'custom')
			remove_filter('single_product_archive_thumbnail_size', array($this, 'image_prerendered_size_filter'), 10);
		else
			remove_filter('woocommerce_product_get_image', array($this, 'filter_image_custom_size'), 10, 5);
		
		remove_filter('woocommerce_product_loop_title_classes', array($this, 'add_class_for_title'), 10);
	}
	
	public function escape_text($safe_text, $text) {
		return $text;
	}
	
	public function add_to_cart_icon($text) {
//		$settings = $this->get_settings_for_display();
		global $local_settings;
        $icon = '';
        switch ($local_settings['product_button_icon']) {
            case 'bag':
                $icon = get_theme_mod('bold_icons', 0) ? '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M20.304 5.544v0c-0.024-0.696-0.576-1.224-1.272-1.224h-2.304c-0.288-2.424-2.304-4.248-4.728-4.248-2.448 0-4.464 1.824-4.728 4.248h-2.28c-0.696 0-1.272 0.576-1.272 1.248l-0.624 15.936c-0.024 0.648 0.192 1.272 0.624 1.728 0.432 0.48 1.008 0.72 1.68 0.72h13.176c0.624 0 1.2-0.24 1.68-0.72 0.408-0.456 0.624-1.056 0.624-1.704l-0.576-15.984zM9.12 4.296c0.288-1.344 1.464-2.376 2.88-2.376s2.592 1.032 2.88 2.4l-5.76-0.024zM8.184 8.664c0.528 0 0.936-0.408 0.936-0.936v-1.536h5.832v1.536c0 0.528 0.408 0.936 0.936 0.936s0.936-0.408 0.936-0.936v-1.536h1.68l0.576 15.336c-0.024 0.144-0.072 0.288-0.168 0.384s-0.216 0.144-0.312 0.144h-13.2c-0.12 0-0.24-0.048-0.336-0.144-0.072-0.072-0.12-0.192-0.096-0.336l0.6-15.384h1.704v1.536c-0.024 0.528 0.384 0.936 0.912 0.936z"></path></svg>' :
                    '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
<path d="M20.232 5.352c-0.024-0.528-0.456-0.912-0.936-0.912h-2.736c-0.12-2.448-2.112-4.392-4.56-4.392s-4.464 1.944-4.56 4.392h-2.712c-0.528 0-0.936 0.432-0.936 0.936l-0.648 16.464c-0.024 0.552 0.168 1.104 0.552 1.512s0.888 0.624 1.464 0.624h13.68c0.552 0 1.056-0.216 1.464-0.624 0.36-0.408 0.552-0.936 0.552-1.488l-0.624-16.512zM12 1.224c1.8 0 3.288 1.416 3.408 3.216l-6.816-0.024c0.12-1.776 1.608-3.192 3.408-3.192zM7.44 5.616v1.968c0 0.336 0.264 0.6 0.6 0.6s0.6-0.264 0.6-0.6v-1.968h6.792v1.968c0 0.336 0.264 0.6 0.6 0.6s0.6-0.264 0.6-0.6v-1.968h2.472l0.624 16.224c-0.024 0.24-0.12 0.48-0.288 0.648s-0.384 0.264-0.6 0.264h-13.68c-0.24 0-0.456-0.096-0.624-0.264s-0.24-0.384-0.216-0.624l0.624-16.248h2.496z"></path>
</svg>';
                break;
            case 'cart':
                $icon = get_theme_mod('bold_icons', 0) ? '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M0.048 1.872c0 0.504 0.36 0.84 0.84 0.84h2.184l2.28 11.448c0.336 1.704 1.896 3 3.648 3h11.088c0.48 0 0.84-0.36 0.84-0.84 0-0.504-0.36-0.84-0.84-0.84h-10.992c-0.432 0-0.84-0.144-1.176-0.384l13.344-1.824c0.36 0 0.72-0.36 0.744-0.72l1.944-7.704v-0.048c0-0.096-0.024-0.384-0.192-0.552l-0.072-0.048c-0.12-0.096-0.288-0.24-0.6-0.24h-18.024l-0.408-2.16c-0.024-0.432-0.504-0.744-0.84-0.744h-2.904c-0.48-0.024-0.864 0.336-0.864 0.816zM21.912 5.544l-1.44 6.12-13.464 1.752-1.584-7.872h16.488zM5.832 20.184c0 1.56 1.224 2.784 2.784 2.784s2.784-1.224 2.784-2.784-1.224-2.784-2.784-2.784-2.784 1.224-2.784 2.784zM8.616 19.128c0.576 0 1.056 0.504 1.056 1.056s-0.504 1.056-1.056 1.056c-0.552 0-1.056-0.504-1.056-1.056s0.504-1.056 1.056-1.056zM15.48 20.184c0 1.56 1.224 2.784 2.784 2.784s2.784-1.224 2.784-2.784-1.224-2.784-2.784-2.784c-1.56 0-2.784 1.224-2.784 2.784zM18.24 19.128c0.576 0 1.056 0.504 1.056 1.056s-0.504 1.056-1.056 1.056c-0.552 0-1.056-0.504-1.056-1.056s0.504-1.056 1.056-1.056z"></path></svg>' :
                    '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
<path d="M23.76 4.248c-0.096-0.096-0.24-0.24-0.504-0.24h-18.48l-0.48-2.4c-0.024-0.288-0.384-0.528-0.624-0.528h-2.952c-0.384 0-0.624 0.264-0.624 0.624s0.264 0.648 0.624 0.648h2.424l2.328 11.832c0.312 1.608 1.848 2.856 3.48 2.856h11.28c0.384 0 0.624-0.264 0.624-0.624s-0.264-0.624-0.624-0.624h-11.16c-0.696 0-1.344-0.312-1.704-0.816l14.064-1.92c0.264 0 0.528-0.24 0.528-0.528l1.968-7.824v-0.024c-0.024-0.048-0.024-0.288-0.168-0.432zM22.392 5.184l-1.608 6.696-14.064 1.824-1.704-8.52h17.376zM8.568 17.736c-1.464 0-2.592 1.128-2.592 2.592s1.128 2.592 2.592 2.592c1.464 0 2.592-1.128 2.592-2.592s-1.128-2.592-2.592-2.592zM9.888 20.328c0 0.696-0.624 1.32-1.32 1.32s-1.32-0.624-1.32-1.32 0.624-1.32 1.32-1.32 1.32 0.624 1.32 1.32zM18.36 17.736c-1.464 0-2.592 1.128-2.592 2.592s1.128 2.592 2.592 2.592c1.464 0 2.592-1.128 2.592-2.592s-1.128-2.592-2.592-2.592zM19.704 20.328c0 0.696-0.624 1.32-1.32 1.32s-1.344-0.6-1.344-1.32 0.624-1.32 1.32-1.32 1.344 0.624 1.344 1.32z"></path>
</svg>';
                break;
            case 'cart2':
                $icon = get_theme_mod('bold_icons', 0) ? '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M23.088 1.032h-2.904c-0.336 0-0.84 0.312-0.84 0.744l-0.408 2.16h-18.024c-0.312 0-0.48 0.144-0.6 0.24l-0.072 0.048c-0.168 0.168-0.192 0.432-0.192 0.552v0.048l1.944 7.704c0.024 0.36 0.36 0.72 0.744 0.72l13.344 1.824c-0.336 0.24-0.744 0.384-1.176 0.384h-10.992c-0.504 0-0.84 0.36-0.84 0.84s0.36 0.84 0.84 0.84h11.088c1.752 0 3.312-1.296 3.648-3l2.256-11.448h2.184c0.504 0 0.84-0.36 0.84-0.84 0.024-0.456-0.36-0.816-0.84-0.816zM18.576 5.544l-1.584 7.872-13.464-1.752-1.44-6.12h16.488zM15.384 17.4c-1.56 0-2.784 1.224-2.784 2.784s1.224 2.784 2.784 2.784 2.784-1.224 2.784-2.784-1.224-2.784-2.784-2.784zM16.44 20.184c0 0.552-0.504 1.056-1.056 1.056s-1.056-0.504-1.056-1.056c0-0.576 0.504-1.056 1.056-1.056s1.056 0.504 1.056 1.056zM5.736 17.4c-1.56 0-2.784 1.224-2.784 2.784s1.224 2.784 2.784 2.784 2.784-1.224 2.784-2.784-1.224-2.784-2.784-2.784zM6.816 20.184c0 0.552-0.504 1.056-1.056 1.056s-1.056-0.504-1.056-1.056c0-0.576 0.504-1.056 1.056-1.056s1.056 0.504 1.056 1.056z"></path></svg>' :
                    '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
<path d="M0.096 4.656v0.024l1.968 7.824c0 0.264 0.264 0.528 0.528 0.528l14.064 1.92c-0.384 0.504-1.032 0.816-1.704 0.816h-11.184c-0.384 0-0.624 0.264-0.624 0.624s0.264 0.624 0.624 0.624h11.28c1.656 0 3.168-1.248 3.48-2.856l2.328-11.832h2.424c0.384 0 0.624-0.264 0.624-0.624s-0.264-0.624-0.624-0.624h-2.952c-0.24 0-0.624 0.24-0.624 0.528l-0.456 2.424h-18.528c-0.264 0-0.384 0.144-0.504 0.24-0.12 0.12-0.12 0.36-0.12 0.384zM18.984 5.184l-1.704 8.52-14.088-1.824-1.584-6.696h17.376zM12.84 20.328c0 1.464 1.128 2.592 2.592 2.592s2.592-1.128 2.592-2.592c0-1.464-1.128-2.592-2.592-2.592s-2.592 1.128-2.592 2.592zM15.432 19.008c0.696 0 1.32 0.624 1.32 1.32s-0.624 1.32-1.32 1.32-1.32-0.624-1.32-1.32 0.6-1.32 1.32-1.32zM3.024 20.328c0 1.464 1.128 2.592 2.592 2.592s2.592-1.128 2.592-2.592c0-1.464-1.128-2.592-2.592-2.592-1.44 0-2.592 1.128-2.592 2.592zM5.64 19.008c0.696 0 1.32 0.624 1.32 1.32s-0.624 1.32-1.32 1.32-1.32-0.624-1.32-1.32 0.6-1.32 1.32-1.32z"></path>
</svg>';
                break;
            case 'custom':
                if ( ! empty( $local_settings['product_button_custom_icon'] ) || ! empty( $local_settings['product_button_custom_selected_icon']['value'] ) ) :
                    ob_start();
                    \Elementor\Icons_Manager::render_icon( $local_settings['product_button_custom_selected_icon'], [ 'aria-hidden' => 'true' ] );
                    $icon = ob_get_clean();
                endif;
                break;
        }
		
		$text = $text ? '<span class="button-text">'.$text.'</span>' : $text;
		return ($local_settings['product_button_icon_align'] == 'left') ? $icon . $text : $text . $icon;
	}
	
	public function quantity_plus_icon() {
		echo '<span class="plus">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M23.52 11.4h-10.92v-10.92c0-0.264-0.216-0.48-0.48-0.48h-0.24c-0.264 0-0.48 0.216-0.48 0.48v10.92h-10.92c-0.264 0-0.48 0.216-0.48 0.48v0.24c0 0.264 0.216 0.48 0.48 0.48h10.92v10.92c0 0.264 0.216 0.48 0.48 0.48h0.24c0.264 0 0.48-0.216 0.48-0.48v-10.92h10.92c0.264 0 0.48-0.216 0.48-0.48v-0.24c0-0.264-0.216-0.48-0.48-0.48z"></path>
                </svg>
            </span>';
	}
	
	public function quantity_minus_icon() {
		echo '<span class="minus">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M23.52 11.4h-23.040c-0.264 0-0.48 0.216-0.48 0.48v0.24c0 0.264 0.216 0.48 0.48 0.48h23.040c0.264 0 0.48-0.216 0.48-0.48v-0.24c0-0.264-0.216-0.48-0.48-0.48z"></path>
                </svg>
            </span>';
	}
	
	/**
	 * Return first product's category.
	 *
	 * @since 4.1.2
	 *
	 * @return void
	 */
	public function get_product_categories() {
	    global $product;
		$product_cats = function_exists( 'wc_get_product_category_list' ) ? wc_get_product_category_list( $product->get_ID(), '\n', '', '' ) : $product->get_categories( '\n', '', '' );
		// hide html tags
        // $product_cats = strip_tags( $product_cats );
		
		if ( $product_cats ) {
			list( $first_cat ) = explode( '\n', $product_cats );
			echo '<div class="etheme-product-list-categories">'.$first_cat.'</div>';
		}
	}
	
	/**
	 * Return product sku.
	 *
	 * @since 4.1.2
	 *
	 * @return void
	 */
	public function get_product_sku() {
	    global $product;
		if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
            <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'xstore-core' ); ?>
                <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore-core' ); ?></span>
            </span>
		<?php endif;
	}

    public function get_countdown($settings, $product = null) {
        if ( !$product )
            return;

        $countdown_class = 'etheme-countdown-wrapper';
        $product_id = $product->get_ID();
        $date       = get_post_meta( $product_id, '_sale_price_dates_to', true );
        $date_from  = get_post_meta( $product_id, '_sale_price_dates_from', true );
        $time_start = get_post_meta( $product_id, '_sale_price_time_start', true );
        $time_start = explode( ':', $time_start );
        $time_end   = get_post_meta( $product_id, '_sale_price_time_end', true );
        $time_end   = explode( ':', $time_end );

        $start_hour = ( isset( $time_start[0] ) && $time_start[0] != 'Array' && $time_start[0] > 0 ) ? $time_start[0] : '00';
        $start_minute = isset( $time_start[1] ) ? $time_start[1] : '00';

        $end_hour = ( isset( $time_end[0] ) && $time_end[0] != 'Array' && $time_end[0] > 0 ) ? $time_end[0] : '00';
        $end_minute = isset( $time_end[1] ) ? $time_end[1] : '00';

        $has_variation_on_sale = false;

        if( $product && isset($settings['product_swatches']) && !!$settings['product_swatches'] && is_object($product) && $product->is_type('variable') ) {
            $variation_ids = $product->get_visible_children();
            foreach( $variation_ids as $variation_id ) {
                if ( $has_variation_on_sale ) break;
                $variation = wc_get_product( $variation_id );

                if ( $variation->is_on_sale() ) {
                    $has_variation_on_sale = true;
                    $date       = get_post_meta( $variation_id, '_sale_price_dates_to', true );
                    $date_from  = get_post_meta( $variation_id, '_sale_price_dates_from', true );
                    $time_start = get_post_meta( $variation_id, '_sale_price_time_start', true );
                    $time_start = explode( ':', $time_start );
                    $time_end   = get_post_meta( $variation_id, '_sale_price_time_end', true );
                    $time_end   = explode( ':', $time_end );

                    $start_hour = ( isset( $time_start[0] ) && $time_start[0] != 'Array' && $time_start[0] > 0 ) ? $time_start[0] : '00';
                    $start_minute = isset( $time_start[1] ) ? $time_start[1] : '00';

                    $end_hour = ( isset( $time_end[0] ) && $time_end[0] != 'Array' && $time_end[0] > 0 ) ? $time_end[0] : '00';
                    $end_minute = isset( $time_end[1] ) ? $time_end[1] : '00';
                }
            }
            if ( $has_variation_on_sale )
                $countdown_class .= ' hidden';
        }

        if ( !$date ) return;

        $now = strtotime('now');

        if ( $date_from ) {
            // place condition here because we have time start/end post_meta in XStore Theme so
            // origin time is 23:59 but user could set another and time could be already out
            $date_from = strtotime( get_gmt_from_date( date( 'Y-m-d', $date_from ) . ' ' . $start_hour . ':' . $start_minute . ':00' ) );
        }

        if ( ($date_from && $now < $date_from) ) return;

        // for frontend
        wp_enqueue_script('etheme_countdown');
        wp_enqueue_style('etheme-elementor-countdown');

        $date = strtotime( get_gmt_from_date(date('Y-m-d', $date) . ' '. $end_hour.':'.$end_minute.':00') );

        // place condition here because we have time start/end post_meta in XStore Theme so
        // origin time is 23:59 but user could set another and time could be already out
        if ( ($date && $now > $date) ) return;

        ?>
        <div class="<?php echo esc_attr($countdown_class) ?>" data-date="<?php echo $date; ?>"<?php if ($has_variation_on_sale) echo ' data-has-reinit="yes"'; ?>>

            <div class="etheme-countdown">
                <?php
                \ETC\App\Controllers\Elementor\General\Countdown::render_countdown(array(
                    'show_labels' => $settings['countdown_show_labels'],
                    'custom_labels' => $settings['countdown_custom_labels'],
                    'label_days' => $settings['countdown_label_days'],
                    'label_hours' => $settings['countdown_label_hours'],
                    'label_minutes' => $settings['countdown_label_minutes'],
                    'label_seconds' => $settings['countdown_label_seconds'],
                    'label_position' =>  'bottom',
                    'show_days' => $settings['countdown_show_days'],
                    'show_hours' => $settings['countdown_show_hours'],
                    'show_minutes' => $settings['countdown_show_minutes'],
                    'show_seconds' => $settings['countdown_show_seconds'],
                    'add_delimiter' => $settings['countdown_add_delimiter'],
                    'delimiter' => $settings['countdown_delimiter'],
                ));
                ?>
            </div>

        </div>
        <?php

    }

    /**
     * Getter of progress count bar shown above lazy load button
     *
     * @param $found_posts
     * @param $posts_per_page
     * @return void
     *
     * @since 5.2
     */
    public function get_progress_bar($found_posts, $posts_per_page) {
        $this->add_render_attribute( 'load-more-button-progress', [
            'class' => [
                'etheme-elementor-lazy-progress-wrapper',
            ],
        ]);

        $this->add_render_attribute( 'load-more-button-progress-text', [
            'class' => [
                'etheme-elementor-lazy-progress-bar-title',
            ],
            'data-text' => esc_html__('Showing {{current_count}} of {{all_count}} items', 'xstore-core')
        ]);

        $this->add_render_attribute( 'load-more-progress-bar', [
            'class' => [
                'etheme-elementor-lazy-progress-bar',
            ] ]);

        $this->add_render_attribute( 'load-more-progress-bar-inner', [
            'class' => [
                'etheme-elementor-lazy-progress-bar-inner',
            ],
            'style' => 'width:'.$posts_per_page / $found_posts * 100 . '%']);

        ?>
        <div <?php $this->print_render_attribute_string( 'load-more-button-progress' ); ?>>
            <div <?php $this->print_render_attribute_string( 'load-more-button-progress-text' ); ?>>
                <?php echo sprintf(esc_html__('Showing %s of %s items', 'xstore-core'), $posts_per_page, $found_posts); ?>
            </div>
            <span <?php $this->print_render_attribute_string( 'load-more-progress-bar' ); ?>>
                <span <?php $this->print_render_attribute_string( 'load-more-progress-bar-inner' ); ?>></span>
            </span>
        </div>
        <?php
    }

	/**
	 * Filter image by default (wp) size.
	 *
	 * @param $old_size
	 * @return mixed
	 *
	 * @since 4.1.2
	 *
	 */
	public function image_prerendered_size_filter($old_size) {
		global $local_settings;
//	    $settings = $this->get_settings_for_display();
		return $local_settings['image_size'];
	}
	
	/**
	 * Filter image with custom size.
	 *
	 * @param $image_origin
	 * @param $WC_Product
	 * @param $size
	 * @param $attr
	 * @param $placeholder
	 * @return string|string[]
	 *
	 * @since 4.1.2
	 *
	 */
	public function filter_image_custom_size($image_origin, $WC_Product, $size, $attr, $placeholder) {
//	    $settings = $this->get_settings_for_display();
		global $local_settings;
		$product_id = '';
		if ( $WC_Product->get_image_id() ) {
			$product_id = $WC_Product->get_image_id();
		} elseif ( $WC_Product->get_parent_id() ) {
			$parent_product = wc_get_product( $WC_Product->get_parent_id() );
			if ( $parent_product ) {
				$product_id = $parent_product->get_image_id();
			}
		}
		
		if ( $product_id ) {
			$custom_size = $local_settings['image_custom_dimension'];
			$image = \Elementor\Group_Control_Image_Size::get_attachment_image_html(
				array(
					'image' => array(
						'id' => $product_id,
					),
					'image_custom_dimension' =>
						array(
							'width' => $custom_size['width'],
							'height' => $custom_size['width']
						),
					'image_size' => 'custom',
				)
			);
			$image = str_replace(
				'<img ',
				sprintf('<img width="%1s" height="%2s"',
					$custom_size['width'],
					$custom_size['height']
				),
				$image
			);
		}
		else
			$image = wc_placeholder_img( $size, $attr );
		
		return $image;
	}
	
	/**
	 * Wraps title in link.
	 *
	 * @param $title
	 * @param $id
	 * @return string
	 *
	 * @since 4.1.2
	 *
	 */
	public function add_link_for_title($title, $id) {
		$permalink = get_permalink( $id );
		return ( $permalink ) ? '<a href="'.$permalink.'">'.$title.'</a>' : $title;
	}
	
	/**
	 * Function that returns rendered title by chars/words limit.
	 *
	 * @param $title
	 * @return mixed|string
	 *
	 * @since 4.1.2
	 *
	 */
	public function limit_title_string($title) {
//		$settings = $this->get_settings_for_display();
		global $local_settings;
		if ( $local_settings['product_title_limit'] > 0) {
			if ( $local_settings['product_title_limit_type'] == 'chars' ) {
				return Elementor::limit_string_by_chars($title, $local_settings['product_title_limit']);
			}
            elseif ( $local_settings['product_title_limit_type'] == 'words' ) {
				return Elementor::limit_string_by_words($title, $local_settings['product_title_limit']);
			}
		}
		return $title;
	}
	
	/**
	 * Function that returns rendered excerpt by chars/words limit.
	 *
	 * @param $title
	 * @return mixed|string
	 *
	 * @since 4.1.2
	 *
	 */
	public function limit_excerpt_string($excerpt) {
//		$settings = $this->get_settings_for_display();
		global $local_settings;
		if ( $local_settings['product_excerpt_limit'] > 0) {
			if ( $local_settings['product_excerpt_limit_type'] == 'chars' ) {
				return Elementor::limit_string_by_chars($excerpt, $local_settings['product_excerpt_limit']);
			}
            elseif ( $local_settings['product_excerpt_limit_type'] == 'words' ) {
				return Elementor::limit_string_by_words($excerpt, $local_settings['product_excerpt_limit']);
			}
		}
		return $excerpt;
	}
	
	/**
	 * Function that adds custom class for product title.
	 *
	 * @param $class
	 * @return string
	 *
	 * @since 4.1.2
	 *
	 */
	public function add_class_for_title($class) {
		$class .= ($class) ? ' ' : '';
		$class .= 'etheme-product-list-title';
		return $class;
	}
	
	/**
	 * Function that adds custom class for loop button (add-to-cart/read-more/etc).
	 *
	 * @param $args
	 * @return mixed
	 *
	 * @since 4.1.2
	 *
	 */
	public function add_class_for_button($args) {
		$args['class'] .= ' etheme-product-list-button';
		return $args;
	}

    /**
     * Filter Current_query products content with the current product content
     *
     * @param $template
     * @return void
     */
    public function filter_template_part($template) {
        global $local_settings;
        // secure if settings were not set before
        if ( !$local_settings ) {
            $local_settings = $this->get_settings_for_display();
        }
        $this->get_content_product( $local_settings );
    }

	/**
	 * All product element that could be shown.
	 *
	 * @since 4.1.2
	 *
	 * @return mixed
	 */
	public static function get_product_elements() {
	    $elements = array(
		    'image' => esc_html__('Show Image', 'xstore-core'),
		    'categories' => esc_html__('Show Categories', 'xstore-core'),
		    'title' => esc_html__('Show Title', 'xstore-core'),
		    'rating' => esc_html__('Show Rating', 'xstore-core'),
		    'price' => esc_html__('Show Price', 'xstore-core'),
		    'excerpt' => esc_html__('Show Excerpt', 'xstore-core'),
		    'sku' => esc_html__('Show SKU', 'xstore-core'),
		    'button' => esc_html__('Show Add To Cart Button', 'xstore-core'),
		    'countdown' => esc_html__('Show Countdown', 'xstore-core'),
	    );
	    return apply_filters('etheme_product_grid_list_product_elements', $elements);
    }
	
	/**
	 * Return filtered product taxonomies.
	 *
	 * @since 4.2.1
	 *
	 * @return mixed
	 */
	public static function product_taxonomies_to_filter() {
		return apply_filters('etheme_product_grid_list_product_taxonomies', array(
			'product_cat' => esc_html__('Categories', 'xstore-core'),
			'product_tag' => esc_html__('Product tags', 'xstore-core'),
		) );
	}

    /**
     * Return filtered product data sources
     *
     * @since 5.2
     *
     * @return mixed
     */
    public static function get_data_source_list() {
        return apply_filters('etheme_product_grid_list_product_data_source', array(
            'all' => esc_html__( 'All Products', 'xstore-core' ),
            'featured' => esc_html__( 'Featured Products', 'xstore-core' ),
            'onsale' => esc_html__( 'On-sale Products', 'xstore-core' ),
            'product_ids' => esc_html__( 'List of IDs', 'xstore-core' ),
            'recently_viewed' => esc_html__( 'Recently Viewed Products', 'xstore-core')
        ));
    }
	
	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  4.1
	 */
	public static function get_instance( $shortcodes = array() ) {
		
		if ( null == self::$instance ) {
			self::$instance = new self( $shortcodes );
		}
		
		return self::$instance;
	}
    
}
