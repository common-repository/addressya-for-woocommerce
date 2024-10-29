<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Admin_Driver_New {

    public function __construct() {        
        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
        add_action( 'wp_ajax_get_driver', array( $this, 'get_driver' ) );
        add_action( 'wp_ajax_nopriv_get_driver', array( $this, 'get_driver' ) );
        add_action( 'wp_ajax_addressya_add_driver', array( $this, 'addressya_add_driver' ) );
        add_action( 'wp_ajax_nopriv_addressya_add_driver', array( $this, 'addressya_add_driver' ) );
        add_action( 'wp_ajax_addressya_get_driver', array( $this, 'addressya_get_driver' ) );
        add_action( 'wp_ajax_nopriv_addressya_get_driver', array( $this, 'addressya_get_driver' ) );
        add_action( 'wp_ajax_addressya_update_driver', array( $this, 'addressya_update_driver' ) );
        add_action( 'wp_ajax_nopriv_addressya_update_driver', array( $this, 'addressya_update_driver' ) );
        
        add_action( 'wp_ajax_addressya_delete_driver', array( $this, 'addressya_delete_driver' ) );
        add_action( 'wp_ajax_nopriv_addressya_delete_driver', array( $this, 'addressya_delete_driver' ) );
        
    }
    public function register_meta_boxes() {
        add_meta_box(
            'addressya-creditapp-metabox',
            'Credit Application 2',
            array( $this, 'meta_box' ),
            'addressya_creditapp_2',
            'normal',
            'high'
        );
    }

    public function meta_box() {
        global $post;
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/meta-boxes/html-creditapp-update.php';
    }

    public function get_driver()
    {
        $request = ADDRESSYA_Admin_API::getDriver();
        wp_send_json_success($request);
    }

    public function addressya_add_driver()
    {
        $userNameAvailable = ADDRESSYA_API::is_user_exists( $_REQUEST['username'] );
        if ($userNameAvailable['status'] != 200) {
            /* echo "<pre>";
            print_r($userNameAvailable);
            wp_die(); */
            return $userNameAvailable['message'];
        } else {           

            //$response = ADDRESSYA_Admin_API::addDriverUnderOrganization($userNameAvailable['data']['id'], $_POST['addressya_driver_start_time'], $_POST['addressya_driver_end_time'], $timeZone);
            $response = ADDRESSYA_Admin_API::addDriver($userNameAvailable['data']['id'],$_REQUEST['vehicle_name'],$_REQUEST['vehicle_number'],$_REQUEST['wh_start_time'],$_REQUEST['wh_end_time'],$_REQUEST['wh_timezone']);
            /* echo "<pre>";
            print_r($response);
            wp_die(); */
        }        
        wp_send_json_success($response);
    }

    public function addressya_update_driver()
    {
        
        $userNameAvailable = ADDRESSYA_API::is_user_exists( $_REQUEST['username'] );
        if ($userNameAvailable['status'] != 200) {
            wp_send_json_success($userNameAvailable['message']);
            //return $userNameAvailable['message'];
        } else {           

            $response = ADDRESSYA_Admin_API::updateDriver($_REQUEST['memberId'],$_REQUEST['vehicleName'],$_REQUEST['vehicleNumber'],$_REQUEST['startTime'],$_REQUEST['endTime'],$_REQUEST['timezone'],$_REQUEST['status']);
        }        
        wp_send_json_success($_REQUEST['wh_timezone']);
    }

    public function addressya_delete_driver()
    {
        //wp_send_json_success($_REQUEST['member_id']);
        $response = ADDRESSYA_Admin_API::deleteDriver($_REQUEST['member_id']);       
        wp_send_json_success($response);
    }

    public function addressya_get_driver()
    {
        $response = ADDRESSYA_Admin_API::getDriverById($_REQUEST['member_id']);
        /* echo "<pre>";
        print_r($response);
        wp_die();      */ 
        wp_send_json_success($response);
    }

}

new ADDRESSYA_Admin_Driver_New;