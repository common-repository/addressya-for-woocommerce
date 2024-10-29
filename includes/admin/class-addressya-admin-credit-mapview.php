<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Admin_Credit_Mapview {

    public function __construct() {        
        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
        add_action( 'wp_ajax_addressya_get_credit_application', array( $this, 'get_credit_application' ) );
        add_action( 'wp_ajax_nopriv_addressya_get_credit_application', array( $this, 'get_credit_application' ) ); 
        add_action( 'wp_ajax_get_business_credit_applications', array( $this, 'get_business_credit_applications' ) );
        add_action( 'wp_ajax_nopriv_get_business_credit_applications', array( $this, 'get_business_credit_applications' ) );
    }
    public function register_meta_boxes() {
        add_meta_box(
            'addressya-creditapp-metabox',
            'Credit Application',
            array( $this, 'meta_box' ),
            'addressya_creditapp',
            'normal',
            'high'
        );
    }

    public function meta_box() {
        global $post;
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/meta-boxes/html-creditapp-update.php';
    }

    public function get_credit_application() {
        global $wpdb;
        die("here");
        $results = $wpdb->get_results( 'SELECT * FROM addressya_creditapp' ); 
        return $results;
        foreach ( $results as $result ) { 
           // do something with the data 
           echo $result->column_name; 
        } 
    }

    public function get_business_credit_applications()
    {
        $request = ADDRESSYA_Admin_API::getBusinessCreditApplications($_REQUEST['status'],);
        wp_send_json_success($request);
    }

}

new ADDRESSYA_Admin_Credit_Mapview;