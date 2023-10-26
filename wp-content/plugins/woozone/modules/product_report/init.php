<?php
/*
* Define class WooZoneAmzReport
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;

if (class_exists('WooZoneProductReport') != true) { class WooZoneProductReport {

	const VERSION = '1.0';

	static protected $_instance;

	public $the_plugin = null;

	private $module_folder = '';
	private $module = '';

	public $timer; // timer object



	// Required __construct() function that initalizes the AA-Team Framework
	public function __construct()
	{
		global $WooZone;

		$this->the_plugin = $WooZone;
		$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/product_report/';
		$this->module = $this->the_plugin->cfg['modules']['product_report'];

		if ( $this->the_plugin->is_admin ) {
			add_action('admin_menu', array( $this, 'adminMenu' ));
		}

		add_action( 'add_meta_boxes', array($this, 'product_report_meta_boxes') );
		//add_action( 'wp_ajax_WooZoneAmzReport', array( $this, 'ajax_request' ) );
	}



	//====================================================================================
	//== AJAX REQUEST
	//====================================================================================

	public function ajax_request() {

		$requestData = array(
			'action' 	=> isset($_REQUEST['sub_action']) ? (string) $_REQUEST['sub_action'] : '',
		);
		extract($requestData);
		//var_dump('<pre>', $requestData , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

		$ret = array(
			'status' => 'invalid',
			'msg' => 'Invalid action!',
		);

		if ( empty($action) || !in_array($action, array(
			'add_product',
		)) ) {
			die(json_encode($ret));
		}

		//:: actions
		switch ( $action ) {


		}

		die( json_encode( $ret ) );
	}


	// Hooks
	public function adminMenu()
	{  
		if( isset($_REQUEST['post']) && isset($_REQUEST['action']) == 'edit' ) {
			wp_enqueue_script(
				'WooZone/product_report',
				$this->module_folder . 'assets/product_report.js',
				array(),
				//'1.0&time=' . time(),
				'1.0',
				true
			);

			wp_localize_script( 'WooZone/product_report', 'WooZoneProductReport', array(
				'ajaxurl' => admin_url('admin-ajax.php')
			) );
		}
	}

	/**
     * Adds the meta box container.
     */
    public function product_report_meta_boxes( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'product' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'product_issue_report',
                __( '[WooZone] Report a problem', 'woozone' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'normal',
                'high'
            );
        }
    }
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        //wp_nonce_field( 'woozone_product_issue_report', 'woozone_product_issue_report_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $asin = get_post_meta( $post->ID, '_amzASIN', true );
 
        // Display the form, using the current value.
        ?>
        <input type="hidden" name="client_ipc" value="<?php echo esc_attr( get_option( 'WooZone_register_key' ) ); ?>"/>
        <input type="hidden" name="wzone_product_asin" value="<?php echo esc_attr( $asin ); ?> "/>
        <input type="hidden" name="wzone_product_permalink" value="<?php echo esc_attr( get_the_permalink() ); ?> "/>
        
        <div>
	        <label for="issue_report_asin">
	            <?php _e( 'Product ASIN', 'woozone' ); ?>
	        </label>
	        <input type="text" disabled="disabled" id="issue_report_asin" name="issue_report_asin" value="<?php echo esc_attr( $asin ); ?>" size="15" />
		</div>
        <div>
	        <h3><?php _e( 'Description report', 'woozone' ); ?></h3>
	        <p><i><?php esc_html_e( 'Here you can write the issue you encounter with this prodct after import.', 'woozone' ); ?></i></p>
	        <textarea name="wzone_product_report" rows="5" cols="75"></textarea>
        </div>
        
        <a href="#" class="button-primary button-large" id="send_product_report"><?php esc_html_e('Send report', 'woozone'); ?></a>
        <?php
    }
	
	// Singleton pattern
	static public function getInstance()
	{
		if (!self::$_instance) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

} } // end class

// Initialize the WooZoneAmzReport class
$WooZoneProductReport = WooZoneProductReport::getInstance();
