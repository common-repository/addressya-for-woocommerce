<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Admin_Driver {

    public function __construct() {        
        add_action( 'wp_ajax_addressya_assign_driver', array( $this, 'assign_driver' ) );
        add_action( 'wp_ajax_nopriv_addressya_assign_driver', array( $this, 'assign_driver' ) ); 
        add_action( 'wp_ajax_addressya_unassign_driver', array( $this, 'unassign_driver' ) );
        add_action( 'wp_ajax_nopriv_addressya_unassign_driver', array( $this, 'unassign_driver' ) ); 
    }

    public function assign_driver() {
        global $wpdb;
        
        //$driver_uid = get_post_meta(sanitize_text_field($_REQUEST['driver_member_id']), 'addressya_driver_uid', true);
        
        $meta_member_id = 'driver_member_id_' . sanitize_text_field($_REQUEST['value']);
        $meta_firstname = 'driver_firstname_' . sanitize_text_field($_REQUEST['value']);
        $meta_id = 'driver_id_' . sanitize_text_field($_REQUEST['value']);
        
        if (isset($_REQUEST['value']) && sanitize_text_field($_REQUEST['value']) == 'billing') {
            $customer_username = get_post_meta(sanitize_text_field($_REQUEST['order_id']), '_billing_addressya_username', true);
            $customer_uid = get_post_meta(sanitize_text_field($_REQUEST['order_id']), '_billing_addressya_uid', true);
            $location_id = get_post_meta(sanitize_text_field($_REQUEST['order_id']), '_billing_addressya_locationId', true);
        } else {
            $customer_username = get_post_meta(sanitize_text_field($_REQUEST['order_id']), 'addressya_user_ship', true);
            $customer_uid = get_post_meta(sanitize_text_field($_REQUEST['order_id']), 'addressya_user_uid_ship', true);
            $location_id = get_post_meta(sanitize_text_field($_REQUEST['order_id']), 'addressya_location_ship', true);
        }
        //echo $customer_username;wp_die();
        //$result = ADDRESSYA_Admin_API::copyConnectionToDriver($driver_uid, $customer_uid, $location_id);
        $result = ADDRESSYA_Admin_API::copyConnectionToDriver($_REQUEST['driver_member_id'], $customer_uid, $location_id);
        if(isset($result['message']) && sanitize_text_field($result['message']) == 'Connection Copied') {
            //echo $_REQUEST['order_id'];die;
            update_post_meta(sanitize_text_field($_REQUEST['order_id']), $meta_member_id, $_REQUEST['driver_member_id']);
            //update_post_meta(sanitize_text_field($_REQUEST['order_id']), $meta_firstname, $firstname);
            //update_post_meta(sanitize_text_field($_REQUEST['order_id']), $meta_id, $_REQUEST['driver_member_id']);
/*             $message = get_option( 'addressya_business_name' ) . " assigned you for a delivery to " . $customer_username . ". Find " . $customer_username . " in your Connections list for the delivery address. Order reference : " . sanitize_text_field($_REQUEST['order_id']);
            ADDRESSYA_Admin_API::sendNotification($location_id, $driver_uid, $username, $customer_username, 'assigned-customer', $message);
            $message = "Your order from " . get_option( 'addressya_business_name' ) . " will be delivered by " . $firstname . " " . $lastname . " (Contact No. : " . $mobile .  "). Order reference " . sanitize_text_field($_REQUEST['order_id']);
            ADDRESSYA_Admin_API::sendNotification($location_id, $customer_uid, $username, $customer_username, 'assigned-driver', $message); */
        }
       
        wp_send_json_success($result);
    }

    public function assign_driver_bk_24_3_2023() {
        global $wpdb;
   
        $firstname = get_post_meta(sanitize_text_field($_REQUEST['driver_member_id']), 'addressya_driver_firstname', true);
        $driver_uid = get_post_meta(sanitize_text_field($_REQUEST['driver_member_id']), 'addressya_driver_uid', true);
        $lastname = get_post_meta(sanitize_text_field($_REQUEST['driver_member_id']), 'addressya_driver_lastname', true);
        $mobile = get_post_meta(sanitize_text_field($_REQUEST['driver_member_id']), 'addressya_driver_number', true);
        $username = get_post_meta(sanitize_text_field($_REQUEST['driver_member_id']), 'addressya_driver_username', true);
        
        $meta_member_id = 'driver_member_id_' . sanitize_text_field($_REQUEST['value']);
        $meta_firstname = 'driver_firstname_' . sanitize_text_field($_REQUEST['value']);
        $meta_id = 'driver_id_' . sanitize_text_field($_REQUEST['value']);
        //$driver_uid = get_post_meta(sanitize_text_field($_REQUEST['driver_member_id']), 'addressya_driver_uid', true);
        
        if (isset($_REQUEST['value']) && sanitize_text_field($_REQUEST['value']) == 'billing') {
            $customer_username = get_post_meta(sanitize_text_field($_REQUEST['order_id']), '_billing_addressya_username', true);
            $customer_uid = get_post_meta(sanitize_text_field($_REQUEST['order_id']), '_billing_addressya_uid', true);
            $location_id = get_post_meta(sanitize_text_field($_REQUEST['order_id']), '_billing_addressya_locationId', true);
        } else {
            $customer_username = get_post_meta(sanitize_text_field($_REQUEST['order_id']), 'addressya_user_ship', true);
            $customer_uid = get_post_meta(sanitize_text_field($_REQUEST['order_id']), 'addressya_user_uid_ship', true);
            $location_id = get_post_meta(sanitize_text_field($_REQUEST['order_id']), 'addressya_location_ship', true);
        }
        //echo $customer_username;wp_die();
        $result = ADDRESSYA_Admin_API::copyConnectionToDriver($driver_uid, $customer_uid, $location_id);
        //echo $result['message'];wp_die();
        if(isset($result['message']) && sanitize_text_field($result['message']) == 'Connection Copied') {
            //echo $_REQUEST['order_id'];die;
            update_post_meta(sanitize_text_field($_REQUEST['order_id']), $meta_member_id, $driver_uid);
            update_post_meta(sanitize_text_field($_REQUEST['order_id']), $meta_firstname, $firstname);
            update_post_meta(sanitize_text_field($_REQUEST['order_id']), $meta_id, $_REQUEST['driver_member_id']);
            $message = get_option( 'addressya_business_name' ) . " assigned you for a delivery to " . $customer_username . ". Find " . $customer_username . " in your Connections list for the delivery address. Order reference : " . sanitize_text_field($_REQUEST['order_id']);
            ADDRESSYA_Admin_API::sendNotification($location_id, $driver_uid, $username, $customer_username, 'assigned-customer', $message);
            $message = "Your order from " . get_option( 'addressya_business_name' ) . " will be delivered by " . $firstname . " " . $lastname . " (Contact No. : " . $mobile .  "). Order reference " . sanitize_text_field($_REQUEST['order_id']);
            ADDRESSYA_Admin_API::sendNotification($location_id, $customer_uid, $username, $customer_username, 'assigned-driver', $message);
        }
        wp_send_json_success($result);
    }

    
    public function unassign_driver() {
        global $wpdb;
      
        $meta_member_id = 'driver_member_id_' . sanitize_text_field($_REQUEST['value']);
        $meta_firstname = 'driver_firstname_' . sanitize_text_field($_REQUEST['value']);
        $meta_id = 'driver_id_' . sanitize_text_field($_REQUEST['value']);
        
        //$meta_id = get_post_meta(sanitize_text_field($_REQUEST['order_id']), 'driver_id_billing', true);
        //$meta_firstname = get_post_meta(sanitize_text_field($_REQUEST['order_id']), 'driver_firstname_billing', true);
        $member_id = get_post_meta(sanitize_text_field($_REQUEST['order_id']), 'driver_member_id_billing', true);
        
        $customer_username = get_post_meta(sanitize_text_field($_REQUEST['order_id']), '_billing_addressya_username', true);
        $customer_uid = get_post_meta(sanitize_text_field($_REQUEST['order_id']), '_billing_addressya_uid', true);
        $location_id = get_post_meta(sanitize_text_field($_REQUEST['order_id']), '_billing_addressya_locationId', true);
        
        //echo "meta_member_id: " . $meta_member_id . "meta_firstname: " . $meta_firstname . "meta_id: " . $meta_id;
        //echo $customer_username . " customer_uid: " . $customer_uid . " meta_member_id: " . $meta_member_id . " meta_firstname: " . $meta_firstname . " meta_id: " . $meta_id . " location_id: " . $location_id;
        //wp_die();
      
        $result = ADDRESSYA_Admin_API::deleteInheritedConnectionFromDriver($member_id, $customer_uid, $location_id);
        
        if(isset($result['message']) && sanitize_text_field($result['message']) == 'Connection Deleted') {
            $message = get_option( 'addressya_business_name' ) . " unassigned you from this order reference : " . sanitize_text_field($_REQUEST['order_id']) . ". You do no longer have access to the customers address details.";
            ADDRESSYA_Admin_API::sendNotification($location_id, $member_id, '', $customer_username, 'unassigned-customer', $message);
            delete_post_meta(sanitize_text_field($_REQUEST['order_id']), $meta_member_id);
            delete_post_meta(sanitize_text_field($_REQUEST['order_id']), $meta_firstname);
            delete_post_meta(sanitize_text_field($_REQUEST['order_id']), $meta_id);
        }
        wp_send_json_success($result);
    }


}

new ADDRESSYA_Admin_Driver;
//var_dump( ADDRESSYA_Admin_Driver_Functions::assign_driver() );